<div id="subjects"></div>

<script>
    (function() {
        const init = async () => {
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

                series: [
                    <?php foreach ($subjectsArray as $data) : ?> {
                            type: 'treemap',
                            layoutAlgorithm: 'squarified',
                            data: <?= json_encode($data) ?>,
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
                                        window.location.href = `/?action=search&q="${event.point.name}"+<?= str_replace('`', '', $_GET['q']) ?>`;
                                    }
                                },
                            },
                        },
                    <?php endforeach; ?>
                ],

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
        };

        try {
            Highcharts;
            init()
        } catch (e) {
            document.addEventListener('DOMContentLoaded', init);
        }
    })();
</script>