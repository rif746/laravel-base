import '../plugin/apexchart.js'

export default function AlpineChart(Alpine) {
    Alpine.directive('chart',  function (el, { expression }, { evaluate, cleanup }) {
        let config = evaluate(expression);

        const chart = new window.ApexCharts(el, config);
        chart.render();

        const event = function(event) {
            const newData = event.detail
            chart.updateSeries(newData.series, true, true);
            chart.updateOptions(newData.options, true, true);
        }

        name = el.getAttribute('id');

        window.addEventListener(`chart-${name}-update`, event)

        cleanup(() => {
            chart.destroy();
            window.removeEventListener(`chart-${name}-update`, event)
        });
    })
}
