<main>
    <div class="grid">
        <div class="s12 m10">
            <p style="margin-top: 22px;"><?= $thesis->date_year ?></p>
            <h1 style="font-size: 28px;"><?= $thesis->title ?></h1>
            <?php if (strlen($subjects[0]) > 0) : ?>
                <nav class="scroll">
                    <?php foreach ($subjects as $subject) : ?>
                        <a href="/?action=search&q=%22<?= $subject ?>%22" class="chip fill small"><?= $subject ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>

        <div class="s12 m2">
            <div class="m l" style="height: 32px;"></div>
            <a target="_blank" href="<?= $onlineLink ?>" class="button" <?= $thesis->online ? "" : "disabled" ?>>Lire en ligne</a>
            <?php if (!$thesis->online) : ?>
                <br>
                <small>Cette thèse n'est pas publiée sur internet</small>
            <?php endif; ?>
        </div>

        <div class="l8 s12">
            <article class="no-elevate white">
                <h5>Résumé</h5>
                <p id="readMoreText" style="font-size: 16px;"><?= $thesis->summary ?></p>
            </article>

            <article class="no-elevate white">
                <h5>Personnes concernées</h5>
            </article>
        </div>

        <div class="l4 s12">
            <article class="no-padding no-elevate white">
                <img class="responsive" src="<?= $map ?>" alt="Map Bing">
                <div class="padding">
                    <small>Soutenu à</small>
                    <a href="/?action=search&q=à+<?= $thesis->code_etab ?>">
                        <strong><?= $establishments[0] ?></strong>
                    </a>

                    <p><img class="flag" src="<?= $flag ?>" width="30" alt="Drapeau <?= $thesis->lang ?>"> Langue originale de la thèse</p>
                </div>
            </article>
        </div>
    </div>
</main>

<script>
    const readMoreText = document.getElementById('readMoreText');
    const fullText = readMoreText.innerText;
    const defaultHeight = readMoreText.clientHeight;

    if (fullText.length > 500) {
        readMoreText.innerHTML = fullText.substring(0, 300) + '... <button class="link" onclick="revealText();">voir plus</button>';
    }

    function revealText() {
        readMoreText.style.height = readMoreText.clientHeight + 'px';
        readMoreText.innerHTML = fullText;
        readMoreText.style.height = defaultHeight + 'px';

        setTimeout(() => {
            readMoreText.style.height = 'auto';
        }, 300);
    }
</script>

<style>
    article h5 {
        margin-left: 0;
    }
</style>