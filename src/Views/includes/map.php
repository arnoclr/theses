<div id="map"></div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {

        const topology = await fetch(
            'https://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json'
        ).then(response => response.json());

        // Prepare demo data. The data is joined to map using value of 'hc-key'
        // property by default. See API docs for 'joinBy' for more info on linking
        // data and map.
        const data = <?= $regionalArray ?>;

        // Create the chart
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

            colorAxis: {
                min: 0,
                minColor: "#E0E0E0",
                maxColor: "#0277bd"
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