<h5><?= $wikipediaData['title'] ?></h5>
<p style="font-size: 18px;"><?= $wikipediaData['extract'] ?></p>
<?php if (isset($wikipediaData['thumbnail'])) : ?>
    <img height="120" src="<?= $wikipediaData['thumbnail']['source'] ?>" alt="<?= $wikipediaData['title'] ?>">
    <br>
<?php endif; ?>
<small style="font-style: italic">wikipedia.org</small>