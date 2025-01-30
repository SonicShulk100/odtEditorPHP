<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
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
            <?php
            $fichiers = FichierDAO::getFichiersByIdUtilisateur((int)$_SESSION['idUtilisateur']);

            // Affichage des fichiers dans le tableau
            foreach ($fichiers as $fichier) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fichier->getId()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getNom()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getContenu()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getCreatedAt()) . "</td>";
                echo "<td>" . htmlspecialchars($fichier->getUpdatedAt()) . "</td>";
                echo "<td>
                        <a href='/index.php?action=modifierFichier&id=" . htmlspecialchars($fichier->getId()) . "'>Modifier</a>
                        <a href='/index.php?action=supprimerFichier&id=" . htmlspecialchars($fichier->getId()) . "'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
