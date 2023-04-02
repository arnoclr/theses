<?php if (count($comparisons) > 1 || count($decoders[0]->displayableFilters()) > 0) : ?>
    <?php require "src/Views/includes/compare.php"; ?>
<?php endif; ?>

<main>

    <div class="context">
        <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.
            <a href="javascript:void(0);" onclick="document.getElementById('createAlert').showModal();">Créer une alerte</a>
            <a href="javascript:void(0);" onclick="document.getElementById('advancedSearch').showModal();">Recherche avancée</a>
        </small>

        <hr style="opacity: 0; padding-top: 32px;">

        <dialog id="advancedSearch">
            <h5>Recherche avancée</h5>

            <form class="advancedSearch">
                <label>
                    <span><b>tous</b> les mots suivants</span>
                    <input spellcheck="false" name="all" type="text">
                </label>
                <label>
                    <span>Cette <b>expression exacte</b></span>
                    <input spellcheck="false" name="exact" type="text">
                </label>
                <label>
                    <span><b>au moins un</b> des mots suivants</span>
                    <input spellcheck="false" name="include" type="text" value="<?= htmlspecialchars($q) ?>">
                </label>
                <label>
                    <span>Rechercher des thèses <b>rédigées par</b></span>
                    <input spellcheck="false" name="by" type="text">
                </label>
                <small>ex: Patrick Flajolet</small>
                <label>
                    <span>Rechercher des thèses <b>datées</b> de</span>
                    <input min="1980" max="<?= date("Y"); ?>" name="before" spellcheck="false" type="number">
                    <span>&nbsp;—&nbsp;</span>
                    <input min="1980" max="<?= date("Y"); ?>" name="after" spellcheck="false" type="number">
                </label>
                <small>ex: 1999</small>
                <label>
                    <span>Rechercher les thèses soutenues <b>près de</b></span>
                    <input spellcheck="false" type="text" name="near">
                </label>
                <small>ex: Paris</small>

                <nav class="right-align small-space" style="gap: 6px">
                    <button type="button" class="border" onclick="document.getElementById('advancedSearch').close();">Annuler</button>
                    <button>Rechercher</button>
                </nav>
            </form>

            <script>
                const advancedSearchForm = document.querySelector(".advancedSearch");

                advancedSearchForm.addEventListener("submit", (e) => {
                    e.preventDefault();

                    let query = "";
                    const formData = new FormData(advancedSearchForm);

                    if (formData.get("all") !== "") {
                        const words = formData.get("all").split(" ");
                        words.forEach(word => {
                            query += `+"${word}"`;
                        });
                    }

                    if (formData.get('exact') !== "") {
                        query += `+"${formData.get('exact')}"`;
                    }

                    if (formData.get('include') !== "") {
                        query += `+${formData.get('include')}`;
                    }

                    if (formData.get('by') !== "") {
                        query += `+par:"${formData.get('by')}"`;
                    }

                    if (formData.get('before') !== "") {
                        query += `+avant:${formData.get('before')}`;
                    }

                    if (formData.get('after') !== "") {
                        query += `+apres:${formData.get('after')}`;
                    }

                    if (formData.get('near') !== "") {
                        query += `+vers:"${formData.get('near')}"`;
                    }

                    window.location.href = `/?action=search&q=${query}`;
                });
            </script>
        </dialog>

        <dialog id="createAlert">
            <h5>Créer une alerte</h5>
            <p>Activez une alerte pour être prévenu de l'existence de nouveaux résultats pour une recherche donnée.</p>

            <form method="post" action="/?action=submitAlert" style="display: flex; flex-direction: column; gap: 22px; margin-top: 42px;">
                <div class="field label fill large" style="margin: 0">
                    <input spellcheck="false" name="q" type="text" value="<?= htmlspecialchars($q) ?>" required>
                    <label>Recherche</label>
                </div>

                <div class=" field label fill large" style="margin: 0">
                    <input name="email" type="email" autofocus autocomplete="email" required>
                    <label>Adresse email</label>
                </div>

                <div class="captcheck_container"></div>

                <small>Vous recevrez un mail de confirmation pour activer votre alerte.</small>


                <nav class="right-align small-space" style="gap: 6px">
                    <button type="button" class="border" onclick="document.getElementById('createAlert').close();">Annuler</button>
                    <button>Activer l'alerte</button>
                </nav>
            </form>
        </dialog>
    </div>

    <?php if ($resultsNumber > 0 || count($decoders[0]->getEstablishments()) > 0) : ?>
        <div class="searchContent">
            <div class="searchCol">
                <?php if ($decoders[0]->getMapBoundaries() !== null) : ?>
                    <?php require "src/Views/includes/streetmap.php"; ?>
                <?php endif; ?>

                <ul class="searchResults">
                    <?php foreach ($moreAccurate as $pos => $query) : ?>
                        <?php foreach ($query as $i => $these) : ?>
                            <?php $color = count($moreAccurate) > 1 ? App\Model\Charts::getColorAt($pos) . "15" : "transparent" ?>

                            <?php if ($decoders[$pos]->authorName() !== null) : ?>
                                <article class="searchResult big">
                                    <p class="text">Thèses de <mark><?= $decoders[$pos]->authorName() ?></mark></p>
                                </article>
                            <?php endif; ?>

                            <?php require "src/Views/includes/coloredListItem.php"; ?>
                            <?php if ($i === 0 && count($comparisons) === 1 && \App\Model\These::canBeDisplayedHasBigResult($these->summary, $q)) : ?>
                                <?php require "src/Views/includes/bigSearchResult.php"; ?>
                            <?php else : ?>
                                <?php require "src/Views/includes/searchResult.php"; ?>
                            <?php endif; ?>

                            <?php if ($i == 2) : ?>
                                <?php require_once "src/Views/includes/wikipediaSubjects.php"; ?>
                            <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <?php require_once "src/Views/includes/wikipediaSubjects.php"; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($resultsNumber > 50 && $decoders[0]->isLocalizedQuery() === false) : ?>
                    <section class="locateResults">
                        <h6>Trop de résultats ?</h6>
                        <p>Localisez les thèses qui ont été soutenues près de chez vous.</p>
                        <a style="margin: 16px 0" onclick="addGeoOnSearch()">Localiser les résultats</a>

                        <script>
                            function addGeoOnSearch() {
                                // request permission to access user location
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        const lat = position.coords.latitude;
                                        const lon = position.coords.longitude;
                                        const q = "<?= $q ?>"
                                        window.location.href = `/?action=search&q=${q} lat:${lat} lon:${lon}`;
                                    });
                                } else {
                                    alert("Votre navigateur ne supporte pas la géolocalisation");
                                }
                            }
                        </script>
                    </section>
                <?php endif; ?>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <h6>Recherches associées</h6>
                    <section class="moreSearches">
                        <?php foreach ($subjectsArray as $data) : ?>
                            <?php foreach (array_slice($data, 0, $resultsNumberForComparison) as $subject) : ?>
                                <a href="/?action=search&q=%22<?= htmlspecialchars($subject["name"]) ?>%22"><?= htmlspecialchars($subject["name"]) ?></a>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </section>
                </div>

                <ul class="searchResults appendedSearchResults" style="display: none;">
                </ul>

                <?php if ($showMoreResults === true) : ?>
                    <div class="showMoreResults">
                        <a href="javascript:void(0);" onclick="loadMoreResults();">Afficher plus de résultats</a>
                    </div>

                    <script>
                        const showMoreResults = document.querySelector(".showMoreResults a");
                        const searchResults = document.querySelector(".appendedSearchResults");
                        let p = 2;

                        function loadMoreResults() {
                            showMoreResults.innerText = "Chargement...";

                            fetch("/?action=loadMoreResults&headless=1&q=<?= urlencode($_GET['q']) ?>&p=" + p)
                                .then(response => response.text())
                                .then(html => {
                                    showMoreResults.innerText = "Afficher plus de résultats";
                                    searchResults.innerHTML += html;
                                    searchResults.style.display = "";
                                    p++;
                                });
                        }
                    </script>
                <?php endif; ?>
            </div>

            <aside class="graphs <?= $wikipediaData ? 'withArrow' : '' ?>">
                <?php if ($wikipediaData !== null) : ?>
                    <article>
                        <?php require "src/Views/includes/wikipedia.php"; ?>
                    </article>
                <?php endif; ?>

                <?php if ($establishmentData) : ?>
                    <article>
                        <?php require "src/Views/includes/establishment.php"; ?>
                    </article>
                <?php endif; ?>

                <?php if ($resultsNumber > 0) : ?>
                    <article>
                        <h5>Evolution dans le temps</h5>
                        <?php require "src/Views/includes/timeline.php"; ?>

                        <?php if (count($comparisons) === 1) : ?>
                            <p>Comparer avec</p>
                            <nav class="scroll">
                                <?php foreach ($subjectsArray[0] as $subject) : ?>
                                    <a href="/?action=search&q=<?= urlencode($q) ?>, %22<?= urlencode($subject["name"]) ?>%22" class="chip border"><?= htmlspecialchars($subject["name"]) ?></a>
                                <?php endforeach; ?>
                            </nav>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>

                <?php if (count($regionalArray[0]) > 0 || count($comparisons) > 1) : ?>
                    <article>
                        <h5 id="regions">Par région</h5>
                        <?php require "src/Views/includes/map.php"; ?>
                        <?php if (count($comparisons) > 1 || isset($_GET['_q'])) : ?>
                            <p>Plus de détails pour</p>
                            <nav class="scroll">
                                <?php $terms = isset($_GET['_q']) ? explode(',', $_GET['_q']) : $comparisons ?>
                                <?php foreach ($terms as $term) : $term = trim($term); ?>
                                    <a href="/?action=search&q=<?= urlencode($term) ?>&_q=<?= urlencode(isset($_GET['_q']) ? $_GET['_q'] : $_GET['q']) ?>#regions" class="chip 
                                <?= count($comparisons) === 1 && \App\Model\These::containExactMatch($_GET['q'], $term) ? 'fill' : 'border' ?>
                                "><?= htmlspecialchars($term) ?></a>
                                <?php endforeach; ?>
                                <?php if (count($comparisons) === 1) : ?>
                                    <a href="/?action=search&q=<?= urlencode($_GET['_q']) ?>#regions" class="chip border">Tout</a>
                                <?php endif; ?>
                            </nav>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>
            </aside>
        </div>
    <?php else : ?>
        <div>
            <h2>Oups</h2>
            <p>Aucun résultat d'a été trouvé pour <?= htmlspecialchars($_GET['q']) ?></p>
            <p><b>Essayez de retirer certains filtres ou d'élargir votre recherche</b></p>
        </div>
    <?php endif; ?>
</main>