import "./bootstrap";

if (import.meta.env.PROD) {
    document.addEventListener("livewire:init", () => {
        Livewire.hook("request", ({ fail }) => {
            fail(({ status, preventDefault }) => {
                switch (status) {
                    case 419:
                        window.notify({
                            icon: "warning",
                            title: "419 Expired",
                            message: "Invalid or Expired Token",
                        });
                        break;

                    case 403:
                        window.notify({
                            icon: "warning",
                            title: "403 Unauthorized",
                            message: "You are not authorized to do this action",
                        });
                        break;

                    case 500:
                        window.notify({
                            icon: "error",
                            title: "500 System Error",
                            message:
                                "Your action causing error, please contact developer.",
                        });
                        break;

                    case 404:
                        window.notify({
                            icon: "warning",
                            title: "404 Not Found",
                            message: "Action not found",
                        });
                        break;

                    default:
                        break;
                }
                preventDefault();
            });
        });
    });
}

document.addEventListener("livewire:init", () => {
    Alpine.data("theme", (dark = false) => ({
        darkMode: dark,
        toggleTheme: () => {
            darkMode = !darkMode;
        },
        init() {
            if (
                !("darkMode" in localStorage) &&
                window.matchMedia("(prefers-color-scheme: dark)").matches
            ) {
                localStorage.setItem("darkMode", JSON.stringify(true));
            }
            this.darkMode =
                dark || JSON.parse(localStorage.getItem("darkMode"));
            this.$watch("darkMode", (value) =>
                localStorage.setItem("darkMode", JSON.stringify(value)),
            );
        },
    }));
});
