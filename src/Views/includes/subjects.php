<div id="subjects"></div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        Highcharts.chart('subjects', {
            credits: {
                enabled: false
            },

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
                },
                point: {
                    events: {
                        click: function(event) {
                            // TODO: fix XSS 
                            // `.substr(0, 0) + `maria` .substr(0) + `https://www.youtube.com/watch?v=dQw4w9WgXcQ
                            // window.location.href = `/?action=search&q="${event.point.name}"+<?= htmlspecialchars($_GET['q']) ?>`;
                        }
                    },
                },
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