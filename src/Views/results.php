<main>
    <div class="grid">
        <div class="s12">
            <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.</small>

            <nav class="scroll">
                <?php if ($by) : ?>
                    <a class="chip fill" href="/?action=search&q=<?= $queryWithoutAuthor ?>">
                        <span><?= $by ?></span>
                        <i class="small">close</i>
                    </a>
                <?php elseif ($isPerson) : ?>
                    <a class="chip border" href="/?action=search&q=par+<?= $person->firstname ?>+<?= $person->lastname ?>">par <?= $person->firstname ?> <?= $person->lastname ?></a>
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

        <div class="s12">
            <article class="white no-elevate">
                <h5>Evolution dans le temps</h5>
                <?php require "src/Views/includes/timeline.php"; ?>
            </article>
        </div>

        <div class="s5">
            <article class="white full-height no-elevate">
                <h5>Par région</h5>
                <?php require "src/Views/includes/map.php"; ?>
            </article>
        </div>

        <div class="s7">
            <article class="white no-padding no-elevate">
                <h5 class="padding">Meilleurs résultats</h5>
                <ul>
                    <?php foreach ($moreAccurate as $these) : ?>
                        <li>
                            <a title="<?= htmlspecialchars($these->title) ?>" class="ellipsis" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= htmlspecialchars($q) ?>">
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
    </div>
</main>