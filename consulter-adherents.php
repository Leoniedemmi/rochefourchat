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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$nom_filter = isset($_GET['nom']) ? $_GET['nom'] : '';
$prenom_filter = isset($_GET['prenom']) ? $_GET['prenom'] : '';

try {
    // Construire la requête SQL avec les filtres
    $sql = "SELECT a.*, 
                   COUNT(e.id) as nb_emprunts_actifs,
                   COUNT(CASE WHEN e.Date_Retour > CURDATE() THEN 1 END) as nb_emprunts_en_cours,
                   MAX(e.Date_Emprunt) as dernier_emprunt
            FROM ADHERENT a
            LEFT JOIN EMPRUNT e ON a.id = e.ADHERENT_id AND e.Date_Retour >= CURDATE()
            WHERE 1=1";
    
    $params = [];
    
    // Ajouter les conditions de filtre
    if (!empty($nom_filter) && $nom_filter != 'Tous') {
        $sql .= " AND a.Nom = :nom";
        $params[':nom'] = $nom_filter;
    }
    
    if (!empty($prenom_filter) && $prenom_filter != 'Tous') {
        $sql .= " AND a.Prenom = :prenom";
        $params[':prenom'] = $prenom_filter;
    }
    
    if (!empty($search)) {
        $sql .= " AND (a.Nom LIKE :search OR a.Prenom LIKE :search OR a.Mail LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $sql .= " GROUP BY a.id ORDER BY a.Nom, a.Prenom";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $adherents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les données pour les filtres
    $noms = $pdo->query("SELECT DISTINCT Nom FROM ADHERENT WHERE Nom IS NOT NULL AND Nom != '' ORDER BY Nom")->fetchAll(PDO::FETCH_COLUMN);
    $prenoms = $pdo->query("SELECT DISTINCT Prenom FROM ADHERENT WHERE Prenom IS NOT NULL AND Prenom != '' ORDER BY Prenom")->fetchAll(PDO::FETCH_COLUMN);
    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $adherents = [];
    $noms = $prenoms = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les adhérents - Médiathèque de la Rochefourchet</title>
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
                <h1>Consulter les adhérents</h1>
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
                    <?= count($adherents) ?> adhérent(s) trouvé(s)
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Emprunts en cours</th>
                            <th>Dernier emprunt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($adherents)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                                    Aucun adhérent trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($adherents as $adherent): ?>
                                <tr>
                                    <td><?= htmlspecialchars($adherent['id']) ?></td>
                                    <td><?= htmlspecialchars($adherent['Nom']) ?></td>
                                    <td><?= htmlspecialchars($adherent['Prenom']) ?></td>
                                    <td class="email-cell" title="<?= htmlspecialchars($adherent['Mail']) ?>">
                                        <?= htmlspecialchars($adherent['Mail']) ?>
                                    </td>
                                    <td class="emprunts-count <?= ($adherent['nb_emprunts_en_cours'] > 0) ? 'emprunts-actifs' : 'emprunts-zero' ?>">
                                        <?= htmlspecialchars($adherent['nb_emprunts_en_cours']) ?>
                                    </td>
                                    <td>
                                        <?php if ($adherent['dernier_emprunt']): ?>
                                            <?= date('d/m/Y', strtotime($adherent['dernier_emprunt'])) ?>
                                        <?php else: ?>
                                            <span style="color: #999;">Aucun emprunt</span>
                                        <?php endif; ?>
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