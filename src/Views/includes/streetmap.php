<?php $etabs = $decoders[0]->getEstablishments() ?>

<article class="searchResult big">
    <div id="osm" style="height: 320px; z-index: 0; font-size: 13px;"></div>
    <small><?= $decoders[0]->getMapLicence() ?></small>
    <p class="text"><?= count($etabs) ?> établissements à <?= htmlspecialchars($q) ?></p>
    <p style="font-style: italic;">
        <span>Liste des thèses soutenues à <?= htmlspecialchars($q) ?>. </span>
        <a style="text-decoration: underline;" href="/?action=search&q=%22<?= htmlspecialchars($q) ?>%22">Chercher plutot les thèses qui contiennent "<?= htmlspecialchars($q) ?>"</a>
    </p>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('osm').setView([51.505, -0.09], 13);

        var gl = L.mapboxGL({
            style: "https://openmaptiles.geo.data.gouv.fr/styles/osm-bright/style.json",
            accessToken: 'no-token',
        }).addTo(map);

        <?php foreach ($etabs as $etab) : ?>
            L.marker([<?= $etab->{'Géolocalisation'} ?>]).addTo(map)
                .bindPopup(<?= json_encode('<p>' . $etab->{'Libellé'} . '</p>') ?>)
        <?php endforeach; ?>

        map.fitBounds([
            [<?= $decoders[0]->getMapBoundaries()[0] ?>, <?= $decoders[0]->getMapBoundaries()[1] ?>],
            [<?= $decoders[0]->getMapBoundaries()[2] ?>, <?= $decoders[0]->getMapBoundaries()[3] ?>]
        ]);
    });
</script>