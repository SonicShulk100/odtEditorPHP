<?php
// Importation des fichiers nécessaires
require_once "controllers/controleurCreerFichier.php";
require_once "controllers/controleurSupprimerFichier.php";

// Initialisation du statut de suppression et du message d'erreur.
$statutAjout = $_GET["création"] ?? null;
$statutSuppression = $_GET['suppression'] ?? null;
$messageErreur = $_GET['erreur'] ?? null;

// Gestion des messages de notification
$message = '';
if ($statutSuppression === 'success') {
    $message = '<div class="alertalert-success">Le fichier a été supprimé avec succès.</div>';
} elseif ($statutAjout === "success") {
    $message = "<div class='alert alert-success'>Le fichier a été ajouté avec succès.</div>";
} elseif ($statutSuppression === 'erreur' || $statutAjout === "erreur") {
    $message = '<div class="alertalert-danger">' . htmlspecialchars($messageErreur) . '</div>';
}
?>

<!-- Conteneur de la page -->
<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <h2>Utilisateur :
            <?php
            $utilisateurs = UtilisateurDAO::getUtilisateurById((int)$_SESSION['idUtilisateur']);
            foreach ($utilisateurs as $utilisateur){
                echo htmlspecialchars($utilisateur->getNomutilisateur()) . " " . htmlspecialchars($utilisateur->getPrenom());
            }
            ?>
        </h2>

        <!-- Affichage des notifications -->
        <?php
        if (!empty($message)) {
            echo $message;
        }
        ?>

        <!-- Tableau pour les fichiers ODT -->
        <table id="tableFichiersODTUtilisateur" border="3">
            <thead>
            <tr>
                <th>ID du fichier</th>
                <th>Nom du fichier</th>
                <th>Contenu du fichier</th>
                <th>Date de création</th>
                <th>Date de mise à jour</th>
                <th style="width: 15%;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Récupération des fichiers de l'utilisateur
            $fichiers = FichierDAO::getFichiersByIdUtilisateur((int)$_SESSION['idUtilisateur']);

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
        <br>
        <button class="button"><a href="../../index.php?action=supprimerUtilisateur">Supprimer le compte</a></button>
    </section>
    <?php
        require_once "views/bas.php";
    ?>
</div>
