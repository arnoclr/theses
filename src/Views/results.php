<main>
    <div class="grid">
        <div class="s12">
            <small><?= $resultsNumber ?> résultat<?= $resultsNumber > 1 ? "s" : "" ?> en <?= number_format($time, 3) ?> secondes.</small>
        </div>

        <div class="s12">
            <article class="white no-elevate">
                <h4>Evolution dans le temps</h4>
                <?php require "src/Views/includes/timeline.php"; ?>
            </article>
        </div>

        <div class="s5">
            <article class="white full-height no-elevate">
                <h4>Par région</h4>
                <?= require "src/Views/includes/map.php"; ?>
            </article>
        </div>

        <div class="s7">
            <article class="white no-padding no-elevate">
                <h4 class="padding">Meilleurs résultats</h4>
                <ul>
                    <?php foreach ($moreAccurate as $these) : ?>
                        <li>
                            <a title="<?= $these->title ?>" class="ellipsis" href="/?action=view&tid=<?= $these->iddoc ?>">
                                <?= $these->title ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </article>
        </div>
    </div>
</main>