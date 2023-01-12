<?php if (preg_match("/$filterTerm/i", $q)) : ?>
    <a class="chip fill" href="/?action=search&q=<?= htmlspecialchars(preg_replace("/$filterTerm/i", "", $q)) ?>">
        <span><?= $filterTerm ?></span>
        <i class="small">close</i>
    </a>
<?php elseif (!preg_match("/$exclude/i", $q)) : ?>
    <a class="chip border" href="/?action=search&q=<?= htmlspecialchars($q) ?>+<?= $filterTerm ?>">
        <span><?= $filterTerm ?></span>
    </a>
<?php endif; ?>