<?php
//Initialisation du statut de suppression et du message d'erreur.
$statutSuppression = $_GET['suppression'] ?? null;
$messageErreur = $_GET['erreur'] ?? null;

//Un succès?
if ($statutSuppression === 'success') {
    //Affichage du message.
    $message = '<div class="alertalert-success">Le fichier a été supprimé avec succès.</div>';
    //Quelque chose n'allait pas pendant la suppression du fichier ?
} elseif ($statutSuppression === 'erreur') {
    //Affichage de l'erreur.
    $message = '<div class="alertalert-danger">' . $messageErreur . '</div>';
}
?>

<!-- Conteneur de la page -->
<div class="container">
    <nav>
        <?php
        //Affichage des boutons de navigation.
        require_once "views/haut.php";
        ?>
    </nav>
    <section>
        <h2>Utilisateur :<?php print_r(UtilisateurDAO::getUtilisateurById((int)$_SESSION['idUtilisateur'])); ?></h2>
        <!-- Votre contenu existant -->
        <?php echo $message ?? ''; ?>
        <!-- Création d'un tableau pour les fichiers ODT. -->
        <table id="tableFichiersODTUtilisateur" border="3">
            <thead>
                <tr>
                    <th>ID du fichier</th>
                    <th>Nom du fichier</th>
                    <th>Contenu du fichier</th>
                    <th>Date de création</th>
                    <th>Date de mise à Jour</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <br>
            <?php
            //Récupération des fichiers en fonction de l'utilisateur.
            $fichiers = FichierDAO::getFichiersByIdUtilisateur((int)$_SESSION['idUtilisateur']);

            // Affichage des fichiers récupérés dans le tableau
            foreach ($fichiers as $fichier) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fichier->getId()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getNom()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getContenu()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getCreatedAt()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getUpdatedAt()) . "</td>";
                echo "<td>
                        <a href='/index.php?action=modifierFichier&id=" . htmlspecialchars($fichier->getId()) . "'>Modifier</a>
                        <a href='/index.php?action=supprimerFichier&idFichier=" . htmlspecialchars($fichier->getId()) . "'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </section>
    <?php
        //Importation du footer.
        require_once "views/bas.php";
    ?>
</div>
