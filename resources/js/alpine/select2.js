export default function alpineSelect2(Alpine) {
    Alpine.directive(
        "select2",
        function (el, { expression }, { evaluate, cleanup }) {
            let config = evaluate(expression) || {};

            Promise.all([import("../plugin/select2.js")]).then(
                ([select2]) => {
                    if (
                        (window.jQuery || window.jquery || window.$) &&
                        typeof window.$.fn.select2 !== "undefined"
                    ) {
                        let modalContainer = el.closest(".modal");
                        let select2Default = {
                            placeholder:
                                config.placeholder || "Select an option",
                            dropdownParent:
                                config.dropdownParent ||
                                modalContainer ||
                                el.parentElement,
                            allowClear: config.allowClear ?? true,
                            ajax: config.url
                                ? {
                                      url: config.url,
                                      dataType: "json",
                                      delay: 250,
                                      xhrFields: {
                                          withCredentials: true,
                                      },
                                      data: function (params) {
                                          return {
                                              search: params.terms,
                                          };
                                      },
                                      processResults: function (data) {
                                          return {
                                              results: data.data || data,
                                          };
                                      },
                                  }
                                : undefined,
                        };

                        const container = document.createElement("div");
                        el.parentElement.insertBefore(container, el);

                        // Keep wire:ignore on container so Livewire DOM diffing does not destroy Select2 UI
                        container.setAttribute("wire:ignore", "");
                        container.appendChild(el);

                        config = Object.assign(select2Default, config);
                        let $el = $(el).select2(config);

                        const $select2Container =
                            $el.next(".select2-container");

                        const modelName =
                            el.getAttribute("wire:model") ||
                            el.getAttribute("wire:model.live") ||
                            el.getAttribute("wire:model.blur") ||
                            "select2";

                        let isUpdating = false;

                        $el.on("change", (e) => {
                            if (isUpdating) return;
                            isUpdating = true;
                            el.dispatchEvent(
                                new Event("change", { bubbles: true }),
                            );
                            isUpdating = false;
                        });

                        // Fix for allowClear: prevent dropdown from opening when clicking the 'x'
                        $el.on("select2:unselecting", function (e) {
                            $(this).data("unselecting", true);
                        }).on("select2:opening", function (e) {
                            if ($(this).data("unselecting")) {
                                $(this).removeData("unselecting");
                                e.preventDefault();
                            }
                        });

                        const event = (e) => {
                            $el.val(null).trigger("change", { bubbles: true });
                        };

                        window.addEventListener(`${modelName}-clear`, event);

                        // Sync error state (is-invalid) from native select to Select2 container
                        const syncErrorState = () => {
                            if (el.classList.contains("is-invalid")) {
                                container.classList.add("is-invalid");
                                $select2Container
                                    .find(".select2-selection")
                                    .addClass("is-invalid border-danger");
                            } else {
                                container.classList.remove("is-invalid");
                                $select2Container
                                    .find(".select2-selection")
                                    .removeClass("is-invalid border-danger");
                            }
                        };

                        // Run initial check on load
                        syncErrorState();

                        // Observe attribute changes on the native <select> element
                        const observer = new MutationObserver((mutations) => {
                            if (isUpdating) return;

                            mutations.forEach((mutation) => {
                                // Update Select2 UI if value attribute changes
                                if (mutation.attributeName === "value") {
                                    isUpdating = true;
                                    $el.trigger("change.select2");
                                    isUpdating = false;
                                }

                                // Sync error styling if class attribute changes (e.g. is-invalid added/removed)
                                if (mutation.attributeName === "class") {
                                    syncErrorState();
                                }
                            });
                        });

                        observer.observe(el, {
                            attributes: true,
                            attributeFilter: ["value", "class"],
                        });

                        // Listen to Livewire DOM updates to force error styling inside wire:ignore wrapper
                        if (window.Livewire) {
                            Livewire.hook(
                                "morph.updated",
                                ({ el: updatedEl }) => {
                                    if (
                                        updatedEl === el ||
                                        updatedEl.contains(el)
                                    ) {
                                        syncErrorState();
                                    }
                                },
                            );
                        }

                        cleanup(() => {
                            $el.select2("destroy");
                            window.removeEventListener(
                                `${modelName}-clear`,
                                event,
                            );
                            observer.disconnect();
                        });
                    }
                },
            );
        },
    );
}
