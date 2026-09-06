export default function alpineSelect2(Alpine) {
    Alpine.directive(
        "select2",
        function (el, { expression }, { evaluate, cleanup }) {
            let userConfig = evaluate(expression) || {};

            Promise.all([import("../plugin/select2.js")]).then(([select2]) => {
                if (
                    !(window.jQuery || window.jquery || window.$) ||
                    typeof window.$.fn.select2 === "undefined"
                ) {
                    return;
                }

                const $ = window.jQuery || window.$;

                // ==========================================
                // CORE HOTFIX: Patch Select2 4.1.0 unbound Ajax bug
                // ==========================================
                try {
                    const SelectAdapter = $.fn.select2.amd.require(
                        "select2/data/select",
                    );
                    if (
                        SelectAdapter &&
                        SelectAdapter.prototype &&
                        SelectAdapter.prototype._normalizeItem
                    ) {
                        const originalNormalize =
                            SelectAdapter.prototype._normalizeItem;
                        SelectAdapter.prototype._normalizeItem = function (
                            item,
                        ) {
                            // Safeguard 'this' context if passed unbound inside Select2's internal array maps
                            return originalNormalize.call(this || {}, item);
                        };
                    }
                } catch (e) {
                    console.warn("Select2 core patch could not be applied:", e);
                }
                // ==========================================

                // 1. Wrap element to preserve layout from Livewire re-renders
                const container = document.createElement("div");
                el.parentElement.insertBefore(container, el);
                container.setAttribute("wire:ignore", "");
                container.appendChild(el);

                // 2. Setup standard configurations
                let finalConfig = {
                    placeholder: userConfig.placeholder || "Select an option",
                    dropdownParent: userConfig.dropdownParent || container,
                    allowClear: userConfig.allowClear ?? true,
                    ...userConfig,
                };

                // 3. Setup AJAX configuration properties
                if (userConfig.url) {
                    finalConfig.ajax = {
                        url: userConfig.url,
                        dataType: "json",
                        delay: 250,
                        xhrFields: {
                            withCredentials: true,
                        },
                        data: function (params) {
                            return {
                                search: params.term,
                            };
                        },
                        processResults: function (data) {
                            // Maps directly over your array payload envelope
                            return {
                                results: data.data || data,
                            };
                        },
                        ...userConfig.ajax,
                    };
                }

                // 4. Initialize Select2 safely
                let $el = $(el).select2(finalConfig);
                const $select2Container = $el.next(".select2-container");

                const modelName =
                    el.getAttribute("wire:model") ||
                    el.getAttribute("wire:model.live") ||
                    el.getAttribute("wire:model.blur") ||
                    "select2";

                let isUpdating = false;

                // Sync change event to Livewire backend hooks
                $el.on("change", (e) => {
                    if (isUpdating) return;
                    isUpdating = true;
                    el.dispatchEvent(new Event("change", { bubbles: true }));
                    isUpdating = false;
                });

                // Clear button dropdown-opening preventions
                $el.on("select2:unselecting", function () {
                    $(this).data("unselecting", true);
                }).on("select2:opening", function (e) {
                    if ($(this).data("unselecting")) {
                        $(this).removeData("unselecting");
                        e.preventDefault();
                    }
                });

                const clearEvent = () => {
                    isUpdating = true;
                    $el.val(null).trigger("change");
                    isUpdating = false;
                };
                window.addEventListener(`${modelName}-clear`, clearEvent);

                // Sync styling layout states
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

                syncErrorState();

                // Observe element mutation details
                const observer = new MutationObserver((mutations) => {
                    if (isUpdating) return;

                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === "value") {
                            isUpdating = true;
                            $el.trigger("change.select2");
                            isUpdating = false;
                        }
                        if (mutation.attributeName === "class") {
                            syncErrorState();
                        }
                    });
                });

                observer.observe(el, {
                    attributes: true,
                    attributeFilter: ["value", "class"],
                });

                // Morph synchronization framework tracking
                if (window.Livewire) {
                    Livewire.hook("morph.updated", ({ el: updatedEl }) => {
                        if (updatedEl === el || updatedEl.contains(el)) {
                            syncErrorState();
                        }
                    });
                }

                // Release references to clean memory leaks
                cleanup(() => {
                    if ($el.data("select2")) {
                        $el.select2("destroy");
                    }
                    window.removeEventListener(
                        `${modelName}-clear`,
                        clearEvent,
                    );
                    observer.disconnect();
                });
            });
        },
    );
}
