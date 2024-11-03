document.addEventListener("livewire:init", () => {
    Livewire.on("theme-changed", ({ theme }) => {
        document.documentElement.setAttribute("data-theme", theme);
        document.documentElement.setAttribute("class", theme);
    });

    Livewire.directive("modal", ({ el, directive, component, cleanup }) => {
        let [modal_name, id] = directive.expression.split(",");

        let isShow = directive.modifiers.includes("show");
        let listeners = [];

        if (!isShow) {
            modal_name = component.name;
            modal_name = modal_name.split(".");
            modal_name = modal_name[modal_name.length - 1];
            let $wire = Livewire.find(component.id);
            $wire.watch("modal", (value) => !value && $wire.clear());
            listeners.push(
                Livewire.on("open-modal", ({ name, id = null }) => {
                    if (name == modal_name) {
                        $wire.modal = true;
                        if (id !== null) $wire.load(id);
                    }
                }),

                Livewire.on("close-modal", () => {
                    $wire.modal = false;
                }),
            );
            cleanup(() => {
                listeners.forEach((listen) => listen());
            });
        } else {
            let event = {};
            event.name = "click";
            event.on = (e) => {
                e.preventDefault();
                Livewire.dispatch("open-modal", {
                    name: modal_name,
                    id: id?.replace("/ /g", ""),
                });
            };

            el.addEventListener(event.name, event.on, { capture: true });
            cleanup(() => {
                el.removeEventListener(event.name, event.on);
            });
        }
    });
    Livewire.directive("delete", ({ el, directive, component, cleanup }) => {
        let content = directive.expression;
        let [message, id] = content.split(",");

        let onClick = (e) => {
            e.preventDefault();
            window
                .confirm({
                    title: "Removal",
                    message: message,
                })
                .then((e) => {
                    if (e.isConfirmed) {
                        Livewire.find(component.id).delete(
                            id.replace("/ /g", ""),
                        );
                    }
                });
        };

        el.addEventListener("click", onClick, { capture: true });
        cleanup(() => {
            el.removeEventListener("click", onClick);
        });
    });
});
