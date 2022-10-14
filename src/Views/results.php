<main>
    <div class="grid">
        <div class="s12">
            <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.</small>
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
                <?= require "src/Views/includes/map.php"; ?>
            </article>
        </div>

        <div class="s7">
            <article class="white no-padding no-elevate">
                <h5 class="padding">Meilleurs résultats</h5>
                <ul>
                    <?php foreach ($moreAccurate as $these) : ?>
                        <li>
                            <a title="<?= htmlspecialchars($these->title) ?>" class="ellipsis" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= $q ?>">
                                <?= $these->title ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </article>
        </div>
    </div>
</main>