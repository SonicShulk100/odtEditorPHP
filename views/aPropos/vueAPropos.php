<div class="container">
    <nav>
        <?php require_once "views/haut.php";?>
    </nav>
    <section>
        <h2>A propos de ce projet : </h2>
        <div class="paragraph">
            <p>Ce projet est considéré comme un site qui permet de : </p>
            <ul>
                <li>Gérer l'intégration des fichiers ODT (OpenDocument) dans la base de données</li>
                <li>Gérer les utilisateurs dans la base de données</li>
            </ul>
            <p>Les langages utilisés sont : </p>
            <ul>
                <li>PHP : HyperText Preprocessor - Version : 8.4</li>
                <li>MySQL : SGBD (Système de Gestion de Base de Données) relationnel. - Version : 9.2</li>
                <li>HTML : HyperText Markup Language - Version 5</li>
                <li>CSS : Cascading Style Sheets - Version 2</li>
            </ul>
            <p>Les outils utilisés sont :</p>
            <ul>
                <li>WAMP server : Windows Apache MySQL PHP - Version : 3.3.7</li>
                <li>phpMyAdmin : Application web de gestions des SGBD type MySQL et MariaDB - Version : 5.2.2</li>
            </ul>
            <p>Les patterns utilisés sont : </p>
            <ul>
                <li>Chaîne de responsabilité : patron de conception comportemental qui permet de faire circuler des demandes dans une chaîne de handlers. Lorsqu’un handler reçoit une demande, il décide de la traiter ou de l’envoyer au handler suivant de la chaîne (Regardez le dossier 'utils' pour plus de détails).</li>
                <li>MVC (Modèle Vue Contrôleur) : Pattern d'architecture d'une application web.</li>
            </ul>
        </div>
    </section>
    <?php require_once "views/bas.php";?>
</div>
