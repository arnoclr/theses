<?php if (count($comparisons) > 1 || count($decoders[0]->displayableFilters()) > 0) : ?>
    <?php require "src/Views/includes/compare.php"; ?>
<?php endif; ?>

<main>

    <div class="context">
        <?php if ($resultsNumber > 0) : ?>
            <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.
                <a href="javascript:void(0);" onclick="document.getElementById('createAlert').showModal();">Créer une alerte</a>
            </small>
        <?php endif; ?>

        <hr style="opacity: 0; padding-top: 32px;">

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

        <?php if ($establishmentData) : ?>
            <header class="establishment">
                <div>
                    <h2><?= $establishmentData->{'Libellé'} ?></h2>
                    <small><?= $establishmentData->Commune ?>, <?= $establishmentData->Département ?></small>
                    <a href="<?= $establishmentData->{"Page Wikipédia en français"} ?>"><?= $establishmentData->{"Page Wikipédia en français"} ?></a>

                    <?php if ($establishmentData->{'nom_court'} !== "" || $establishmentData->{'Libellé'} !== "") : ?>
                        <form class="snippet" action="/" method="get" id="estabSearchForm">
                            <input id="estabSearchInput" type="search" placeholder="Chercher des thèses soutenues à <?= $establishmentData->{'Libellé'} ?>">
                            <input id="estabHiddenInput" type="hidden" name="q" value="">
                            <input type="hidden" name="action" value="search">
                            <input type="submit" value="Rechercher">
                        </form>
                        <script>
                            const f = document.getElementById('estabSearchForm');
                            f.addEventListener('submit', (e) => {
                                e.preventDefault();
                                const i = document.getElementById('estabSearchInput');
                                const h = document.getElementById('estabHiddenInput');
                                h.value = i.value + ` a:"<?= $establishmentData->{'nom_court'} !== "" ? $establishmentData->{'nom_court'} : $establishmentData->{'Libellé'} ?>"`;
                                f.submit();
                            });
                        </script>
                    <?php endif; ?>
                </div>

                <img height="200" src="https://dev.virtualearth.net/REST/v1/Imagery/Map/Road/<?= $establishmentData->{"Géolocalisation"} ?>/16?mapSize=1200,200&pp=<?= $establishmentData->{"Géolocalisation"} ?>;66&mapLayer=Basemap,Buildings&key=AiSO_FZNso9JJnrkixZ6T3d142q2DnTBLhQDVuZXeGFAI_gcnTD11M7JwvhevmzA" alt="">
            </header>
        <?php endif; ?>
    </div>

    <?php if ($resultsNumber == 0) : ?>
        <div>
            <h2>Oups</h2>
            <p>Aucun résultat d'a été trouvé pour <?= htmlspecialchars($_GET['q']) ?></p>
            <p><b>Essayez de retirer certains filtres ou d'élargir votre recherche</b></p>
        </div>
    <?php endif; ?>

    <?php if ($resultsNumber > 0) : ?>
        <div class="searchContent">
            <ul class="searchResults">
                <?php foreach ($moreAccurate as $pos => $query) : ?>
                    <?php foreach ($query as $i => $these) : ?>
                        <?php $color = count($moreAccurate) > 1 ? App\Model\Charts::getColorAt($pos) . "15" : "transparent" ?>
                        <li style="background-color: <?= $color ?>; box-shadow: 0 0 0 10px <?= $color ?>">
                            <?php if ($i === 0 && count($comparisons) === 1) : ?>
                                <?php require "src/Views/includes/bigSearchResult.php"; ?>
                            <?php else : ?>
                                <?php require "src/Views/includes/searchResult.php"; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <!-- <a class="showMoreResults" href="#">Afficher plus de résultats</a> -->
                <?php if ($resultsNumber > 50) : ?>
                    <section>
                        <h5>Trop de résultats ?</h5>
                        <p>Localisez les thèses qui ont été soutenues près de chez vous.</p>
                        <button style="margin: 16px 0" onclick="addGeoOnSearch()" class="chip border">Localiser les résultats <i>my_location</i></button>

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

                <h6>Recherches associées</h6>
                <section class="moreSearches">
                    <?php foreach ($subjectsArray as $data) : ?>
                        <?php foreach (array_slice($data, 0, $resultsNumberForComparison) as $subject) : ?>
                            <a href="/?action=search&q=%22<?= htmlspecialchars($subject["name"]) ?>%22"><?= htmlspecialchars($subject["name"]) ?></a>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </section>
            </ul>

            <aside class="graphs">
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

                <article>
                    <h5>Sujets les plus mentionnés</h5>
                    <?php require "src/Views/includes/subjects.php"; ?>
                </article>
            </aside>
        </div>
    <?php endif; ?>
</main>