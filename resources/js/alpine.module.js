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
