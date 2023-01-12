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
            </nav>
        </div>

        <?php if ($resultsNumber > 0) : ?>
            <div class="s12">
                <article class="white no-elevate">
                    <h5>Evolution dans le temps</h5>
                    <?php require "src/Views/includes/timeline.php"; ?>
                </article>
            </div>

            <div class="s12 l5">
                <article class="white full-height no-elevate">
                    <h5>Par région</h5>
                    <?php require "src/Views/includes/map.php"; ?>
                </article>
            </div>

            <div class="s12 l7">
                <article class="white no-padding no-elevate">
                    <h5 class="padding">Meilleurs résultats</h5>
                    <ul>
                        <?php foreach ($moreAccurate as $these) : ?>
                            <li>
                                <a title="<?= htmlspecialchars($these->title) ?>" class="ellipsis" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= htmlspecialchars($q) ?>">
                                    <span style="opacity: 0.6; margin-right: 12px;"><?= $these->date_year ?></span>
                                    <?= $these->title ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            </div>

            <div class="s12">
                <article class="white no-elevate">
                    <h5>Sujets les plus mentionnés</h5>
                    <?php require "src/Views/includes/subjects.php"; ?>
                </article>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php if (!$headless) : ?>
    <script src="/public/js/includes/results.js"></script>
<?php endif; ?>