<section class="establishment">
    <div class="grid">
        <div>
            <img src="http://www.google.com/s2/favicons?domain=<?= $establishmentData->{'Site internet'} ?>" alt="">
            <h2><?= $establishmentData->{'Libellé'} ?></h2>
            <small><?= $establishmentData->Commune ?>, <?= $establishmentData->Département ?></small>
            <a style="font-style: italic" href="<?= $establishmentData->{"Page Wikipédia en français"} ?>">wikipedia.org</a>
        </div>
        <img class="map" height="140" width="140" src="https://dev.virtualearth.net/REST/v1/Imagery/Map/Road/<?= $establishmentData->{"Géolocalisation"} ?>/16?mapSize=140,140&pp=<?= $establishmentData->{"Géolocalisation"} ?>;66&mapLayer=Basemap,Buildings&key=AiSO_FZNso9JJnrkixZ6T3d142q2DnTBLhQDVuZXeGFAI_gcnTD11M7JwvhevmzA" alt="">
    </div>

    <?php if ($establishmentData->{'nom_court'} !== "" || $establishmentData->{'Libellé'} !== "") : ?>
        <form class="snippet" action="/" method="get" id="estabSearchForm">
            <input id="estabSearchInput" type="search" placeholder="Chercher des thèses soutenues à <?= $establishmentData->{'Libellé'} ?>">
            <input id="estabHiddenInput" type="hidden" name="q" value="">
            <input type="hidden" name="action" value="search">
            <input type="submit" value="Rechercher">
        </form>
        <script>
            const f = document.getElementById('estabSearchForm');
            f.addEventListener('submit', (e) => {
                e.preventDefault();
                const i = document.getElementById('estabSearchInput');
                const h = document.getElementById('estabHiddenInput');
                h.value = i.value + ` a:"<?= $establishmentData->{'nom_court'} !== "" ? $establishmentData->{'nom_court'} : $establishmentData->{'Libellé'} ?>"`;
                f.submit();
            });
        </script>
    <?php endif; ?>
</section>