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