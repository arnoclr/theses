<div id="subjects"></div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        Highcharts.chart('subjects', {
            chart: {
                type: 'treemap',
                backgroundColor: null,
                height: 240,
            },

            title: {
                text: "",
            },

            series: [{
                type: 'treemap',
                layoutAlgorithm: 'squarified',
                data: <?= $subjectsArray ?>,
                dataLabels: {
                    style: {
                        fontFamily: 'Roboto',
                        fontSize: '16px',
                        color: '#fff',
                        textOutline: 0,
                        fontWeigth: 400,
                    }
                }
            }],

            colorAxis: {
                min: 0,
                minColor: "#E0E0E0",
                maxColor: "#0277bd",
                showInLegend: false,
            },

            legend: {
                enabled: false
            },

            tooltip: {
                enabled: false
            },
        });
    });
</script>