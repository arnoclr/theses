<?php if (count($comparisons) > 1) : ?>
    <?php require "src/Views/includes/compare.php"; ?>
<?php endif; ?>

<main>

    <div class="context">
        <?php if ($resultsNumber > 0) : ?>
            <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.</small>
        <?php endif; ?>

        <nav class="scroll">

        </nav>

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
                            <?php if ($i === 0 && \App\Model\These::isCloseMatch($these, $q)) : ?>
                                <?php require "src/Views/includes/bigSearchResult.php"; ?>
                            <?php else : ?>
                                <?php require "src/Views/includes/searchResult.php"; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <!-- <a class="showMoreResults" href="#">Afficher plus de résultats</a> -->
            </ul>

            <aside class="graphs">
                <article>
                    <h5>Evolution dans le temps</h5>
                    <?php require "src/Views/includes/timeline.php"; ?>

                    <?php if (count($comparisons) === 1) : ?>
                        <p>Comparer avec</p>
                        <nav class="scroll">
                            <?php foreach ($subjectsArray[0] as $subject) : ?>
                                <a href="/?action=search&q=<?= urlencode($q) ?>,<?= urlencode($subject["name"]) ?>" class="chip border"><?= htmlspecialchars($subject["name"]) ?></a>
                            <?php endforeach; ?>
                        </nav>
                    <?php endif; ?>
                </article>

                <article>
                    <h5>Par région</h5>
                    <?php require "src/Views/includes/map.php"; ?>
                </article>

                <article>
                    <h5>Sujets les plus mentionnés</h5>
                    <?php require "src/Views/includes/subjects.php"; ?>
                </article>
            </aside>
        </div>
    <?php endif; ?>
</main>