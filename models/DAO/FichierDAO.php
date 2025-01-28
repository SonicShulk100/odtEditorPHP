<?php
class FichierDAO {
    /**
     * Récupération des fichiers dans la base de données.
     * @return array|bool les données sous format JSON
     */
    public static function getAllFichiers(): array|bool{
        //Instanciation d'un PDO
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);

        try{
            //Requête SQL
            $SQL = "SELECT * FROM fichier";

            //Préparation de la requête SQL
            $stmt = $db->prepare($SQL);

            //Exécution de la requête
            $stmt->execute();

            //Récupération des données
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Récupération du fichier en question dans la base de données.
     * @param int|null $id l'ID du fichier en quesiton
     * @return bool TRUE si il existe, FALSE sinon.
     */
    public static function getFichierById(?int $id): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);

        try{
            $SQL = "SELECT * FROM fichier WHERE idFichier = :id";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Récupération des fichiers en fonction de l'utilisateur.
     * @param int|null $idUtilisateur l'ID de l'utilisateur
     * @return bool|array Les données sous format JSON
     */
    public static function getFichiersByIdUtilisateur(?int $idUtilisateur): bool|array
    {
        if (!$idUtilisateur) {
            return [];
        }

        $db = new PDO(Param::DSN, Param::USER, Param::PASS);

        try {
            $SQL = "SELECT * FROM fichier WHERE idUtilisateur = :idUtilisateur";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(":idUtilisateur", $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log('Erreur : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Insertion d'un fichier dans la base de données.
     * @param string|null $nomFichier Le nom du fichier
     * @param string|null $contenu Le contenu deu fichier
     * @param DateTime|null $dateAjout La date de l'ajout qui ne doit pas être changé
     * @param DateTime|null $dateMaj La date de la modification qui doit être changé
     * @param int|null $idUtilisateur L'ID de l'utilisateur.
     * @return bool TRUE si l'insertion a été fait, FALSE sinon
     */
    public static function createFichier(?string $nomFichier, ?string $contenu, ?DateTime $dateAjout, ?DateTime $dateMaj, ?int $idUtilisateur): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        $db->exec("SET AUTOCOMMIT=0");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            $db->beginTransaction();

            $sql = "INSERT INTO fichier (nomFichier, contenuFichier, dateAjout, dateMaJ, idUtilisateur) VALUES (?, ?, ?, ?, ?)";

            //Préparation de la requête SQL
            $stmt = $db->prepare($sql);

            //Mise en place des paramètres
            $stmt->bindParam(1, $nomFichier);
            $stmt->bindParam(2, $contenu);
            $stmt->bindParam(3, $dateAjout);
            $stmt->bindParam(4, $dateMaj);
            $stmt->bindParam(5, $idUtilisateur);

            //Exécution
            $stmt->execute();

            //Commitage
            return $db->commit();
        }
        catch(PDOException $e){
            $db->rollBack();
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Mise à jour du fichier en quesion
     * @param int|null $idFichier L'ID du fichier en question
     * @param string|null $nomFichier le nom du fichier en question
     * @param string|null $contenu le contenu du fichier en quesion
     * @param int|null $idUtilisateur l'ID de l'utilisateur
     * @return bool TRUE si la MAJ est fait, FALSE sinon.
     */
    public static function updateFichier(?int $idFichier, ?string $nomFichier, ?string $contenu, ?int $idUtilisateur): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        $db->exec("SET AUTOCOMMIT=0");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            $db->beginTransaction();

            $sql = "UPDATE fichier SET nomFichier = ?, contenuFichier = ?, dateMaJ = CURRENT_DATE WHERE idFichier = ? OR idUtilisateur = ?";

            //Préparation de la requête SQL
            $stmt = $db->prepare($sql);

            //Mise en place des paramètres

            //Execution de la requête avec les paramètres.
            $stmt->execute([$nomFichier, $contenu, $idFichier, $idUtilisateur]);

            //Commitage
            return $db->commit();
        }
        catch(PDOException $e){
            $db->rollBack();
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Supression du fichier
     * @param int|null $idFichier l'ID du fichier en question
     * @param int|null $idUtilisateur l'ID de l'utilisateur
     * @return bool TRUE si la supression est bien faite, FALSE sinon.
     */
    public static function deleteFichier(?int $idFichier, ?int $idUtilisateur): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        $db->exec("SET AUTOCOMMIT=0");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            $db->beginTransaction();

            $sql = "DELETE FROM fichier WHERE idFichier = ? OR idUtilisateur = ?";

            //Préparation de la requête SQL
            $stmt = $db->prepare($sql);

            //Mise ne place du paramètre
            $stmt->bindParam(1, $idFichier);
            $stmt->bindParam(2, $idUtilisateur);

            //Execution
            $stmt->execute();

            //Commitage
            return $db->commit();
        }
        catch (PDOException $e){
            $db->rollBack();
            die('Erreur : '.$e->getMessage());
        }
    }
}
