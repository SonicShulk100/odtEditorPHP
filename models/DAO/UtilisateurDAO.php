<?php
/**
 * DAO pour l'Utilisateur dans la base de données.
 */
class UtilisateurDAO {
    /**
     * Récupération des utilisateurs
     * @return array|bool les données sous format JSON
     */
    public static function getAllUtilisateurs(): array|bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);

        try{
            //Requête SQL
            $sql = "SELECT * FROM utilisateur";

            //Préparation de la requête SQL
            $stmt = $db->prepare($sql);

            //Execution de la requête.
            $stmt->execute();

            //Récupération des données avec la méthode FETCH_ASSOC
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * On récupère le nom et prénom pour l'affichage dans le dashboard de l'utilisateur.
     * @param int|null $idUtilisateur l'ID de l'utilisateur.
     * @return array|bool
     */
    public static function getUtilisateurById(?int $idUtilisateur): array|bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);

        try{
            $SQL = "SELECT * FROM utilisateur WHERE idUtilisateur = ?";

            $stmt = $db->prepare($SQL);
            $stmt->execute([$idUtilisateur]);

            $utilisateur = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $utilisateur[] = new Utilisateur(
                    $row["idUtilisateur"],
                    $row["nom"],
                    $row["prenom"],
                    $row["login"],
                    $row["mdp"]
                );
            }

            return $utilisateur;
        }
        catch(PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Vérification en cas de connexion.
     * @param string|null $login le login(en email)
     * @param string|null $mdp le MDP (encodage MD5)
     * @return array|bool TRUE si l'utilisateur existe et crée une array contenant les données de cet utilisateur, FALSE sinon.
     */
    public static function verif(?string $login, ?string $mdp): array|bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        try{
            //Requête SQL
            $SQL = "SELECT * FROM utilisateur WHERE login = ? AND mdp = md5(?)";

            //Préparation de la requête SQL
            $stmt = $db->prepare($SQL);

            //Execution de la reqête avec les paramètres
            $stmt->execute([$login, $mdp]);

            //Récupération des données avec la requête FETCH_ASSOC
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Création de l'utilisateur en cas d'inscription.
     * @param string|null $nom le nom de l'utilisateur
     * @param string|null $prenom le prénom de l'utilisateur
     * @param string|null $login le login (mail) de l'utilisateur
     * @param string|null $mdp le MDP (encodage MD5) de l'utilisateur
     * @return bool TRUE si on est inscrit, FALSE sinon.
     */
    public static function createUtilisateur(?string $nom, ?string $prenom, ?string $login, ?string $mdp): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        $db->exec("SET AUTOCOMMIT=0");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            //Début de la transaction
            $db->beginTransaction();

            //Requête de l'insertion
            $SQL = "INSERT INTO utilisateur (nom, prenom, login, mdp) VALUES(?, ?, ?, md5(?))";

            //Préparation de la requête
            $stmt = $db->prepare($SQL);

            //Execution de la requête avec les paramètres.
            $stmt->execute([$nom, $prenom, $login, $mdp]);

            //Committer
            return $db->commit();
        }
        catch(PDOException $e){
            $db->rollBack();
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Mise à jour de l'utilisateur
     * @param int|null $idUtilisateur l'ID de l'utilsiateur
     * @param string|null $nom le nom de l'utilsateur
     * @param string|null $prenom le prénom de l'utilisateur
     * @param string|null $login le login de l'utilisateur
     * @param string|null $mdp le MDP de l'utilisateur
     * @return bool TRUE si la modification est faite, FALSE sinon.
     */
    public static function updateUtilisateur(?int $idUtilisateur, ?string $nom, ?string $prenom, ?string $login, ?string $mdp): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        $db->exec("SET AUTOCOMMIT=0");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            //Début de la transaction
            $db->beginTransaction();

            //Requête SQL
            $sql = "UPDATE utilisateur SET nom = ?, prenom = ?, login = ?, mdp = md5(?) WHERE idUtilisateur = ?";

            //Préparation de la requête
            $stmt = $db->prepare($sql);

            //Mise en place des paramètres
            $stmt->bindParam(1, $idUtilisateur);
            $stmt->bindParam(2, $nom);
            $stmt->bindParam(3, $prenom);
            $stmt->bindParam(4, $login);
            $stmt->bindParam(5, $mdp);

            //Execution de la requête
            $stmt->execute();

            //Committer
            return $db->commit();
        }
        catch(PDOException $e){
            $db->rollBack();
            die('Erreur : '.$e->getMessage());
        }
    }

    /**
     * Suppression de l'utilisateur.
     * @param int $idUtilisateur l'ID de l'utilisateur
     * @return bool TRUE si la supression est faite, FALSE sinon.
     */
    public static function deleteUtilisateur(int $idUtilisateur): bool{
        $db = new PDO(Param::DSN, Param::USER, Param::PASS);
        $db->exec("SET AUTOCOMMIT=0");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            //Début de la transaction
            $db->beginTransaction();

            //Requête de la suppression
            $sql = "DELETE FROM utilisateur WHERE idUtilisateur = ?";

            //Préparation de la requête.
            $stmt = $db->prepare($sql);

            //Execution de la requête.
            $stmt->execute([$idUtilisateur]);

            //Committer
            return $db->commit();
        }
        catch(PDOException $e){
            $db->rollBack();
            die('Erreur : '.$e->getMessage());
        }
    }
}