<div id="map"></div>

<script>
    (function() {
        const init = async () => {

            const topology = await fetch(
                'https://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json'
            ).then(response => response.json());

            const regionalArray = <?= json_encode($regionalArray) ?>;

            const highestRegionsValues = {};
            let colorAxis;
            let data;
            let tooltip;

            if (regionalArray.length > 1) {
                regionalArray.forEach((data, i) => {
                    data.forEach(region => {
                        const [code, value] = region;
                        if (highestRegionsValues[code] === undefined || value > highestRegionsValues[code].value) {
                            highestRegionsValues[code] = {
                                value,
                                serie: i
                            };
                        }
                    })
                })

                colorAxis = {
                    dataClasses: [
                        <?php foreach (\App\Model\Charts::seriesColors() as $i => $color) : ?> {
                                from: <?= $i ?>,
                                to: <?= $i + 1 ?>,
                                color: "<?= $color ?>",
                            },
                        <?php endforeach; ?>
                    ]
                }

                tooltip = {
                    enabled: false,
                }

                data = Object.entries(highestRegionsValues).map(([code, {
                    value,
                    serie
                }]) => [code, serie + 0.5]);
            } else {
                colorAxis = {
                    min: 0,
                    minColor: "#E0E0E0",
                    maxColor: "#0277bd"
                }

                data = regionalArray[0];
            }

            Highcharts.mapChart('map', {
                credits: {
                    enabled: false
                },

                chart: {
                    map: topology,
                    backgroundColor: null,
                },

                title: {
                    text: null
                },

                subtitle: {
                    text: null
                },

                mapNavigation: {
                    enabled: false,
                },

                colorAxis: colorAxis,

                legend: {
                    enabled: false
                },

                tooltip: {
                    ...tooltip,
                    backgroundColor: '#FFFE',
                    borderRadius: 1,
                    borderWidth: 1,
                    borderColor: "#DDD",
                    followPointer: true,
                    padding: 12,
                    formatter: function() {
                        return `<b>${this.series.name}</b><br>${this.point.name}<br><strong class="colored">${this.point.value}</strong>`;
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
                    name: "Nombre de thèses",
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