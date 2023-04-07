<article class="searchResult">
    <a title="<?= htmlspecialchars($these->title) ?>" href="<?= \App\Model\These::getOnlineLink($these) ?>">
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
            <?php $tableOfContents = App\Model\These::getTableOfContents($these->summary) ?>
            <?php if (\App\Model\These::containSubject($these, $q)) : ?>
                Sujets mentionnés :
                <ul class="subjects">
                    <?php foreach (\App\Model\These::getSubjects($these) as $subject) : ?>
                        <li>
                            <?php if (\App\Model\These::containExactMatch($subject, $q)) : ?>
                                <b><?= $subject ?></b>
                            <?php else : ?>
                                <span><?= $subject ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php elseif (count($tableOfContents) > 0) : ?>
                <ol>
                    <?php foreach ($tableOfContents as $toc) : ?>
                        <li><?= $toc ?></li>
                    <?php endforeach; ?>
                </ol>
            <?php else : ?>
                <?= \App\Model\These::highlightSummaryWith($these->summary, $q) ?>
            <?php endif; ?>
        </span>
    </p>
    <?php if (\App\Model\These::hasAtLeastOneCommonWord($these->establishments, $q)) : ?>
        <ul class="subjects">
            <li>
                <span>Soutenu à :</span>
            </li>
            <?php foreach (\App\Model\These::getEstablishments($these) as $estab) : ?>
                <li>
                    <?php if (\App\Model\These::hasAtLeastOneCommonWord($estab, $q)) : ?>
                        <b><?= $estab ?></b>
                    <?php else : ?>
                        <span><?= $estab ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
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
    <?php require "src/Views/includes/imagesCarousel.php"; ?>
</article>