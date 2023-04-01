<article class="searchResult big">
    <p class="text">
        <span class="date"><?= $these->date_year ?> —</span>
        <span class="summary"><?= \App\Model\These::highlightSummaryWith($these->summary, $q) ?>...</span>
    </p>
    <?php require "src/Views/includes/imagesCarousel.php"; ?>
    <a title="<?= htmlspecialchars($these->title) ?>" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= htmlspecialchars($q) ?>">
        <header>
            <small><?= \App\Model\These::getEstabShortName($these) ?></small>
            <?php if ($these->online) : ?>
                <kbd>PDF</kbd>
            <?php endif; ?>
        </header>
        <h3 class="ellipsis"><?= $these->title ?></h3>
    </a>
    <nav>
        <?php $subjects = \App\Model\These::getCommonSubjects($these, $subjectsCount); ?>
        <?php if (count($subjects) > 0) : ?>
            <p>Sujets mentionnés</p>
        <?php endif; ?>
        <ul class="subjects">
            <?php foreach ($subjects as $subject) : ?>
                <a href="/?action=search&q=%22<?= $subject ?>%22">
                    <li>
                        <span><?= $subject ?></span>
                    </li>
                </a>
            <?php endforeach; ?>
        </ul>
    </nav>
</article>