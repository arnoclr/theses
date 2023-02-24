<article class="searchResult">
    <a title="<?= htmlspecialchars($these->title) ?>" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= htmlspecialchars($q) ?>">
        <header>
            <small>https://theses.fr/<?= $these->nnt ?></small>
            <?php if ($these->online) : ?>
                <kbd>PDF</kbd>
            <?php endif; ?>
        </header>
        <h3><?= $these->title ?></h3>
    </a>
    <p>
        <span class="date">
            <?= $these->date_year ?>
            <?= $these->lang === "en" ? "(Traduit de l'anglais)" : "" ?> —
        </span>
        <span class="summary">
            <?= mb_substr($these->summary, 0, 160, "UTF-8") ?>...
        </span>
    </p>
    <ul class="subjects">
        <?php foreach (\App\Model\These::getCommonSubjects($these, $subjectsCount) as $subject) : ?>
            <a href="/?action=search&q=%22<?= $subject ?>%22">
                <li>
                    <span><?= $subject ?></span>
                </li>
            </a>
        <?php endforeach; ?>
    </ul>
</article>