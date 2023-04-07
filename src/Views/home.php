<header class="secondary-container header-container center-align">
    <h2 class="header-title">Cherchez parmi l'ensemble des <b class="bold">thèses de doctorat</b> soutenues en France depuis 1985</h2>
    <form method="get" action="/" class="field suffix extra plain js-main-search">
        <input type="hidden" name="action" value="search">
        <input name="q" type="search" spellcheck="false" placeholder="Saisir un terme de recherche ou un thème">
        <i>search</i>
    </form>
    <script>
        window.overrideTimelineSettings = {
            chart: {
                type: 'area',
                style: {
                    filter: 'alpha(opacity=1)',
                    opacity: 1,
                    background: 'transparent'
                },
                animation: {
                    enabled: true,
                    duration: 3000,
                    easing: 'ease'
                },
            },

            yAxis: {
                gridLineWidth: 0,
                title: {
                    enabled: false
                },
                labels: {
                    enabled: false
                }
            },

            xAxis: {
                title: {
                    enabled: false
                },
                labels: {
                    enabled: false
                }
            },

            plotOptions: {
                line: {
                    lineWidth: 10,
                    fillColor: 'transparent',
                },
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
        }
    </script>
    <?php require "src/Views/includes/timeline.php"; ?>
</header>

<div class="demo-card">
    <div class="demo-card-inner">
        <p class="example-text">Exemples de recherches</p>

        <nav class="scroll" style="flex-flow: row wrap; margin-bottom: 32px">
            <a class="subjectCard" href="/?action=search&q=Paris">
                <p>Thèses soutenues à Paris</p>
                <img height="120" src="/public/img/examples/map.jpg?v=2" alt="Map Paris">
            </a>
            <a class="subjectCard" href="/?action=search&q=bourse+avant%3A2008">
                <p>Sur la bourse, avant 2008</p>
                <img height="120" src="/public/img/examples/bourse.jfif" alt="Bourse">
            </a>
            <a class="subjectCard" href="/?action=search&q=Capitalisme,Communisme">
                <p>Comparer le nombre de thèses portant sur le capitalisme et le communisme</p>
                <img height="120" src="/public/img/examples/capcom.jpg" alt="Timeline de comparaison">
            </a>
            <a class="subjectCard" href="/?action=search&q=développement+de+la+tomate">
                <p>Photos du développement de la tomate</p>
                <img height="120" src="/public/img/examples/devtom.jfif" alt="Pousse de la tomate">
            </a>
            <a class="subjectCard" href="/?action=search&q=vaccin+tri%3Arecent">
                <p>Thèses les plus récentes concernant les vaccins</p>
                <img height="120" src="/public/img/examples/vaccin.jpg" alt="Vaccin">
            </a>
        </nav>

        <p style="margin-bottom: 22px;">Stats globales</p>
        <ul class="stats">
            <li>
                <span class="numeric"><?= $thesesCount ?></span>
                <span>thèses repertoriées</span>
            </li>
            <li>
                <span class="numeric"><?= $peopleCount ?></span>
                <span>personnes concernées</span>
            </li>
            <li>
                <span class="numeric"><?= $etabsCount ?></span>
                <span>etablissements</span>
            </li>
        </ul>
    </div>
</div>

<style>
    header.fixed {
        display: none;
    }

    header {
        overflow-y: hidden;
    }

    header #timeline {
        position: absolute;
        bottom: -32px;
        left: 0;
        right: 0;
        top: 0;
        z-index: 1;
        opacity: 0.4;
    }

    header form,
    header h2 {
        z-index: 2;
    }

    .field.plain {
        background-color: #f6fbff;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px 2px rgba(0, 0, 0, 0.05);
    }
</style>

<script>
    const numerics = document.querySelectorAll('.numeric');

    numerics.forEach(numeric => {
        const value = numeric.innerText;
        const formatter = new Intl.NumberFormat('en', {
            notation: 'compact'
        });
        numeric.innerText = formatter.format(value);
    });
</script>