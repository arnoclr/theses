<?php if ($these->online) : ?>
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