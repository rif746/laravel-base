export default function alpineQuill(Alpine) {
    Alpine.directive('quill', function (el, { expression }, { evaluate, cleanup }) {
        const options = expression ? evaluate(expression) : {};
        const defaultOptions = { theme: 'snow', ...options };

        if (typeof window.Quill === 'undefined') {
            throw new Error('Quill must be loaded');
        }

        const container = document.createElement('div');
        el.appendChild(container);
        const quill = new window.Quill(container, defaultOptions);

        let isUpdating = false;

        // 1. Text changes handler
        quill.on('text-change', () => {
            if (isUpdating) return;
            isUpdating = true;
            const htmlContent = quill.root.innerHTML;
            el.value = htmlContent === '<p><br></p>' ? '' : htmlContent;
            el.dispatchEvent(new Event('input', { bubbles: true }));
            isUpdating = false;
        });

        // 2. Element Value Getter/Setter
        Object.defineProperty(el, 'value', {
            get() { return quill.root.innerHTML; },
            set(newValue) {
                if (isUpdating || newValue === quill.root.innerHTML) return;
                isUpdating = true;
                quill.root.innerHTML = newValue || '';
                isUpdating = false;
            },
            configurable: true
        });

        quill.on('selection-change', (range) => {
            if (range == null) {
                el.dispatchEvent(new Event('blur', { bubbles: true }));
            }
        });

        cleanup(() => el.innerHTML = '')
    })
}
