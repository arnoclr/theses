<main>
    <?php if ($resultsNumber == 0) : ?>
        <div>
            <h2>Oups</h2>
            <p>Aucun résultat d'a été trouvé pour <?= htmlspecialchars($_GET['q']) ?></p>
            <p><b>Essayez de retirer certains filtres ou d'élargir votre recherche</b></p>
        </div>
    <?php endif; ?>
    <div class="grid">
        <div class="s12">
            <?php if ($resultsNumber > 0) : ?>
                <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.</small>
            <?php endif; ?>

            <nav class="scroll">
                <?php if ($by) : ?>
                    <a class="chip fill" href="/?action=search&q=<?= $queryWithoutAuthor ?>">
                        <span><?= $by ?></span>
                        <i class="small">close</i>
                    </a>
                <?php elseif ($isPerson) : ?>
                    <a class="chip border" href="/?action=search&q=par+<?= $person->firstname ?>+<?= $person->lastname ?>">par <?= $person->firstname ?> <?= $person->lastname ?></a>
                <?php endif; ?>
                <?php if ($at) : ?>
                    <a class="chip fill" href="/?action=search&q=<?= $queryWithoutEstablishment ?>">
                        <span><?= $at ?></span>
                        <i class="small">close</i>
                    </a>
                <?php endif; ?>
                <?php if ($dateString) : ?>
                    <a class="chip fill" href="/?action=search&q=<?= $queryWithoutDate ?>">
                        <span><?= $dateString ?></span>
                        <i class="small">close</i>
                    </a>
                <?php else : ?>
                    <a class="chip border" href="/?action=search&q=<?= htmlspecialchars($q) ?>+après+<?= date('Y') - 5 ?>">Ces 5 dernières années</a>
                <?php endif; ?>
                <?php
                $filterTerm = "les plus récentes";
                $exclude = "les plus anciennes";
                include "src/Views/includes/resultChip.php"; ?>
                <?php
                $filterTerm = "les plus anciennes";
                $exclude = "les plus récentes";
                include "src/Views/includes/resultChip.php"; ?>
                <?php
                $filterTerm = "en ligne";
                $exclude = false;
                include "src/Views/includes/resultChip.php"; ?>
            </nav>
        </div>

        <?php if ($resultsNumber > 0) : ?>
            <div class="s12 l7">
                <ul class="searchResults">
                    <?php foreach ($moreAccurate as $pos => $these) : ?>
                        <li>
                            <?php if ($pos === 0 && \App\Model\These::isCloseMatch($these, $q)) : ?>
                                <?php require "src/Views/includes/bigSearchResult.php"; ?>
                            <?php else : ?>
                                <!-- 
                        Gros résultat si on a un match exact de la recherche dans le résumé de la these
                        Affiche une carte si on recherche un établissement.
                        Si on cherche un auteur, afficher un résumé wikipedia si c dispo OU ALORS afficher un encadré spécial avec les thèses qu'il a ecrit 
                        -->
                                <?php require "src/Views/includes/searchResult.php"; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="s12 l5">
                <article class="white no-elevate">
                    <h5>Evolution dans le temps</h5>
                    <?php require "src/Views/includes/timeline.php"; ?>
                </article>

                <article class="white no-elevate">
                    <h5>Par région</h5>
                    <?php require "src/Views/includes/map.php"; ?>
                </article>

                <article class="white no-elevate">
                    <h5>Sujets les plus mentionnés</h5>
                    <?php require "src/Views/includes/subjects.php"; ?>
                </article>
            </div>
        <?php endif; ?>
    </div>
</main>