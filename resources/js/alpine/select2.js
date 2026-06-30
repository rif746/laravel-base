
export default function alpineSelect2(Alpine) {
    Alpine.directive('select2', function (el, { expression }, { evaluate, cleanup }) {
        let config = evaluate(expression);

        if((window.jQuery||window.jquery||window.$) && (typeof window.$.fn.select2 !== 'undefined')) {
            let select2Default = {
                placeholder: config.placeholder || 'Select an option',
                ajax: config.url ? {
                    url: config.url,
                    dataType: 'json',
                    delay: 250,
                    xhrFields: {
                        withCredentials: true
                    },
                    data: function (params) {
                        return {
                            search: params.terms
                        }
                    },
                    processResults: function (data) {
                        return {
                            results: data.data || data
                        }
                    }
                } : undefined
            }

            config = Object.assign(config, select2Default)
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

            // Fix for allowClear: prevent dropdown from opening when clicking the 'x'
            $el.on('select2:unselecting', function (e) {
                $(this).data('unselecting', true);
            }).on('select2:opening', function (e) {
                if ($(this).data('unselecting')) {
                    $(this).removeData('unselecting');
                    e.preventDefault();
                }
            });

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
