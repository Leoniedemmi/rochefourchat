<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$auteur_filter = isset($_GET['auteur']) ? $_GET['auteur'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    $sql = "SELECT 
                p.id,
                p.Titre,
                p.Auteur,
                p.Type,
                p.Support,
                p.Genre,
                p.Date_Parution,
                p.nombre_exemplaires,
                p.Editeur,
                (p.nombre_exemplaires - IFNULL(e.nombre_emprunts, 0)) AS exemplaires_disponibles,
                CASE
                    WHEN (p.nombre_exemplaires - IFNULL(e.nombre_emprunts, 0)) > 0 THEN 'Disponible'
                    ELSE 'Emprunté'
                END AS etat
            FROM 
                PRODUIT p
            LEFT JOIN (
                SELECT 
                    PRODUIT_id,
                    COUNT(*) AS nombre_emprunts
                FROM 
                    EMPRUNT
                WHERE 
                    Date_Retour IS NULL OR Date_Retour >= CURDATE()
                GROUP BY 
                    PRODUIT_id
            ) e ON p.id = e.PRODUIT_id
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($type_filter) && $type_filter != 'Tous') {
        $sql .= " AND p.Type = :type";
        $params[':type'] = $type_filter;
    }
    
    if (!empty($auteur_filter) && $auteur_filter != 'Tous') {
        $sql .= " AND p.Auteur = :auteur";
        $params[':auteur'] = $auteur_filter;
    }
    
    if (!empty($genre_filter) && $genre_filter != 'Tous') {
        $sql .= " AND p.Genre = :genre";
        $params[':genre'] = $genre_filter;
    }
    
    if (!empty($date_filter) && $date_filter != 'Toutes') {
        $sql .= " AND p.Date_Parution LIKE :date";
        $params[':date'] = $date_filter . '%';
    }
    
    if (!empty($search)) {
        $sql .= " AND (p.Titre LIKE :search OR p.Auteur LIKE :search OR p.Genre LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    if (!empty($statut_filter) && $statut_filter != 'Tous') {
        if ($statut_filter == 'Disponible') {
            $sql .= " HAVING (p.nombre_exemplaires - IFNULL(e.nombre_emprunts, 0)) > 0";
        } elseif ($statut_filter == 'Emprunté') {
            $sql .= " HAVING (p.nombre_exemplaires - IFNULL(e.nombre_emprunts, 0)) <= 0";
        }
    }
    
    $sql .= " ORDER BY p.Titre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($documents as &$doc) {
        $disponibles = $doc['exemplaires_disponibles'];
        $total = $doc['nombre_exemplaires'];
        
        if ($disponibles > 0) {
            $doc['statut_formate'] = 'Disponible (' . $disponibles . '/' . $total . ')';
        } else {
            $doc['statut_formate'] = 'Emprunté (0/' . $total . ')';
        }
    }
    
    $types = $pdo->query("SELECT DISTINCT Type FROM PRODUIT WHERE Type IS NOT NULL AND Type != '' ORDER BY Type")->fetchAll(PDO::FETCH_COLUMN);
    $auteurs = $pdo->query("SELECT DISTINCT Auteur FROM PRODUIT WHERE Auteur IS NOT NULL AND Auteur != '' ORDER BY Auteur")->fetchAll(PDO::FETCH_COLUMN);
    $genres = $pdo->query("SELECT DISTINCT Genre FROM PRODUIT WHERE Genre IS NOT NULL AND Genre != '' ORDER BY Genre")->fetchAll(PDO::FETCH_COLUMN);
    $dates = $pdo->query("SELECT DISTINCT SUBSTRING(Date_Parution, 1, 4) as annee FROM PRODUIT WHERE Date_Parution IS NOT NULL AND Date_Parution != '' ORDER BY annee DESC")->fetchAll(PDO::FETCH_COLUMN);
    
    $stats_types = $pdo->query("SELECT Type, COUNT(*) as nombre FROM PRODUIT WHERE Type IS NOT NULL AND Type != '' GROUP BY Type ORDER BY Type")->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $documents = [];
    $types = $auteurs = $genres = $dates = [];
    $stats_types = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les documents - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
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

        <div class="content">
            <div class="header">
                <h1>Consulter les documents</h1>
            </div>

            <div class="main-content">
                <section class="search-bar">
                    <form method="GET" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                        <div class="search-filters">
                            <label for="search" style="display: none;">Recherche</label>
                            <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par titre, auteur ou genre..." style="padding: 0.5rem; width: 300px; border: 1px solid #ccc;">
                           
                            <label for="type" style="display: none;">Type de document</label>
                            <select id="type" name="type" style="padding: 0.5rem;">
                                <option value="">Tous les types</option>
                                <?php foreach ($stats_types as $type_stat): ?>
                                    <option value="<?= htmlspecialchars($type_stat['Type']) ?>" <?= $type_filter === $type_stat['Type'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type_stat['Type']) ?> (<?= $type_stat['nombre'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="genre" style="display: none;">Genre</label>
                            <select id="genre" name="genre" style="padding: 0.5rem;">
                                <option value="">Tous les genres</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= htmlspecialchars($genre) ?>" <?= $genre_filter === $genre ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($genre) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="date" style="display: none;">Année de parution</label>
                            <select id="date" name="date" style="padding: 0.5rem;">
                                <option value="">Toutes les années</option>
                                <?php foreach ($dates as $date): ?>
                                    <option value="<?= htmlspecialchars($date) ?>" <?= $date_filter === $date ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($date) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="statut" style="display: none;">Statut</label>
                            <select id="statut" name="statut" style="padding: 0.5rem;">
                                <option value="">Tous les statuts</option>
                                <option value="Disponible" <?= ($statut_filter == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                                <option value="Emprunté" <?= ($statut_filter == 'Emprunté') ? 'selected' : '' ?>>Emprunté</option>
                            </select>

                            <button type="submit" style="background-color: #b35c5c; color: white; border: none; padding: 0.5rem 1rem; cursor: pointer;">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </div>
                    </form>
                </section>

                <?php if (!empty($search) || !empty($type_filter) || !empty($genre_filter) || !empty($date_filter) || !empty($statut_filter)): ?>
                <section class="search-results">
                    <?php if (!empty($search)): ?>
                        <h2>Résultats de recherche pour "<?= htmlspecialchars($search) ?>"</h2>
                    <?php else: ?>
                        <h2>Documents filtrés</h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($documents)): ?>
                        <p><?= count($documents) ?> résultat(s) trouvé(s)</p>
                        <div class="results-grid">
                            <?php foreach ($documents as $product): ?>
                                <div class="product-card">
                                    <div class="product-title"><?= htmlspecialchars($product['Titre']) ?></div>
                                    <div class="product-author">par <?= htmlspecialchars($product['Auteur']) ?></div>
                                    <div style="margin: 0.5rem 0;">
                                        <span class="product-type"><?= htmlspecialchars($product['Type']) ?></span>
                                        <span style="margin-left: 1rem; color: #666;"><?= htmlspecialchars($product['Date_Parution']) ?></span>
                                    </div>
                                    <div style="font-size: 0.9rem; color: #666;">
                                        Genre: <?= htmlspecialchars($product['Genre']) ?><br>
                                        Support: <?= htmlspecialchars($product['Support']) ?><br>
                                        Statut: <span class="status-<?= (strpos($product['statut_formate'], 'Disponible') !== false) ? 'disponible' : 'emprunte' ?>">
                                            <?= htmlspecialchars($product['statut_formate']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-results">
                            <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <p>Aucun résultat trouvé pour votre recherche.</p>
                            <p>Essayez avec d'autres mots-clés ou modifiez les filtres.</p>
                        </div>
                    <?php endif; ?>
                </section>
                <?php endif; ?>

                <?php if (empty($search) && empty($type_filter) && empty($genre_filter) && empty($date_filter) && empty($statut_filter)): ?>
                <div class="results-count">
                    <?= count($documents) ?> document(s) au total
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($documents)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                                    Aucun document trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($documents as $document): ?>
                                <tr>
                                    <td><?= htmlspecialchars($document['Type']) ?></td>
                                    <td><?= htmlspecialchars($document['Titre']) ?></td>
                                    <td><?= htmlspecialchars($document['Auteur']) ?></td>
                                    <td><?= htmlspecialchars($document['Genre']) ?></td>
                                    <td><?= htmlspecialchars($document['Date_Parution']) ?></td>
                                    <td><?= htmlspecialchars($document['Support']) ?></td>
                                    <td>
                                        <span class="status-<?= (strpos($document['statut_formate'], 'Disponible') !== false) ? 'disponible' : 'emprunte' ?>">
                                            <?= htmlspecialchars($document['statut_formate']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>