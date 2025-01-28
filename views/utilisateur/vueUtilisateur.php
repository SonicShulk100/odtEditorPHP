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
                    <th>Date MAJ</th>
                    <th>ID de l'utilisateur</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Files = FichierDAO::getFichiersByIdUtilisateur((int)$_SESSION['idUtilisateur']);
            foreach ($Files as $odt){
                echo "<tr>";
                echo "<td>".$odt->getId()."</td>";
                echo "<td>".$odt->getNom()."</td>";
                echo "<td>".$odt->getContenu()."</td>";
                echo "<td>".$odt->getDateCreation()."</td>";
                echo "<td>".$odt->getDateModification()."</td>";
                echo "<td>".$odt->getIdUtilisateur()."</td>";
                echo "<td>
                        <a href='/index.php?action=modifierFichier&id=".$odt->getId()."'>Modifier le fichier</a>
                        <a href='/index.php?action=supprimerFichier&id=".$odt->getId()."'>Supprimer le fichier</a>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
