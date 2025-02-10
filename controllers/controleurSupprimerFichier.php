<?php

//Importation du DAO nécessaire.
require_once "models/DAO/FichierDAO.php";

//Récupération des erreurs.
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * Contrôleur pour la suppression d'un fichier ODT en fonction de l'ID du fichier.
 * @return void Les contrôleurs ne doivent pas retourner une réponse.
 */
function supprimerFichier(): void {
    // Récupération des paramètres depuis l'URL
    $idFichier = filter_input(INPUT_GET, 'idFichier', FILTER_VALIDATE_INT);
    $idUtilisateur = filter_input(INPUT_GET, 'idUtilisateur', FILTER_VALIDATE_INT);

    // Vérification des paramètres
    if ($idFichier === false || $idUtilisateur === false) {
        header('Location: index.php?action=utilisateur&erreur=parametres_invalides');
        exit();
    }

    // Passage des paramètres à la vue
    $_SESSION['suppression_fichier'] = [
        'idFichier' => $idFichier,
        'idUtilisateur' => $idUtilisateur
    ];

    include_once "views/supprimerFichier/vueSupprimerFichier.php";

}

/**
 * Contrôleur qui supprime un fichier ou redirige simplement en cas de refus.
 * @return void Comme la fonction "supprimerFichier", cette fonction est sous obligation de ne rien retourner.
 */
function supprimer(): void {
    // Vérification de la méthode HTTP
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?action=utilisateur");
        exit();
    }

    // Initialisation du statut et du message d'erreur
    $statutSuppression = null;
    $messageErreur = '';

    // Traitement de la validation
    if (isset($_POST["validation"])) {
        $idFichier = filter_input(INPUT_POST, "idFichier", FILTER_VALIDATE_INT);
        $idUtilisateur = filter_input(INPUT_POST, "idUtilisateur", FILTER_VALIDATE_INT);

        // Vérification des paramètres
        if ($idFichier === false || $idUtilisateur === false) {
            $messageErreur = 'Paramètres invalides';
        } else {
            try {
                $statutSuppression = FichierDAO::deleteFichier($idFichier, $idUtilisateur);

                // Si la suppression échoue, capturer l'erreur précise
                if ($statutSuppression === false) {
                    $messageErreur = 'Échec de la suppression du fichier';
                }
            } catch (PDOException $e) {
                $messageErreur = 'Erreur technique : ' . htmlspecialchars($e->getMessage());
            }
        }
    }

    // Redirection avec le statut et le message d'erreur si nécessaire
    $params = ['suppression' => ($statutSuppression ? 'success' : 'erreur')];
    if (!empty($messageErreur)) {
        $params['erreur'] = htmlspecialchars($messageErreur);
    }

    header('Location: index.php?action=utilisateur&' . http_build_query($params));
    exit();
}

// Ajouter cette fonction pour le débogage
function debugPDO(PDO $pdo): void {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);
}
