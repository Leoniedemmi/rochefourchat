<?php
// Inclure le fichier de configuration de la base de données
require_once 'config/database.php';

// Initialiser les variables de filtre
$adherent_filter = isset($_GET['adherent']) ? $_GET['adherent'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';


try {
    // Construire la requête SQL avec les filtres
    $sql = "SELECT 
                a.id,
                a.prenom,
                a.nom,
                a.Mail
            FROM ADHERENT a
            WHERE 1=1";
    
    $params = [];
    
    // Filtrer par recherche (nom, prénom ou ID)
    if (!empty($search)) {
        // Vérifier si c'est un ID (numérique) ou un nom/prénom
        if (is_numeric($search)) {
            $sql .= " AND a.id = :adherent_id";
            $params[':adherent_id'] = $search;
        } else {
            $sql .= " AND (CONCAT(a.nom, ' ', a.prenom) LIKE :search OR a.nom LIKE :search OR a.prenom LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
    }
    
    $sql .= " ORDER BY a.id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $adherents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $adherents = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les adhérents - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .results-count {
            margin-bottom: 15px;
            font-style: italic;
            color: #666;
        }
        .search-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
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
                <h1>Consulter les adhérents</h1>
            </div>

            <div class="main-content">
                <form method="GET" action="">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Recherche par adhérent (nom, prénom ou ID)..." 
                               class="search-input" value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="search-btn">Rechercher</button>
                    </div>
                    <div class="search-hint">
                        Tapez un nom, prénom ou l'ID de l'adhérent (ex: "Dupont", "Jean", ou "5")
                    </div>
                </form>

                <div class="results-count">
                    <?= count($adherents) ?> adhérent(s) trouvé(s)
                    <?php if (!empty($search)): ?>
                        <?php if (is_numeric($search)): ?>
                            pour l'ID <?= htmlspecialchars($search) ?>
                        <?php else: ?>
                            pour "<?= htmlspecialchars($search) ?>"
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Adresse mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($adherents)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px; color: #666;">
                                    Aucun adhérent trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($adherents as $adherent): ?>
                                <tr>
                                    <td><?= htmlspecialchars($adherent['id']) ?></td>
                                    <td><?= htmlspecialchars($adherent['prenom']) ?></td>
                                    <td><?= htmlspecialchars($adherent['nom']) ?></td>
                                    <td><?= htmlspecialchars($adherent['Mail']) ?></td>
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