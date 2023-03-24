<article class="searchResult">
    <a title="<?= htmlspecialchars($these->title) ?>" href="/?action=view&tid=<?= $these->iddoc ?>&q=<?= htmlspecialchars($q) ?>">
        <header>
            <small><?= \App\Model\These::getEstabShortName($these) ?></small>
            <?php if ($these->online) : ?>
                <kbd>PDF</kbd>
            <?php endif; ?>
        </header>
        <h3 class="ellipsis"><?= $these->title ?></h3>
    </a>
    <p>
        <span class="date">
            <?= $these->date_year ?>
            <?= $these->lang === "en" ? "(Traduit de l'anglais)" : "" ?> —
        </span>
        <span class="summary">
            <!-- TODO: Si la requete est un des sujets, on affiche la liste des sujets avec en gras le sujet en question -->
            <?php if (\App\Model\These::containSubject($these, $q)) : ?>
                Sujets mentionnés :
                <ul class="subjects">
                    <?php foreach (\App\Model\These::getSubjects($these) as $subject) : ?>
                        <li>
                            <?php if (\App\Model\These::containExactMatch($q, $subject)) : ?>
                                <b><?= $subject ?></b>
                            <?php else : ?>
                                <span><?= $subject ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <?= mb_substr($these->summary, 0, 160, "UTF-8") ?>...
            <?php endif; ?>
        </span>
    </p>
    <ul class="subjects">
        <?php foreach (\App\Model\These::getCommonSubjects($these, $subjectsCount[$pos]) as $k => $subject) : ?>
            <?php if ($k === 0) : ?>
                <span>Voir aussi :</span>
            <?php endif; ?>
            <a href="/?action=search&q=%22<?= $subject ?>%22">
                <li>
                    <span><?= $subject ?></span>
                </li>
            </a>
        <?php endforeach; ?>
    </ul>
    <ul class="subjects">
        <?php if ($these->online) : ?>
            <a href="<?= \App\Model\These::getOnlineLink($these) ?>">
                <li>Lire en ligne</li>
            </a>
        <?php endif; ?>
    </ul>
</article>