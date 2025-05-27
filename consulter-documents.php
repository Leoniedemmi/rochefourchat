<?php
// Inclure le fichier de configuration de la base de données
require_once 'config/database.php';

// Initialiser les variables de filtre
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$auteur_filter = isset($_GET['auteur']) ? $_GET['auteur'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Construire la requête SQL avec les filtres
    $sql = "SELECT p.*, 
                   CASE 
                       WHEN e.PRODUIT_id IS NOT NULL AND e.Date_Retour IS NULL THEN 'Emprunté'
                       ELSE 'Disponible'
                   END as statut,
                   MAX(COALESCE(e.Date_Emprunt, NOW())) as derniere_consultation,
                   COUNT(CASE WHEN e.Date_Retour IS NULL THEN 1 END) as nb_empruntes
            FROM PRODUIT p
            LEFT JOIN EMPRUNT e ON p.id = e.PRODUIT_id
            WHERE 1=1";
    
    $params = [];
    
    // Ajouter les conditions de filtre
    if (!empty($type_filter) && $type_filter != 'Tous') {
        $sql .= " AND p.type = :type";
        $params[':type'] = $type_filter;
    }
    
    if (!empty($auteur_filter) && $auteur_filter != 'Tous') {
        $sql .= " AND p.auteur = :auteur";
        $params[':auteur'] = $auteur_filter;
    }
    
    if (!empty($genre_filter) && $genre_filter != 'Tous') {
        $sql .= " AND p.genre = :genre";
        $params[':genre'] = $genre_filter;
    }
    
    if (!empty($date_filter) && $date_filter != 'Toutes') {
        $sql .= " AND p.Date_Parution LIKE :date";
        $params[':date'] = $date_filter . '%';
    }
    
    if (!empty($search)) {
        $sql .= " AND (p.titre LIKE :search OR p.auteur LIKE :search OR p.genre LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $sql .= " GROUP BY p.id";
    
    // Filtrer par statut après le GROUP BY
    if (!empty($statut_filter) && $statut_filter != 'Tous') {
        if ($statut_filter == 'Disponible') {
            $sql .= " HAVING (p.nombre_exemplaires - COUNT(CASE WHEN e.Date_Retour IS NULL THEN 1 END)) > 0";
        } elseif ($statut_filter == 'Emprunté') {
            $sql .= " HAVING COUNT(CASE WHEN e.Date_Retour IS NULL THEN 1 END) > 0";
        }
    }
    
    $sql .= " ORDER BY p.titre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recalculer le statut en tenant compte du nombre d'exemplaires
    foreach ($documents as &$doc) {
        $disponibles = $doc['nombre_exemplaires'] - $doc['nb_empruntes'];
        if ($disponibles > 0) {
            $doc['statut'] = 'Disponible (' . $disponibles . '/' . $doc['nombre_exemplaires'] . ')';
        } else {
            $doc['statut'] = 'Emprunté (' . $doc['nb_empruntes'] . '/' . $doc['nombre_exemplaires'] . ')';
        }
    }
    
    // Récupérer les données pour les filtres
    $types = $pdo->query("SELECT DISTINCT type FROM PRODUIT WHERE type IS NOT NULL AND type != '' ORDER BY type")->fetchAll(PDO::FETCH_COLUMN);
    $auteurs = $pdo->query("SELECT DISTINCT auteur FROM PRODUIT WHERE auteur IS NOT NULL AND auteur != '' ORDER BY auteur")->fetchAll(PDO::FETCH_COLUMN);
    $genres = $pdo->query("SELECT DISTINCT genre FROM PRODUIT WHERE genre IS NOT NULL AND genre != '' ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);
    $dates = $pdo->query("SELECT DISTINCT SUBSTRING(Date_Parution, 1, 4) as annee FROM PRODUIT WHERE Date_Parution IS NOT NULL AND Date_Parution != '' ORDER BY annee DESC")->fetchAll(PDO::FETCH_COLUMN);
    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $documents = [];
    $types = $auteurs = $genres = $dates = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les documents - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-item {
            flex: 1;
            min-width: 120px;
        }
        .filter-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .status-disponible { color: green; font-weight: bold; }
        .status-emprunte { color: red; font-weight: bold; }
        .status-reserve { color: orange; font-weight: bold; }
        .results-count {
            margin-bottom: 15px;
            font-style: italic;
            color: #666;
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
                    <li><a href="consulter-documents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-documents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les documents</a></li>
                    <li><a href="ajouter-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-document.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un document</a></li>
                    <li><a href="modifier-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-document.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un document</a></li>
                    <li><a href="consulter-adherents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-adherents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les adhérents</a></li>
                    <li><a href="ajouter-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-adherent.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un adhérent</a></li>
                    <li><a href="modifier-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-adherent.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un adhérent</a></li>
                    <li><a href="consulter-emprunts.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-emprunts.php') ? 'style="color: #442424;"' : '' ?>>Consulter les emprunts</a></li>
                    <li><a href="ajouter-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un emprunt</a></li>
                    <li><a href="modifier-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un emprunt</a></li>
                </ul>
            </nav>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            <div class="header">
                <h1>Consulter les documents</h1>
            </div>

            <div class="main-content">
                <form method="GET" action="">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Recherche par titre, auteur ou genre..." 
                               class="search-input" value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="search-btn">Rechercher</button>
                    </div>

                    <div class="filter-container">
                        <div class="filter-item">
                            <label>Type</label>
                            <select name="type" class="filter-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" 
                                            <?= ($type_filter == $type) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-item">
                            <label>Auteur</label>
                            <select name="auteur" class="filter-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <?php foreach ($auteurs as $auteur): ?>
                                    <option value="<?= htmlspecialchars($auteur) ?>" 
                                            <?= ($auteur_filter == $auteur) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($auteur) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-item">
                            <label>Genre</label>
                            <select name="genre" class="filter-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= htmlspecialchars($genre) ?>" 
                                            <?= ($genre_filter == $genre) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($genre) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-item">
                            <label>Date</label>
                            <select name="date" class="filter-select" onchange="this.form.submit()">
                                <option value="">Toutes</option>
                                <?php foreach ($dates as $date): ?>
                                    <option value="<?= htmlspecialchars($date) ?>" 
                                            <?= ($date_filter == $date) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($date) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-item">
                            <label>Statut</label>
                            <select name="statut" class="filter-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <option value="Disponible" <?= ($statut_filter == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                                <option value="Emprunté" <?= ($statut_filter == 'Emprunté') ? 'selected' : '' ?>>Emprunté</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="results-count">
                    <?= count($documents) ?> document(s) trouvé(s)
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Genre</th>
                            <th>Date Parution</th>
                            <th>Support</th>
                            <th>Statut</th>
                            <th>Dernière consultation</th>
                        </tr>
                    </thead>
                    <tbody>
                                                    <?php if (empty($documents)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                                    Aucun document trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($documents as $document): ?>
                                <tr>
                                    <td><?= htmlspecialchars($document['type']) ?></td>
                                    <td><?= htmlspecialchars($document['titre']) ?></td>
                                    <td><?= htmlspecialchars($document['auteur']) ?></td>
                                    <td><?= htmlspecialchars($document['genre']) ?></td>
                                    <td><?= htmlspecialchars($document['Date_Parution']) ?></td>
                                    <td><?= htmlspecialchars($document['Support']) ?></td>
                                    <td>
                                        <span class="status-<?= (strpos($document['statut'], 'Disponible') !== false) ? 'disponible' : 'emprunte' ?>">
                                            <?= htmlspecialchars($document['statut']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($document['derniere_consultation'])) ?></td>
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