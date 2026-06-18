
export default function alpineSelect2(Alpine) {
    Alpine.directive('select2', function (el, { expression }, { evaluate, cleanup }) {
        let config = evaluate(expression);

        if((window.jQuery||window.jquery||window.$) && (typeof window.$.fn.select2 !== 'undefined')) {
            let $el = $(el).select2(config)

            const modelName = el.getAttribute('wire:model') ||
                el.getAttribute('wire:model.live') ||
                el.getAttribute('wire:model.blur') || 'select2-clear';

            let isUpdating = false

            $el.on('change', (e) => {
                if(isUpdating) return
                isUpdating = true
                el.dispatchEvent(new Event('change', {bubbles: true}))
                isUpdating = false
            })

            const event = (e) => {
                $el.val(null).trigger('change', {bubbles: true})
            }

            window.addEventListener(`${modelName}-clear`, event)

            const observer = new MutationObserver(() => {
                if (isUpdating) return;

                isUpdating = true;
                $el.trigger('change.select2'); // Sync Select2 UI with new value safely
                isUpdating = false;
            });
            observer.observe(el, { attributes: true, attributeFilter: ['value'] });

            cleanup(() => {
                $el.select2('destroy')
                window.removeEventListener(`${modelName}-clear`, event)
                observer.disconnect()
            })
        }
    })
}
