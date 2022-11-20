<div id="timeline"></div>

<script>
    (function() {
        const init = async () => {
            const data = <?= json_encode($timelineData) ?>;

            // Create the chart
            Highcharts.chart('timeline', {
                credits: {
                    enabled: false
                },

                chart: {
                    backgroundColor: null,
                    height: 240,
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
                    },
                    labels: {
                        enabled: false
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
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        marker: {
                            enabled: false
                        },
                        pointStart: 1985
                    }
                },

                legend: {
                    enabled: false
                },

                tooltip: {
                    enabled: false
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
        };

        try {
            Highcharts;
            init()
        } catch (e) {
            document.addEventListener('DOMContentLoaded', init);
        }
    })();
</script>