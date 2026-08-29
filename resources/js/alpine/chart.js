export default function AlpineChart(Alpine) {
    Alpine.directive(
        "chart",
        function (el, { expression }, { evaluateLater, cleanup }) {
            const getContent = evaluateLater(expression);

            import("apexcharts").then(({ default: ApexCharts }) => {
                getContent((userConfig) => {
                    if (!userConfig || typeof userConfig !== "object") return;

                    // 1. Fetch live computed CSS variables directly from :root (document.documentElement)
                    const getBootstrapColors = () => {
                        const style = getComputedStyle(document.body);

                        // CoreUI v4/v5 & Bootstrap 5 theme variable names
                        const bodyColor =
                            style.getPropertyValue("--bs-body-color").trim() ||
                            "#a6b0cf";

                        const secondaryColor =
                            style
                                .getPropertyValue("--bs-secondary-color")
                                .trim() ||
                            "#8a93a2";

                        const borderColor =
                            style
                                .getPropertyValue(
                                    "--bs-border-color-translucent",
                                )
                                .trim() ||
                            style
                                .getPropertyValue("--bs-border-color")
                                .trim() ||
                            "rgba(255, 255, 255, 0.08)";

                        const primaryColor =
                            style.getPropertyValue("--bs-primary").trim() ||
                            "#6366f1";

                        const theme =
                            document.documentElement.getAttribute(
                                "data-bs-theme",
                            ) ||
                            "dark";

                        return {
                            bodyColor,
                            secondaryColor,
                            borderColor,
                            primaryColor,
                            theme,
                        };
                    };

                    const colors = getBootstrapColors();

                    // 2. Base Configuration explicitly overriding ApexCharts SVG text fills
                    const baseConfig = {
                        chart: {
                            type: "line",
                            height: 360,
                            foreColor: colors.secondaryColor, // Sets default text/legend colors
                            background: "transparent",
                        },
                        colors: [colors.primaryColor],
                        theme: {
                            mode: colors.theme,
                        },
                        tooltip: {
                            theme: colors.theme,
                        },
                        grid: {
                            borderColor: colors.borderColor,
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: colors.secondaryColor,
                                    fontSize: "12px",
                                },
                            },
                            axisBorder: { color: colors.borderColor },
                            axisTicks: { color: colors.borderColor },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: colors.secondaryColor,
                                    fontSize: "12px",
                                },
                            },
                        },
                    };

                    // Deep merge user options with base configuration
                    const options = Object.assign({}, baseConfig, userConfig);
                    options.chart = Object.assign(
                        {},
                        baseConfig.chart,
                        userConfig.chart,
                    );
                    options.theme = Object.assign(
                        {},
                        baseConfig.theme,
                        userConfig.theme,
                    );
                    options.tooltip = Object.assign(
                        {},
                        baseConfig.tooltip,
                        userConfig.tooltip,
                    );

                    // Preserve user primary colors if explicitly provided in PHP, otherwise use CoreUI's
                    if (!userConfig.colors) {
                        options.colors = baseConfig.colors;
                    }

                    requestAnimationFrame(() => {
                        const chart = new ApexCharts(el, options);
                        chart.render();

                        // 3. Theme switch handler
                        const observer = new MutationObserver(() => {
                            const updated = getCoreUIColors();

                            chart.updateOptions(
                                {
                                    chart: {
                                        foreColor: updated.secondaryColor,
                                    },
                                    theme: { mode: updated.theme },
                                    tooltip: { theme: updated.theme },
                                    grid: { borderColor: updated.borderColor },
                                    xaxis: {
                                        labels: {
                                            style: {
                                                colors: updated.secondaryColor,
                                            },
                                        },
                                        axisBorder: {
                                            color: updated.borderColor,
                                        },
                                        axisTicks: {
                                            color: updated.borderColor,
                                        },
                                    },
                                    yaxis: {
                                        labels: {
                                            style: {
                                                colors: updated.secondaryColor,
                                            },
                                        },
                                    },
                                },
                                false,
                                true,
                            );
                        });

                        observer.observe(document.documentElement, {
                            attributes: true,
                            attributeFilter: [
                                "data-coreui-theme",
                                "data-bs-theme",
                                "class",
                            ],
                        });

                        // 4. Dynamic update event listener
                        const name = el.getAttribute("id");
                        const eventHandler = (event) => {
                            const detail = event.detail || event;
                            if (detail.series)
                                chart.updateSeries(detail.series, true);
                            if (detail.options)
                                chart.updateOptions(detail.options, true);
                        };

                        if (name) {
                            window.addEventListener(
                                `chart-${name}-update`,
                                eventHandler,
                            );
                        }

                        cleanup(() => {
                            observer.disconnect();
                            if (name)
                                window.removeEventListener(
                                    `chart-${name}-update`,
                                    eventHandler,
                                );
                            chart.destroy();
                        });
                    });
                });
            });
        },
    );
}
