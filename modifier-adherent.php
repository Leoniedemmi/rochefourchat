<?php
require_once 'config/database.php';
$message = '';
$messageType = '';

// Traitement de la suppression
if (isset($_POST['supprimer']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM ADHERENT WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $message = "Adhérent supprimé avec succès !";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
        $messageType = "error";
    }
}

// Traitement de la modification
if (isset($_POST['modifier']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE ADHERENT SET Mail = ?, Nom = ?, Prenom = ? WHERE id = ?");
        $stmt->execute([
            $_POST['mail'],
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['id']
        ]);
        $message = "Adhérent modifié avec succès !";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification : " . $e->getMessage();
        $messageType = "error";
    }
}

// Récupération des adhérents avec recherche
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = "WHERE Nom LIKE ? OR Prenom LIKE ? OR Mail LIKE ?";
    $searchParam = "%$search%";
    $params = [$searchParam, $searchParam, $searchParam];
}

$stmt = $pdo->prepare("SELECT * FROM ADHERENT $whereClause ORDER BY Nom, Prenom");
$stmt->execute($params);
$adherents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de l'adhérent sélectionné pour modification
$selectedAdherent = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM ADHERENT WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $selectedAdherent = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier/supprimer un adhérent - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .form-modifier {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .adherent-list {
            margin-top: 20px;
            background-color: #fff8f8;
            padding: 15px;
            border-radius: 5px;
            max-height: 400px;
            overflow-y: auto;
        }
        .adherent-item {
            display: grid;
            grid-template-columns: 2fr 2fr 3fr auto;
            gap: 10px;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        .adherent-item:hover {
            background-color: #f0f0f0;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .modify-btn, .edit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .modify-btn:hover, .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .adherent-header {
            display: grid;
            grid-template-columns: 2fr 2fr 3fr auto;
            gap: 10px;
            font-weight: bold;
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .cancel-btn:hover {
            background-color: #5a6268;
        }
        .form-group-full {
            grid-column: 1 / -1;
        }
    </style>
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
                <h1>Modifier/supprimer un adhérent</h1>
            </div>

            <div class="main-content">
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de recherche -->
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Rechercher un adhérent..." 
                           class="search-input" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">Rechercher</button>
                    <?php if ($search): ?>
                        <a href="modifier-adherent.php" class="cancel-btn">Effacer</a>
                    <?php endif; ?>
                </form>
                
                <!-- Liste des adhérents -->
                <div class="adherent-list">
                    <div class="adherent-header">
                        <span>Nom</span>
                        <span>Prénom</span>
                        <span>Email</span>
                        <span>Actions</span>
                    </div>
                    
                    <?php if (empty($adherents)): ?>
                        <p>Aucun adhérent trouvé.</p>
                    <?php else: ?>
                        <?php foreach ($adherents as $adherent): ?>
                            <div class="adherent-item">
                                <span><?php echo htmlspecialchars($adherent['Nom']); ?></span>
                                <span><?php echo htmlspecialchars($adherent['Prenom']); ?></span>
                                <span><?php echo htmlspecialchars($adherent['Mail']); ?></span>
                                <div style="display: flex; gap: 5px;">
                                    <a href="?edit_id=<?php echo $adherent['id']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                                       class="edit-btn">Modifier</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet adhérent ?');">
                                        <input type="hidden" name="id" value="<?php echo $adherent['id']; ?>">
                                        <button type="submit" name="supprimer" class="delete-btn">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Formulaire de modification -->
                <?php if ($selectedAdherent): ?>
                    <div class="form-container" style="margin-top: 20px;">
                        <h3>Modifier l'adhérent : <?php echo htmlspecialchars($selectedAdherent['Prenom'] . ' ' . $selectedAdherent['Nom']); ?></h3>
                        
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedAdherent['id']; ?>">
                            
                            <div class="form-modifier">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedAdherent['Nom']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" id="prenom" name="prenom" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedAdherent['Prenom']); ?>" required>
                                </div>
                                
                                <div class="form-group form-group-full">
                                    <label for="mail">Email</label>
                                    <input type="email" id="mail" name="mail" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedAdherent['Mail']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" name="modifier" class="modify-btn">Enregistrer les modifications</button>
                                <a href="modifier-adherent.php<?php echo $search ? '?search=' . urlencode($search) : ''; ?>" 
                                   class="cancel-btn">Annuler</a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>