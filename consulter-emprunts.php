<?php
// Inclure le fichier de configuration de la base de données
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
// Initialiser les variables de filtre
$adherent_filter = isset($_GET['adherent']) ? $_GET['adherent'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Construire la requête SQL avec les filtres - LOGIQUE CORRIGÉE
    $sql = "SELECT 
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
                END AS statut,
                CASE 
                    WHEN e.Date_Emprunt > CURDATE() THEN 3
                    WHEN e.Date_Emprunt <= CURDATE() AND e.Date_Retour > CURDATE() THEN 2
                    WHEN e.Date_Retour <= CURDATE() THEN 1
                    ELSE 1
                END AS statut_ordre
            FROM EMPRUNT e
            JOIN ADHERENT a ON e.ADHERENT_id = a.id
            JOIN PRODUIT p ON e.PRODUIT_id = p.id
            WHERE 1=1";
    
    $params = [];
    
    // Filtrer par adhérent (ID ou nom)
    if (!empty($search)) {
        // Vérifier si c'est un ID (numérique) ou un nom
        if (is_numeric($search)) {
            $sql .= " AND a.id = :adherent_id";
            $params[':adherent_id'] = $search;
        } else {
            $sql .= " AND (CONCAT(a.nom, ' ', a.prenom) LIKE :search OR a.nom LIKE :search OR a.prenom LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
    }
    
    // Filtre par date d'emprunt
    if (!empty($date_filter) && $date_filter != 'Toutes') {
        switch ($date_filter) {
            case 'Cette semaine':
                $sql .= " AND e.Date_Emprunt >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
                break;
            case 'Ce mois':
                $sql .= " AND e.Date_Emprunt >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                break;
            case 'Ce trimestre':
                $sql .= " AND e.Date_Emprunt >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
                break;
        }
    }
    
    // Filtre par type de document
    if (!empty($type_filter) && $type_filter != 'Tous') {
        $sql .= " AND p.Type = :type";
        $params[':type'] = $type_filter;
    }
    
    // Filtre par statut - LOGIQUE CORRIGÉE
    if (!empty($statut_filter) && $statut_filter != 'Tous') {
        switch ($statut_filter) {
            case 'Réservé':
                $sql .= " AND e.Date_Emprunt > CURDATE()";
                break;
            case 'En cours':
                $sql .= " AND e.Date_Emprunt <= CURDATE() AND e.Date_Retour > CURDATE()";
                break;
            case 'Terminé':
                $sql .= " AND e.Date_Retour <= CURDATE()";
                break;
        }
    }
    
    $sql .= " ORDER BY statut_ordre DESC, e.Date_Emprunt DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les types de documents pour le filtre
    $types = $pdo->query("SELECT DISTINCT Type FROM PRODUIT WHERE Type IS NOT NULL AND Type != '' ORDER BY Type")->fetchAll(PDO::FETCH_COLUMN);
    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $emprunts = [];
    $types = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les emprunts - Médiathèque de la Rochefourchet</title>
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
                <h1>Consulter les emprunts</h1>
            </div>

            <div class="main-content">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Rechercher par adhérent (nom, prénom ou ID)..." 
                           class="search-input" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">Rechercher</button>
                    <?php if ($search): ?>
                        <a href="consulter-adherent.php" class="cancel-btn">Effacer</a>
                    <?php endif; ?>
                </form>

                <div class="results-count">
                    <?= count($emprunts) ?> emprunt(s) trouvé(s)
                    <?php if (!empty($search)): ?>
                        <?php if (is_numeric($search)): ?>
                            pour l'adhérent ID <?= htmlspecialchars($search) ?>
                        <?php else: ?>
                            pour "<?= htmlspecialchars($search) ?>"
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID Adhérent</th>
                            <th>Adhérent</th>
                            <th>Titre</th>
                            <th>Type d'emprunt</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($emprunts)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                                    Aucun emprunt trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($emprunts as $emprunt): ?>
                                <tr>
                                    <td><?= htmlspecialchars($emprunt['adherent_id']) ?></td>
                                    <td><?= htmlspecialchars($emprunt['adherent_nom']) ?></td>
                                    <td><?= htmlspecialchars($emprunt['Titre']) ?></td>
                                    <td><?= htmlspecialchars($emprunt['Type']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($emprunt['Date_Emprunt'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($emprunt['Date_Retour'])) ?></td>
                                    <td>
                                        <span class="status-<?= 
                                            $emprunt['statut'] == 'En cours' ? 'active' : 
                                            ($emprunt['statut'] == 'Réservé' ? 'reserve' : 'termine')
                                        ?>">
                                            <?= htmlspecialchars($emprunt['statut']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>