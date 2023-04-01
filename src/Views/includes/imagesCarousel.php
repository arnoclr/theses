<?php if ($these->online) : ?>
    <?php $images = getCache("pdf.images." . $these->nnt); ?>
    <?php if ($images !== null) : ?>
        <div class="carousel" style="height: 0px;">
            <?php foreach ($images as $src) : ?>
                <?php require "image.php"; ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="carousel">
            <small>Chargement des images ...</small>
            <img src="/public/img/pixel.png" loading="lazy" onload="
            fetch('/?action=imagesFromPDF&headless=1&nnt=<?= $these->nnt ?>').then(r => r.text()).then(html => {
                if (html.includes('img')) {
                    this.parentNode.innerHTML = html;
                } else {
                    this.parentNode.style.height = '0px';
                }
            });
        ">
        </div>
    <?php endif; ?>
<?php endif; ?>