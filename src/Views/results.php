<main>
    <div class="grid">
        <div class="s5">
            <article class="white full-height no-elevate">
                <h4>Par région</h4>
                <?= require "src/Views/includes/map.php" ?>
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