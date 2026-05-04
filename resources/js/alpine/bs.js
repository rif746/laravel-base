export default function bs(Alpine) {

    Alpine.directive('bs', (el, { value, modifiers, expression }, { evaluateLater, cleanup }) => {

        let evaluate = expression ? evaluateLater(expression) : () => { }
        let handler = e => {
            evaluate(() => { }, { scope: { '$event': e }, params: [e] })
        }

        let wrapHandler = (callback, wrapper) => (e) => wrapper(callback, e)
        const eventName = `${modifiers}.bs.${value}`

        handler = wrapHandler(handler, (next, e) => {
            next(e);
        });

        el.addEventListener(eventName, handler);
        cleanup(() => {
            el.removeEventListener(eventName, handler)
        })
    })

    Alpine.magic('bs', (el) => {
        return new Proxy({}, {
            get(target, type) {
                return {
                    on(event, callback) {
                        el.addEventListener(`${event}.bs.${type}`, (e) => {
                            callback(e);
                        });
                    },
                    instance(element = undefined) {
                        const className = type.charAt(0).toUpperCase() + type.slice(1);
                        return bootstrap[className].getInstance(element ?? el);
                    },
                    updateHTML(attr, html) {
                        el.querySelector(attr).innerHTML = html
                    }
                };
            }
        });
    });
}