export default function alpineQuill(Alpine) {
    Alpine.directive(
        "quill",
        (el, { expression }, { evaluateLater, cleanup }) => {
            const getContent = expression
                ? evaluateLater(expression)
                : (cb) => cb({});

            Promise.all([
                import("quill"),
                import("quill/dist/quill.snow.css"),
            ]).then(([quillModule]) => {
                // Extract default export constructor
                const Quill = quillModule.default || quillModule;

                getContent((userOptions) => {
                    const options = Object.assign(
                        {
                            theme: "snow",
                            placeholder: "Write something...",
                        },
                        typeof userOptions === "object" ? userOptions : {},
                    );

                    let editorContainer;
                    let targetInput = el;

                    if (el.tagName === "TEXTAREA" || el.tagName === "INPUT") {
                        // Create a wrapper div to hold Quill UI and prevent Livewire morph issues
                        const wrapper = document.createElement("div");
                        wrapper.setAttribute("wire:ignore", "");

                        editorContainer = document.createElement("div");
                        wrapper.appendChild(editorContainer);

                        el.parentNode.insertBefore(wrapper, el);
                        el.style.display = "none";
                    } else {
                        editorContainer = el;
                        editorContainer.setAttribute("wire:ignore", "");
                    }

                    // Instantiate Quill constructor safely
                    const quill = new Quill(editorContainer, options);

                    // Populate initial value safely
                    if (
                        targetInput.value &&
                        targetInput.value !== "<p><br></p>"
                    ) {
                        quill.root.innerHTML = targetInput.value;
                    }

                    let isUpdating = false;

                    // Sync Quill edits -> hidden input -> Livewire
                    quill.on("text-change", () => {
                        if (isUpdating) return;
                        isUpdating = true;

                        const html = quill.root.innerHTML;
                        const value = html === "<p><br></p>" ? "" : html;

                        targetInput.value = value;
                        targetInput.dispatchEvent(
                            new Event("input", { bubbles: true }),
                        );
                        targetInput.dispatchEvent(
                            new Event("change", { bubbles: true }),
                        );

                        isUpdating = false;
                    });

                    // Sync external updates (e.g. Livewire form resetting) -> Quill
                    const handleExternalUpdate = () => {
                        if (isUpdating) return;
                        if (targetInput.value !== quill.root.innerHTML) {
                            isUpdating = true;
                            quill.root.innerHTML = targetInput.value || "";
                            isUpdating = false;
                        }
                    };

                    targetInput.addEventListener("input", handleExternalUpdate);

                    cleanup(() => {
                        targetInput.removeEventListener(
                            "input",
                            handleExternalUpdate,
                        );
                        if (targetInput !== editorContainer) {
                            editorContainer.parentElement?.remove();
                            el.style.display = "";
                        }
                    });
                });
            });
        },
    );
}
