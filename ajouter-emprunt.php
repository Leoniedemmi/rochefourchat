<?php
require_once 'config/database.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_adherent = trim($_POST['nom_adherent']);
    $titre_document = trim($_POST['titre_document']);
    $type_document = trim($_POST['type_document']);
    $date_emprunt = trim($_POST['date_emprunt']);
    $date_retour = trim($_POST['date_retour']);
    $reserve = isset($_POST['reserve']) ? 1 : 0;

    // Validation des champs
    if (empty($nom_adherent) || empty($titre_document) || empty($type_document) || empty($date_emprunt) || empty($date_retour)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (strtotime($date_retour) <= strtotime($date_emprunt)) {
        $error = "La date de retour doit être postérieure à la date d'emprunt.";
    } else {
        try {
            $adherent_id = null;
            
            // Chercher d'abord si l'adhérent existe dans la base (CORRECTION: ADHERENT au lieu de adherent)
            $adherent_sql = "SELECT id FROM ADHERENT WHERE CONCAT(Nom, ' ', Prenom) LIKE :nom_adherent OR Nom LIKE :nom_adherent OR Prenom LIKE :nom_adherent LIMIT 1";
            $adherent_stmt = $pdo->prepare($adherent_sql);
            $adherent_stmt->execute([':nom_adherent' => '%' . $nom_adherent . '%']);
            $adherent = $adherent_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$adherent) {
                $error = "Aucun adhérent trouvé avec ce nom. Veuillez d'abord l'ajouter dans la section 'Ajouter un adhérent'.";
            } else {
                $adherent_id = $adherent['id'];
                
                // Chercher le document par titre et type (CORRECTION: PRODUIT au lieu de produit)
                $document_sql = "SELECT id FROM PRODUIT WHERE Titre LIKE :titre AND Type = :type LIMIT 1";
                $document_stmt = $pdo->prepare($document_sql);
                $document_stmt->execute([
                    ':titre' => '%' . $titre_document . '%',
                    ':type' => $type_document
                ]);
                $document = $document_stmt->fetch(PDO::FETCH_ASSOC);

                if (!$document) {
                    $error = "Aucun document trouvé avec ce titre et ce type.";
                } else {
                    // Récupérer l'ID du document
                    $document_id = $document['id'];
                    
                    // Vérifier si le document n'est pas déjà emprunté (CORRECTION: EMPRUNT au lieu de emprunt)
                    $check_emprunt_sql = "SELECT COUNT(*) FROM EMPRUNT WHERE PRODUIT_id = :document_id AND Date_Retour IS NULL";
                    $check_emprunt_stmt = $pdo->prepare($check_emprunt_sql);
                    $check_emprunt_stmt->execute([':document_id' => $document_id]);

                    if ($check_emprunt_stmt->fetchColumn() > 0) {
                        $error = "Ce document est déjà emprunté et n'a pas encore été retourné.";
                    } else {
                        // Insérer le nouvel emprunt (CORRECTION: EMPRUNT au lieu de emprunt)
                        $sql = "INSERT INTO EMPRUNT (ADHERENT_id, PRODUIT_id, Date_Emprunt, Date_Retour) 
                                VALUES (:adherent_id, :document_id, :date_emprunt, :date_retour)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':adherent_id' => $adherent_id,
                            ':document_id' => $document_id,
                            ':date_emprunt' => $date_emprunt,
                            ':date_retour' => $date_retour
                        ]);

                        $message = "L'emprunt a été ajouté avec succès !";

                        // Réinitialiser les champs
                        $nom_adherent = $titre_document = $type_document = $date_emprunt = $date_retour = '';
                        $reserve = 0;
                    }
                }
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
    <title>Ajouter un emprunt - Médiathèque de la Rochefourchet</title>
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
                    <li class="separator"></li>
                    <li><a href="consulter-documents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-documents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les documents</a></li>
                    <li><a href="ajouter-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-document.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un document</a></li>
                    <li><a href="modifier-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-document.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un document</a></li>
                    <li class="separator"></li>
                    <li><a href="consulter-adherents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-adherents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les adhérents</a></li>
                    <li><a href="ajouter-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-adherent.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un adhérent</a></li>
                    <li><a href="modifier-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-adherent.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un adhérent</a></li>
                    <li class="separator"></li>
                    <li><a href="consulter-emprunts.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-emprunts.php') ? 'style="color: #442424;"' : '' ?>>Consulter les emprunts</a></li>
                    <li><a href="ajouter-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un emprunt</a></li>
                    <li><a href="modifier-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un emprunt</a></li>
                </ul>
            </nav>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            <div class="header">
                <h1>Ajouter un emprunt</h1>
            </div>

            <div class="main-content">
                <form class="form-emprunt" method="POST" action="">
                    <?php if (!empty($message)): ?>
                        <div class="message success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="message error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nom_adherent">Nom de l'adhérent *</label>
                        <input type="text" id="nom_adherent" name="nom_adherent" class="form-control" 
                               value="<?= htmlspecialchars($nom_adherent ?? '') ?>" 
                               placeholder="Tapez le nom ou prénom" required>
                        <div class="form-hint">Exemple: "Dupont" ou "Jean"</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="titre_document">Titre du document *</label>
                        <input type="text" id="titre_document" name="titre_document" class="form-control" 
                               value="<?= htmlspecialchars($titre_document ?? '') ?>" 
                               placeholder="Tapez le titre du document" required>
                        <div class="form-hint">Exemple: "Le Seigneur des Anneaux"</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="type_document">Type de document *</label>
                        <select id="type_document" name="type_document" class="form-control" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Livre" <?= (isset($type_document) && $type_document == 'Livre') ? 'selected' : '' ?>>Livre</option>
                            <option value="DVD" <?= (isset($type_document) && $type_document == 'DVD') ? 'selected' : '' ?>>DVD</option>
                            <option value="CD" <?= (isset($type_document) && $type_document == 'CD') ? 'selected' : '' ?>>CD</option>
                            <option value="Cassette audio" <?= (isset($type_document) && $type_document == 'Cassette audio') ? 'selected' : '' ?>>Cassette audio</option>
                            <option value="VHS" <?= (isset($type_document) && $type_document == 'VHS') ? 'selected' : '' ?>>VHS</option>
                            <option value="Vinyle" <?= (isset($type_document) && $type_document == 'Vinyle') ? 'selected' : '' ?>>Vinyle</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_emprunt">Date d'emprunt *</label>
                        <input type="date" id="date_emprunt" name="date_emprunt" class="form-control" 
                               value="<?= htmlspecialchars($date_emprunt ?? date('Y-m-d')) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_retour">Date de retour prévue *</label>
                        <input type="date" id="date_retour" name="date_retour" class="form-control" 
                               value="<?= htmlspecialchars($date_retour ?? date('Y-m-d', strtotime('+2 weeks'))) ?>" required>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="submit-btn">Ajouter l'emprunt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculer la date de retour (2 semaines après la date d'emprunt)
        document.getElementById('date_emprunt').addEventListener('change', function() {
            const dateEmprunt = new Date(this.value);
            if (dateEmprunt) {
                const dateRetour = new Date(dateEmprunt);
                dateRetour.setDate(dateRetour.getDate() + 14); // Ajouter 2 semaines
                
                const year = dateRetour.getFullYear();
                const month = String(dateRetour.getMonth() + 1).padStart(2, '0');
                const day = String(dateRetour.getDate()).padStart(2, '0');
                
                document.getElementById('date_retour').value = `${year}-${month}-${day}`;
            }
        });
    </script>
</body>
</html>