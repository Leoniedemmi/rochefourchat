<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$year = isset($_GET['year']) ? $_GET['year'] : '2025';
$category = isset($_GET['category']) ? $_GET['category'] : 'Toutes les catégories';
$month = isset($_GET['month']) ? $_GET['month'] : 'Tous les mois';

$whereConditions = [];
$params = [];

if ($year != 'Toutes les années') {
    $whereConditions[] = "YEAR(e.Date_Emprunt) = :year";
    $params[':year'] = $year;
}

if ($category != 'Toutes les catégories') {
    $whereConditions[] = "p.Type = :category";
    $params[':category'] = $category;
}

if ($month != 'Tous les mois') {
    $monthNumber = array_search($month, [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ]);
    if ($monthNumber) {
        $whereConditions[] = "MONTH(e.Date_Emprunt) = :month";
        $params[':month'] = $monthNumber;
    }
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

$sql = "SELECT p.Titre, p.Auteur, COUNT(*) as nb_emprunts 
        FROM EMPRUNT e 
        JOIN PRODUIT p ON e.PRODUIT_id = p.id 
        $whereClause
        GROUP BY p.id, p.Titre, p.Auteur 
        ORDER BY nb_emprunts DESC 
        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$topBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlStats = "SELECT 
    COUNT(DISTINCT e.id) as total_emprunts,
    COUNT(DISTINCT e.ADHERENT_id) as adherents_actifs,
    COUNT(DISTINCT p.id) as produits_empruntes
    FROM EMPRUNT e 
    JOIN PRODUIT p ON e.PRODUIT_id = p.id 
    $whereClause";

$stmtStats = $pdo->prepare($sqlStats);
$stmtStats->execute($params);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

$sqlYears = "SELECT DISTINCT YEAR(Date_Emprunt) as year FROM EMPRUNT ORDER BY year DESC";
$stmtYears = $pdo->prepare($sqlYears);
$stmtYears->execute();
$years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);

$sqlTypes = "SELECT DISTINCT Type FROM PRODUIT ORDER BY Type";
$stmtTypes = $pdo->prepare($sqlTypes);
$stmtTypes->execute();
$types = $stmtTypes->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style2.css">
    <title>Médiathèque de la Rochefourchet</title>
    <style>
        /* Styles spécifiques à la page statistiques */
        .stats-container {
            display: flex;
            gap: 20px;
        }
        .left-panel {
            flex: 1;
            background-color: #fff8f8;
            padding: 20px;
            border-radius: 5px;
        }
        .right-panel {
            flex: 1;
            background-color: #fff8f8;
            padding: 20px;
            border-radius: 5px;
        }
        .stats-section {
            margin-bottom: 15px;
        }
        .stats-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .stats-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }
        .submit-btn {
            background-color: #CE6A6B;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background-color: #442424;
        }
        .right-panel h3 {
            margin-bottom: 15px;
            color: #333;
        }
        .right-panel ul {
            list-style-type: none;
        }
        .right-panel ul li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .book-title {
            font-weight: bold;
        }
        .book-author {
            font-style: italic;
        }
        /* Nouvelles sections de statistiques */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;

        }
        .stat-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #CE6A6B;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
    </style>
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

        <div class="content">
            <div class="header">
                <h1>Médiathèque de la Rochefourchet</h1>
            </div>

            <div class="main-content">
                <h2>Statistiques</h2>
                
                <div class="stats-summary">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['total_emprunts'] ?></div>
                        <div class="stat-label">Total emprunts</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['adherents_actifs'] ?></div>
                        <div class="stat-label">Adhérents actifs</div>
                    </div>
                </div>
                
                <div class="stats-container">
                    <div class="left-panel">
                        <form method="GET" action="">
                            <div class="stats-section">
                                <label for="year">Année</label>
                                <select id="year" name="year" class="stats-select">
                                    <option value="Toutes les années" <?= $year == 'Toutes les années' ? 'selected' : '' ?>>Toutes les années</option>
                                    <?php foreach($years as $yearOption): ?>
                                        <option value="<?= $yearOption ?>" <?= $year == $yearOption ? 'selected' : '' ?>><?= $yearOption ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="stats-section">
                                <label for="category">Type de produit</label>
                                <select id="category" name="category" class="stats-select">
                                    <option value="Toutes les catégories" <?= $category == 'Toutes les catégories' ? 'selected' : '' ?>>Toutes les catégories</option>
                                    <?php foreach($types as $type): ?>
                                        <option value="<?= $type ?>" <?= $category == $type ? 'selected' : '' ?>><?= $type ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="stats-section">
                                <label for="month">Mois</label>
                                <select id="month" name="month" class="stats-select">
                                    <option value="Tous les mois" <?= $month == 'Tous les mois' ? 'selected' : '' ?>>Tous les mois</option>
                                    <option value="Janvier" <?= $month == 'Janvier' ? 'selected' : '' ?>>Janvier</option>
                                    <option value="Février" <?= $month == 'Février' ? 'selected' : '' ?>>Février</option>
                                    <option value="Mars" <?= $month == 'Mars' ? 'selected' : '' ?>>Mars</option>
                                    <option value="Avril" <?= $month == 'Avril' ? 'selected' : '' ?>>Avril</option>
                                    <option value="Mai" <?= $month == 'Mai' ? 'selected' : '' ?>>Mai</option>
                                    <option value="Juin" <?= $month == 'Juin' ? 'selected' : '' ?>>Juin</option>
                                    <option value="Juillet" <?= $month == 'Juillet' ? 'selected' : '' ?>>Juillet</option>
                                    <option value="Août" <?= $month == 'Août' ? 'selected' : '' ?>>Août</option>
                                    <option value="Septembre" <?= $month == 'Septembre' ? 'selected' : '' ?>>Septembre</option>
                                    <option value="Octobre" <?= $month == 'Octobre' ? 'selected' : '' ?>>Octobre</option>
                                    <option value="Novembre" <?= $month == 'Novembre' ? 'selected' : '' ?>>Novembre</option>
                                    <option value="Décembre" <?= $month == 'Décembre' ? 'selected' : '' ?>>Décembre</option>
                                </select>
                            </div>

                            <button type="submit" class="submit-btn">Chercher</button>
                        </form>
                    </div>

                    <div class="right-panel">
                        <h3>Top 5 des produits les plus empruntés</h3>
                        <ul>
                            <?php if (!empty($topBooks)): ?>
                                <?php foreach($topBooks as $index => $book): ?>
                                    <li>
                                        <span class="book-title"><?= htmlspecialchars($book['Titre']) ?></span> - 
                                        <span class="book-author"><?= htmlspecialchars($book['Auteur']) ?></span>
                                        <span style="color: #CE6A6B; font-weight: bold; float: right;"><?= $book['nb_emprunts'] ?> emprunts</span>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>Aucun emprunt trouvé pour les critères sélectionnés.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>