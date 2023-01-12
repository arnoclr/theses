<header class="primary-container header-container center-align">
    <h2 class="header-title">Cherchez parmi l'ensemble des <b class="bold">thèses de doctorat</b> soutenues en France depuis 1985</h2>
    <form method="get" action="/" class="field suffix extra plain js-main-search out">
        <input type="hidden" name="action" value="search">
        <input name="q" type="search" placeholder="Saisir un terme de recherche ou un thème">
        <i>search</i>
    </form>
</header>

<div class="demo-card">
    <div class="demo-card-inner">
        <p class="example-text">Exemples de recherches</p>
        <div class="examples">
            <a href="/?action=search&q=Marché+de+Rungis">
                <article class="no-elevate white no-padding">
                    <img width="300" src="/public/img/examples/map.jpg" alt="Carte des régions de france">
                    <div class="padding">
                        <p>Carte des régions pour Marché de Rungis</p>
                    </div>
                </article>
            </a>
            <a href="/?action=search&q=Trafic+aérien+entre+2000+et+2008">
                <article class="no-elevate white no-padding">
                    <img width="300" src="/public/img/examples/timeline.jpg" alt="évolution au cours du temps">
                    <div class="padding">
                        <p>Trafic aérien entre 2000 et 2008</p>
                    </div>
                </article>
            </a>
            <a href="/?action=search&q=par+Fabien+Girard">
                <article class="no-elevate white no-padding">
                    <img width="300" src="/public/img/examples/author.jpg" alt="trier par auteur">
                    <div class="padding">
                        <p>Thèses soutenues par Fabien Girard</p>
                    </div>
                </article>
            </a>
        </div>
        <br><br>
        <a href="/?action=search&q=<?= $randomTitle ?>" class="button">J'ai de la chance</a>

        <h4 style="margin-top: 42px; margin-bottom: 32px;">Stats globales</h4>

        <ul class="stats">
            <li>
                <span class="numeric"><?= $thesesCount ?></span>
                <span>thèses repertoriées</span>
            </li>
            <li>
                <span class="numeric"><?= $peopleCount ?></span>
                <span>personnes concernées</span>
            </li>
        </ul>

        <article class="white no-elevate">
            <h5>Evolution dans le temps</h5>
            <?php require "src/Views/includes/timeline.php"; ?>
        </article>
        <article class="white full-height no-elevate">
            <h5>Par région</h5>
            <?php require "src/Views/includes/map.php"; ?>
        </article>

        <a style="margin: 32px 0;" href="https://www.data.gouv.fr/fr/datasets/theses-soutenues-en-france-depuis-1985/">Données open source issues de theses.fr, datagouv.</a>
    </div>
</div>

<script>
    // searchbar elements
    const navSearch = document.querySelector('.js-nav-search');
    const mainSearch = document.querySelector('.js-main-search');
    const navbar = document.querySelector('.js-navbar');
    const numerics = document.querySelectorAll('.numeric');

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

    numerics.forEach(numeric => {
        const value = numeric.innerText;
        const formatter = new Intl.NumberFormat('en', {
            notation: 'compact'
        });
        numeric.innerText = formatter.format(value);
    });
</script>