<div id="timeline"></div>

<script>
    (function() {
        const init = async () => {
            Highcharts.chart('timeline', {
                credits: {
                    enabled: false
                },

                chart: {
                    backgroundColor: null,
                    minHeight: 240,
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

                colors: <?= App\Model\Charts::highchartsSeriesColors() ?>,

                series: [
                    <?php foreach ($timelineData as $data) : ?> {
                            data: <?= json_encode($data) ?>,
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
                        },
                    <?php endforeach; ?>
                ]
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