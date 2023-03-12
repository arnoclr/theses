<div class="_f">
    <p>Une erreur s'est produite</p>
    <h5><?= $e->getMessage() ?></h5>


    <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5.15 20.3L0 15.15V0H36V16.85L30.8 11.65L22.25 20.2L13.75 11.7L5.15 20.3ZM0 36V19.4L5.15 24.55L13.75 15.95L22.25 24.45L30.8 15.9L36 21.1V36H0Z" fill="black" />
    </svg>

    <nav class="right-align">
        <a href="mailto:bug.theses@arno.cl?subject=Rapport de bug (<?= $e->getMessage() ?>)&body=<?php foreach ($e->getTrace() as $line) {
                                                                                                        echo '[' . $line['line'] . ':' . $line['function'] . ']';
                                                                                                    } ?>" class="button border">Envoyer le rapport d'erreur</a>
    </nav>
</div>

<style>
    ._f {
        margin: 32px auto;
        max-width: 500px;
        padding: 42px;
        border-radius: 2px;
        box-shadow: none;
        background-color: white;
    }

    textarea {
        width: 100%;
        border: none;
        border: 1px solid #ddd;
    }

    svg {
        display: block;
        margin: 56px auto;
        width: 50%;
        height: auto;
        opacity: 0.2;
    }
</style>