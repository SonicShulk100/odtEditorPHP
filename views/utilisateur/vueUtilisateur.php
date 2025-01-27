<div class="container">
    <nav>
        <?php require_once "views/haut.php"; ?>
    </nav>
    <section>
        <table id="tableFichiersODTUtilisateur">
            <thead>
                <tr>
                    <th>ID du fichier</th>
                    <th>Nom du fichier</th>
                    <th>Contenu du fichier</th>
                    <th>Date de création</th>
                    <th>Date MAJ</th>
                    <th>ID de l'utilisateur</th>
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
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <a href="/index.php?action=importer">Importer un fichier ODT</a>
    </section>
    <?php require_once "views/bas.php"; ?>
</div>
