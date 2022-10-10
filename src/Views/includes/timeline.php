<div id="timeline"></div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        const data = <?= $timelineData ?>;

        // Create the chart
        Highcharts.chart('timeline', {
            chart: {
                backgroundColor: null,
            },

            title: {
                text: null
            },

            subtitle: {
                text: null
            },

            yAxis: {
                title: {
                    text: 'Nombre de thèses'
                }
            },

            xAxis: {
                accessibility: {
                    rangeDescription: 'Année'
                }
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                    pointStart: 1985
                }
            },

            legend: {
                enabled: false
            },

            tooltip: {
                backgroundColor: '#FFFE',
                borderRadius: 1,
                borderWidth: 1,
                borderColor: "#DDD",
                followPointer: true,
                padding: 12,
                formatter: function() {
                    return `<b>${this.point.name}</b><br>Nombre de thèses<br><strong class="colored">${this.point.value}</strong>`;
                },
                style: {
                    fontSize: 14
                }
            },

            mapView: {
                insetOptions: {
                    borderColor: "#FFF"
                }
            },

            series: [{
                data: data,
                name: 'Nombre de thèses',
                borderColor: '#FFF',
                nullColor: "#E0E0E0",
                states: {
                    hover: {
                        color: null,
                        brightness: 0
                    }
                },
                dataLabels: {
                    enabled: false
                }
            }]
        });
    });
</script>