<header class="primary-container header-container center-align">
    <h2 class="header-title">Cherchez parmi l'ensemble des <b class="bold">thèses de doctorat</b> soutenues en France depuis 1985</h2>
    <form method="get" action="/" class="field suffix extra plain js-main-search out">
        <input type="hidden" name="action" value="search">
        <input name="q" type="search" placeholder="Saisir un terme de recherche ou un thème">
        <i>search</i>
    </form>
</header>

<div class="demo-card">
    <p class="large-text">Exemples de recherches</p>
</div>

<div id="container"></div>

<script>
    window.onload = (async () => {

        const topology = await fetch(
            'https://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json'
        ).then(response => response.json());

        // Prepare demo data. The data is joined to map using value of 'hc-key'
        // property by default. See API docs for 'joinBy' for more info on linking
        // data and map.
        const data = <?= $regionalArray ?>;

        // Create the chart
        Highcharts.mapChart('container', {
            chart: {
                map: topology
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
    })();
</script>

<div style="height: 1000px;"></div>

<script>
    // searchbar elements
    const navSearch = document.querySelector('.js-nav-search');
    const mainSearch = document.querySelector('.js-main-search');
    const navbar = document.querySelector('.js-navbar');

    // hide nav searchbar
    navSearch.classList.add('out');
    mainSearch.classList.remove('out');
    navbar.classList.remove('small-elevate');

    document.addEventListener('scroll', async () => {
        if (window.scrollY > 352) {
            navSearch.classList.remove('out');
            mainSearch.classList.add('out');
        } else {
            navSearch.classList.add('out');
            mainSearch.classList.remove('out');
        }

        if (window.scrollY > 451) {
            navbar.classList.add('small-elevate');
        } else {
            navbar.classList.remove('small-elevate');
        }
    });
</script>