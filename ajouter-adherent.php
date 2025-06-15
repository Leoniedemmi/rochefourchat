<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
$adherent_filter = isset($_GET['adherent']) ? $_GET['adherent'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['Mail']);

    // Validation des champs
    if (empty($nom) || empty($prenom) || empty($email)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        try {
            // Vérifier si l'email existe déjà
            $check_sql = "SELECT COUNT(*) FROM ADHERENT WHERE Mail = :email";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':email' => $email]);

            if ($check_stmt->fetchColumn() > 0) {
                $error = "Un adhérent avec cette adresse email existe déjà.";
            } else {
                // Insérer le nouvel adhérent (l'ID sera généré automatiquement)
                $sql = "INSERT INTO ADHERENT (nom, prenom, Mail) VALUES (:nom, :prenom, :email)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':email' => $email
                ]);

                $message = "L'adhérent a été ajouté avec succès !";

                // Réinitialiser les champs
                $nom = $prenom = $email = '';
            }
        } catch(PDOException $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un adhérent - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="container">
        <!-- Menu de navigation -->
        <div class="sidebar">
            <div class="logo">
                <h3>Admin</h3>
            </div>
            <nav class="menu">
                <ul>
                    <li><a href="index.php" <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'style="color: #442424;"' : '' ?>>Statistiques</a></li>
                    <br><br>
                    <li><a href="consulter-documents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-documents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les documents</a></li>
                    <li><a href="ajouter-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-document.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un document</a></li>
                    <li><a href="modifier-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-document.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un document</a></li>
                    <br><br>
                    <li><a href="consulter-adherents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-adherents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les adhérents</a></li>
                    <li><a href="ajouter-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-adherent.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un adhérent</a></li>
                    <li><a href="modifier-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-adherent.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un adhérent</a></li>
                    <br><br>
                    <li><a href="consulter-emprunts.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-emprunts.php') ? 'style="color: #442424;"' : '' ?>>Consulter les emprunts</a></li>
                    <li><a href="ajouter-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un emprunt</a></li>
                    <li><a href="modifier-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un emprunt</a></li>
                </ul>
            </nav>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            <div class="header">
                <h1>Ajouter un adhérent</h1>
            </div>

            <div class="main-content">
                <form class="form-adherent" method="POST" action="">
                    <?php if (!empty($message)): ?>
                        <div class="message success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="message error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($nom ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Mail">Email *</label>
                        <input type="email" id="Mail" name="Mail" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="submit-btn">Ajouter l'adhérent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
