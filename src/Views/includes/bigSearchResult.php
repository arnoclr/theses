<article class="searchResult big">
    <p class="text">
        <span class="date"><?= $these->date_year ?> —</span>
        <span class="summary"><?= App\Model\These::highlightSummaryWith($these->summary, $q) ?>...</span>
    </p>
    <a title="<?= htmlspecialchars($these->title) ?>" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= htmlspecialchars($q) ?>">
        <header>
            <small>https://theses.fr/<?= $these->nnt ?></small>
            <?php if ($these->online) : ?>
                <kbd>PDF</kbd>
            <?php endif; ?>
        </header>
        <h3><?= $these->title ?></h3>
    </a>
    <nav>
        <p>Sujets mentionnés</p>
        <ul class="subjects">
            <?php foreach (\App\Model\These::getCommonSubjects($these, $subjectsCount) as $subject) : ?>
                <a href="/?action=search&q=%22<?= $subject ?>%22">
                    <li>
                        <span><?= $subject ?></span>
                    </li>
                </a>
            <?php endforeach; ?>
        </ul>
    </nav>
</article>