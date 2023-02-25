<header class="comparisons">
    <h4>Comparer les résultats</h4>
    <ul>
        <?php foreach ($comparisons as $pos => $q) : ?>
            <li>
                <a href="/?action=search&q=<?= urlencode($q) ?>">
                    <i class="circle" style="background-color: <?= \App\Model\Charts::getColorAt($pos) ?>;"></i>
                    <span><?= $decoders[$pos]->displayableQuery() ?></span>
                    <?php if ($decoders[$pos]->queryContainExactMatchExpression()) : ?>
                        <small>Contient une correspondance exacte</small>
                    <?php endif; ?>
                </a>

                <ul class="filters">
                    <?php foreach ($decoders[$pos]->displayableFilters() as $filter) : ?>
                        <li>
                            <?= $filter ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</header>