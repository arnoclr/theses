<br>
<h5>Voir aussi</h5>
<nav class="scroll" style="flex-flow: row wrap;">
    <?php foreach ($wikipediaSubjects as $data) : ?>
        <a class="subjectCard" href="/?action=search&q=%22<?= $data['title'] ?>%22">
            <p><?= $data['title'] ?></p>
            <img height="120" src="<?= $data['thumbnail']['source'] ?>" alt="<?= $data['title'] ?>">
        </a>
    <?php endforeach; ?>
</nav>