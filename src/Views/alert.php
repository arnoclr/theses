<div class="_a">
    <h5><?= $success ? 'Parfait !' : 'Erreur lors de la confirmation' ?></h5>
    <p><?= $hint ?? "Vous êtes maintenant abonné(e) à l'alerte" ?></p>
</div>

<style>
    ._a {
        margin: 32px auto;
        padding: 42px;
        max-width: 480px;
        background-color: white;
    }
</style>