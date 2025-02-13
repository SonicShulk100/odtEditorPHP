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
        <?php
            require_once "views/haut.php";
        ?>
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

        <!-- Conteneur pour rendre le tableau responsive -->
        <div class="table-responsive">
            <table id="tableFichiersODTUtilisateur">
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
        </div>

        <br>
        <button class="button"><a href="../../index.php?action=supprimerUtilisateur">Supprimer le compte</a></button>
        <button class="button"><a href="../../index.php?action=modifierCompte">Modifier le compte</a></button>
    </section>
    <?php
        require_once "views/bas.php";
    ?>
</div>

<style>
    /* Assurer que le tableau occupe toute la largeur sans overflow */
    .table-responsive {
        width: 100%;
        display: block;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
        font-size: 14px; /* Réduction de la taille du texte */
        word-wrap: break-word; /* Permet au texte de se couper */
        font-family: "Consolas", "Courier", "Courier New", monospace;
    }

    th {
        background-color: #f4f4f4;
    }

    /* Ajuster la taille des colonnes pour éviter le débordement */
    td:nth-child(1) { width: 5%; }   /* ID du fichier */
    td:nth-child(2) { width: 20%; }  /* Nom du fichier */
    td:nth-child(3) { width: 35%; }  /* Contenu du fichier */
    td:nth-child(4) { width: 15%; }  /* Date de création */
    td:nth-child(5) { width: 15%; }  /* Date de mise à jour */
    td:nth-child(6) { width: 10%; }  /* Actions */

    /* Assurer que le texte long s'affiche correctement */
    td, th {
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
