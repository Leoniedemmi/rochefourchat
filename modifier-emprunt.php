<?php
require_once 'config/database.php';
$message = '';
$messageType = '';

// Traitement de la suppression
if (isset($_POST['supprimer']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM EMPRUNT WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $message = "Emprunt supprimé avec succès !";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
        $messageType = "error";
    }
}

// Traitement de la modification
if (isset($_POST['modifier']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE EMPRUNT SET Date_Emprunt = ?, Date_Retour = ? WHERE id = ?");
        $stmt->execute([
            $_POST['date_emprunt'],
            $_POST['date_retour'],
            $_POST['id']
        ]);
        $message = "Emprunt modifié avec succès !";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification : " . $e->getMessage();
        $messageType = "error";
    }
}

// Récupération des emprunts avec recherche
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    if (is_numeric($search)) {
        $whereClause = "WHERE a.id = ?";
        $params = [$search];
    } else {
        $whereClause = "WHERE (CONCAT(a.nom, ' ', a.prenom) LIKE ? OR a.nom LIKE ? OR a.prenom LIKE ?)";
        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam];
    }
}

$stmt = $pdo->prepare("SELECT 
                e.id,
                e.Date_Emprunt,
                e.Date_Retour,
                CONCAT(a.nom, ' ', a.prenom) as adherent_nom,
                a.id as adherent_id,
                p.Titre,
                p.Type,
                CASE 
                    WHEN e.Date_Emprunt > CURDATE() THEN 'Réservé'
                    WHEN e.Date_Emprunt <= CURDATE() AND e.Date_Retour > CURDATE() THEN 'En cours'
                    WHEN e.Date_Retour <= CURDATE() THEN 'Terminé'
                    ELSE 'Terminé'
                END AS statut
            FROM EMPRUNT e
            JOIN ADHERENT a ON e.ADHERENT_id = a.id
            JOIN PRODUIT p ON e.PRODUIT_id = p.id
            $whereClause
            ORDER BY e.Date_Emprunt DESC");
$stmt->execute($params);
$emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de l'emprunt sélectionné pour modification
$selectedEmprunt = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT 
                    e.id,
                    e.Date_Emprunt,
                    e.Date_Retour,
                    CONCAT(a.nom, ' ', a.prenom) as adherent_nom,
                    a.id as adherent_id,
                    p.Titre,
                    p.Type
                FROM EMPRUNT e
                JOIN ADHERENT a ON e.ADHERENT_id = a.id
                JOIN PRODUIT p ON e.PRODUIT_id = p.id
                WHERE e.id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $selectedEmprunt = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier/supprimer un emprunt - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .form-modifier {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .emprunt-list {
            margin-top: 20px;
            background-color: #fff8f8;
            padding: 15px;
            border-radius: 5px;
            max-height: 400px;
            overflow-y: auto;
        }
        .emprunt-item {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 1fr 1fr 1fr 1fr auto;
            gap: 10px;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            align-items: center;
            font-size: 12px;
        }
        .emprunt-item:hover {
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
        .emprunt-header {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 1fr 1fr 1fr 1fr auto;
            gap: 10px;
            font-weight: bold;
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-size: 12px;
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
        .status-active {
            color: green;
            font-weight: bold;
        }
        .status-termine {
            color: #666;
        }
        .status-reserve {
            color: #ff9500;
            font-weight: bold;
        }
        .readonly-field {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            border-radius: 4px;
            color: #6c757d;
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
                <h1>Modifier/supprimer un emprunt</h1>
            </div>

            <div class="main-content">
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de recherche -->
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Rechercher par adhérent (nom, prénom ou ID)..." 
                           class="search-input" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">Rechercher</button>
                    <?php if ($search): ?>
                        <a href="modifier-emprunt.php" class="cancel-btn">Effacer</a>
                    <?php endif; ?>
                </form>
                
                <!-- Liste des emprunts -->
                <div class="emprunt-list">
                    <div class="emprunt-header">
                        <span>ID Adhérent</span>
                        <span>Adhérent</span>
                        <span>Titre</span>
                        <span>Type</span>
                        <span>Date emprunt</span>
                        <span>Date retour</span>
                        <span>Statut</span>
                        <span>Actions</span>
                    </div>
                    
                    <?php if (empty($emprunts)): ?>
                        <p>Aucun emprunt trouvé.</p>
                    <?php else: ?>
                        <?php foreach ($emprunts as $emprunt): ?>
                            <div class="emprunt-item">
                                <span><?php echo htmlspecialchars($emprunt['adherent_id']); ?></span>
                                <span><?php echo htmlspecialchars($emprunt['adherent_nom']); ?></span>
                                <span><?php echo htmlspecialchars($emprunt['Titre']); ?></span>
                                <span><?php echo htmlspecialchars($emprunt['Type']); ?></span>
                                <span><?php echo date('d/m/Y', strtotime($emprunt['Date_Emprunt'])); ?></span>
                                <span><?php echo date('d/m/Y', strtotime($emprunt['Date_Retour'])); ?></span>
                                <span class="status-<?= 
                                    $emprunt['statut'] == 'En cours' ? 'active' : 
                                    ($emprunt['statut'] == 'Réservé' ? 'reserve' : 'termine')
                                ?>">
                                    <?php echo htmlspecialchars($emprunt['statut']); ?>
                                </span>
                                <div style="display: flex; gap: 5px;">
                                    <a href="?edit_id=<?php echo $emprunt['id']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                                       class="edit-btn">Modifier</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?');">
                                        <input type="hidden" name="id" value="<?php echo $emprunt['id']; ?>">
                                        <button type="submit" name="supprimer" class="delete-btn">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Formulaire de modification -->
                <?php if ($selectedEmprunt): ?>
                    <div class="form-container" style="margin-top: 20px;">
                        <h3>Modifier l'emprunt : <?php echo htmlspecialchars($selectedEmprunt['adherent_nom'] . ' - ' . $selectedEmprunt['Titre']); ?></h3>
                        
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedEmprunt['id']; ?>">
                            
                            <div class="form-modifier">
                                <div class="form-group">
                                    <label>Adhérent</label>
                                    <div class="readonly-field"><?php echo htmlspecialchars($selectedEmprunt['adherent_nom']); ?></div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Document</label>
                                    <div class="readonly-field"><?php echo htmlspecialchars($selectedEmprunt['Titre']); ?></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_emprunt">Date d'emprunt</label>
                                    <input type="date" id="date_emprunt" name="date_emprunt" class="form-control" 
                                           value="<?php echo $selectedEmprunt['Date_Emprunt']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_retour">Date de retour</label>
                                    <input type="date" id="date_retour" name="date_retour" class="form-control" 
                                           value="<?php echo $selectedEmprunt['Date_Retour']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Type d'emprunt</label>
                                    <div class="readonly-field"><?php echo htmlspecialchars($selectedEmprunt['Type']); ?></div>
                                </div>
                                
                                <div class="form-group">
                                    <label>ID Emprunt</label>
                                    <div class="readonly-field"><?php echo htmlspecialchars($selectedEmprunt['id']); ?></div>
                                </div>
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" name="modifier" class="modify-btn">Enregistrer les modifications</button>
                                <a href="modifier-emprunt.php<?php echo $search ? '?search=' . urlencode($search) : ''; ?>" 
                                   class="cancel-btn">Annuler</a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Validation des dates
        document.addEventListener('DOMContentLoaded', function() {
            const dateEmprunt = document.getElementById('date_emprunt');
            const dateRetour = document.getElementById('date_retour');
            
            if (dateEmprunt && dateRetour) {
                dateEmprunt.addEventListener('change', function() {
                    dateRetour.min = this.value;
                    if (dateRetour.value && dateRetour.value < this.value) {
                        dateRetour.value = this.value;
                    }
                });
                
                // Initialiser la contrainte au chargement
                if (dateEmprunt.value) {
                    dateRetour.min = dateEmprunt.value;
                }
            }
        });
    </script>
</body>
</html>