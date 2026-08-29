import Quill from "quill";
import "quill/dist/quill.snow.css";

export default function alpineQuill(Alpine) {
    Alpine.directive(
        "quill",
        (el, { expression }, { evaluateLater, cleanup }) => {
            // 1. Fallback gracefully if x-quill has no parameters
            const getContent = expression
                ? evaluateLater(expression)
                : (cb) => cb({});

            getContent((userOptions) => {
                const options = Object.assign(
                    {
                        theme: "snow",
                        placeholder: "Write something...",
                    },
                    typeof userOptions === "object" ? userOptions : {},
                );

                // 2. Identify or create container DIV (Quill crashes on <textarea>)
                let editorContainer;
                let targetInput = el;

                if (el.tagName === "TEXTAREA" || el.tagName === "INPUT") {
                    // Create a container DIV for Quill right before the textarea
                    editorContainer = document.createElement("div");
                    el.parentNode.insertBefore(editorContainer, el);

                    // Hide the textarea without breaking Livewire bindings
                    el.style.display = "none";
                } else {
                    editorContainer = el;
                }

                // 3. Instantiate Quill on the container DIV
                const quill = new Quill(editorContainer, options);

                // Populate initial content from textarea / Alpine model
                if (targetInput.value && targetInput.value !== "<p><br></p>") {
                    quill.root.innerHTML = targetInput.value;
                }

                let isUpdating = false;

                // 4. Sync Quill edits -> hidden input -> Livewire
                quill.on("text-change", () => {
                    if (isUpdating) return;
                    isUpdating = true;

                    const html = quill.root.innerHTML;
                    const value = html === "<p><br></p>" ? "" : html;

                    targetInput.value = value;

                    // Dispatch input & change events on the target input so Livewire detects it
                    targetInput.dispatchEvent(
                        new Event("input", { bubbles: true }),
                    );
                    targetInput.dispatchEvent(
                        new Event("change", { bubbles: true }),
                    );

                    isUpdating = false;
                });

                // 5. Sync Livewire dynamic resets -> Quill
                const handleExternalUpdate = () => {
                    if (isUpdating) return;
                    if (targetInput.value !== quill.root.innerHTML) {
                        isUpdating = true;
                        quill.root.innerHTML = targetInput.value || "";
                        isUpdating = false;
                    }
                };

                targetInput.addEventListener("input", handleExternalUpdate);

                // 6. Cleanup DOM when Livewire removes the element
                cleanup(() => {
                    targetInput.removeEventListener(
                        "input",
                        handleExternalUpdate,
                    );
                    if (editorContainer !== el) {
                        editorContainer.remove();
                        el.style.display = "";
                    }
                });
            });
        },
    );
}
