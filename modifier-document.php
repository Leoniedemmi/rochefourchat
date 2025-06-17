<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = '';
$messageType = '';

if (isset($_POST['supprimer']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM PRODUIT WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $message = "Document supprimé avec succès !";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
        $messageType = "error";
    }
}

if (isset($_POST['modifier']) && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE PRODUIT SET Titre = ?, Auteur = ?, Date_Parution = ?, Genre = ?, Type = ?, nombre_exemplaires = ?, Editeur = ? WHERE id = ?");
        $stmt->execute([
            $_POST['titre'],
            $_POST['auteur'],
            $_POST['date_parution'],
            $_POST['genre'],
            $_POST['type'],
            $_POST['nombre_exemplaires'],
            $_POST['editeur'],
            $_POST['id']
        ]);
        $message = "Document modifié avec succès !";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification : " . $e->getMessage();
        $messageType = "error";
    }
}

$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$auteur_filter = isset($_GET['auteur']) ? $_GET['auteur'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    $sql = "SELECT * FROM PRODUIT WHERE 1=1";
    $params = [];
    
    if (!empty($type_filter) && $type_filter != 'Tous') {
        $sql .= " AND Type = :type";
        $params[':type'] = $type_filter;
    }
    
    if (!empty($auteur_filter) && $auteur_filter != 'Tous') {
        $sql .= " AND Auteur = :auteur";
        $params[':auteur'] = $auteur_filter;
    }
    
    if (!empty($genre_filter) && $genre_filter != 'Tous') {
        $sql .= " AND Genre = :genre";
        $params[':genre'] = $genre_filter;
    }
    
    if (!empty($date_filter) && $date_filter != 'Toutes') {
        $sql .= " AND Date_Parution LIKE :date";
        $params[':date'] = $date_filter . '%';
    }
    
    if (!empty($search)) {
        $sql .= " AND (Titre LIKE :search OR Auteur LIKE :search OR Genre LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $sql .= " ORDER BY Titre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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

$selectedDocument = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM PRODUIT WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $selectedDocument = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier/supprimer un document - Médiathèque de la Rochefourchet</title>
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
                    <li class="menu-separator"></li>
                    <li><a href="consulter-documents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-documents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les documents</a></li>
                    <li><a href="ajouter-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-document.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un document</a></li>
                    <li><a href="modifier-document.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-document.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un document</a></li>
                    <li class="menu-separator"></li>
                    <li><a href="consulter-adherents.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-adherents.php') ? 'style="color: #442424;"' : '' ?>>Consulter les adhérents</a></li>
                    <li><a href="ajouter-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-adherent.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un adhérent</a></li>
                    <li><a href="modifier-adherent.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-adherent.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un adhérent</a></li>
                    <li class="menu-separator"></li>
                    <li><a href="consulter-emprunts.php" <?= (basename($_SERVER['PHP_SELF']) == 'consulter-emprunts.php') ? 'style="color: #442424;"' : '' ?>>Consulter les emprunts</a></li>
                    <li><a href="ajouter-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'ajouter-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Ajouter un emprunt</a></li>
                    <li><a href="modifier-emprunt.php" <?= (basename($_SERVER['PHP_SELF']) == 'modifier-emprunt.php') ? 'style="color: #442424;"' : '' ?>>Modifier/supprimer un emprunt</a></li>
                </ul>
            </nav>
        </div>

        <div class="content">
            <div class="header">
                <h1>Modifier/supprimer un document</h1>
            </div>

            <div class="main-content">
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <section class="search-bar">
                    <form method="GET" action="modifier-document.php" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                        <div class="search-filters">
                            <label for="search_input">Recherche :</label>
                            <input type="text" id="search_input" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par titre, auteur ou genre..." style="padding: 0.5rem; width: 300px; border: 1px solid #ccc;" title="Rechercher par titre, auteur ou genre">
                           
                            <label for="type_select">Type :</label>
                            <select id="type_select" name="type" style="padding: 0.5rem;" title="Filtrer par type de document">
                                <option value="">Tous les types</option>
                                <?php foreach ($stats_types as $type_stat): ?>
                                    <option value="<?= htmlspecialchars($type_stat['Type']) ?>" <?= $type_filter === $type_stat['Type'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type_stat['Type']) ?> (<?= $type_stat['nombre'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="auteur_select">Auteur :</label>
                            <select id="auteur_select" name="auteur" style="padding: 0.5rem;" title="Filtrer par auteur">
                                <option value="">Tous les auteurs</option>
                                <?php foreach ($auteurs as $auteur): ?>
                                    <option value="<?= htmlspecialchars($auteur) ?>" <?= $auteur_filter === $auteur ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($auteur) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="genre_select">Genre :</label>
                            <select id="genre_select" name="genre" style="padding: 0.5rem;" title="Filtrer par genre">
                                <option value="">Tous les genres</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= htmlspecialchars($genre) ?>" <?= $genre_filter === $genre ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($genre) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="date_select">Année :</label>
                            <select id="date_select" name="date" style="padding: 0.5rem;" title="Filtrer par année de parution">
                                <option value="">Toutes les années</option>
                                <?php foreach ($dates as $date): ?>
                                    <option value="<?= htmlspecialchars($date) ?>" <?= $date_filter === $date ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($date) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" style="background-color: #b35c5c; color: white; border: none; padding: 0.5rem 1rem; cursor: pointer;">
                                <span class="fas fa-search" aria-hidden="true"></span> Rechercher
                            </button>
                            
                            <?php if (!empty($search) || !empty($type_filter) || !empty($auteur_filter) || !empty($genre_filter) || !empty($date_filter)): ?>
                                <a href="modifier-document.php" class="cancel-btn" style="text-decoration: none; display: inline-block;">
                                    <span class="fas fa-times" aria-hidden="true"></span> Effacer les filtres
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (isset($_GET['edit_id'])): ?>
                            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($_GET['edit_id']) ?>">
                        <?php endif; ?>
                    </form>
                </section>
                
                <div class="results-count">
                    <?= count($documents) ?> document(s) trouvé(s)
                    <?php if (!empty($search) || !empty($type_filter) || !empty($auteur_filter) || !empty($genre_filter) || !empty($date_filter)): ?>
                        avec les filtres appliqués
                    <?php endif; ?>
                </div>
                
                <div class="document-list">
                    <div class="document-header">
                        <span>Titre</span>
                        <span>Auteur</span>
                        <span>Type</span>
                        <span>Genre</span>
                        <span>Exemplaires</span>
                        <span>Actions</span>
                    </div>
                    
                    <?php if (empty($documents)): ?>
                        <p style="text-align: center; padding: 20px; color: #666;">
                            <span class="fas fa-search" style="font-size: 2rem; display: block; margin-bottom: 10px;" aria-hidden="true"></span>
                            Aucun document trouvé avec les critères sélectionnés.
                        </p>
                    <?php else: ?>
                        <?php foreach ($documents as $document): ?>
                            <div class="document-item">
                                <span><?php echo htmlspecialchars($document['Titre']); ?></span>
                                <span><?php echo htmlspecialchars($document['Auteur']); ?></span>
                                <span><?php echo htmlspecialchars($document['Type']); ?></span>
                                <span><?php echo htmlspecialchars($document['Genre']); ?></span>
                                <span><?php echo htmlspecialchars($document['nombre_exemplaires']); ?></span>
                                <div style="display: flex; gap: 5px;">
                                    <?php
                                    $editParams = ['edit_id' => $document['id']];
                                    if (!empty($search)) $editParams['search'] = $search;
                                    if (!empty($type_filter)) $editParams['type'] = $type_filter;
                                    if (!empty($auteur_filter)) $editParams['auteur'] = $auteur_filter;
                                    if (!empty($genre_filter)) $editParams['genre'] = $genre_filter;
                                    if (!empty($date_filter)) $editParams['date'] = $date_filter;
                                    $editUrl = '?' . http_build_query($editParams);
                                    ?>
                                    <a href="<?php echo $editUrl; ?>" class="edit-btn">Modifier</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                        <input type="hidden" name="id" value="<?php echo $document['id']; ?>">
                                        <button type="submit" name="supprimer" class="delete-btn">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if ($selectedDocument): ?>
                    <div class="form-container" style="margin-top: 20px;">
                        <h3>Modifier le document : <?php echo htmlspecialchars($selectedDocument['Titre']); ?></h3>
                        
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedDocument['id']; ?>">
                            
                            <div class="form-modifier">
                                <div class="form-group">
                                    <label for="titre">Titre</label>
                                    <input type="text" id="titre" name="titre" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedDocument['Titre']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="auteur">Auteur</label>
                                    <input type="text" id="auteur" name="auteur" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedDocument['Auteur']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_parution">Date de parution</label>
                                    <input type="text" id="date_parution" name="date_parution" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedDocument['Date_Parution']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="genre">Genre</label>
                                    <input type="text" id="genre" name="genre" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedDocument['Genre']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select id="type" name="type" class="form-control" required>
                                        <option value="Livre" <?php echo $selectedDocument['Type'] == 'Livre' ? 'selected' : ''; ?>>Livre</option>
                                        <option value="DVD" <?php echo $selectedDocument['Type'] == 'DVD' ? 'selected' : ''; ?>>DVD</option>
                                        <option value="CD" <?php echo $selectedDocument['Type'] == 'CD' ? 'selected' : ''; ?>>CD</option>
                                        <option value="VHS" <?php echo $selectedDocument['Type'] == 'VHS' ? 'selected' : ''; ?>>VHS</option>
                                        <option value="Vinyle" <?php echo $selectedDocument['Type'] == 'Vinyle' ? 'selected' : ''; ?>>Vinyle</option>
                                        <option value="Cassette Audio" <?php echo $selectedDocument['Type'] == 'Cassette Audio' ? 'selected' : ''; ?>>Cassette Audio</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nombre_exemplaires">Nombre d'exemplaires</label>
                                    <input type="number" id="nombre_exemplaires" name="nombre_exemplaires" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedDocument['nombre_exemplaires']); ?>" min="1" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="editeur">Éditeur</label>
                                    <input type="text" id="editeur" name="editeur" class="form-control" 
                                           value="<?php echo htmlspecialchars($selectedDocument['Editeur']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" name="modifier" class="modify-btn">Enregistrer les modifications</button>
                                <?php
                                $cancelParams = [];
                                if (!empty($search)) $cancelParams['search'] = $search;
                                if (!empty($type_filter)) $cancelParams['type'] = $type_filter;
                                if (!empty($auteur_filter)) $cancelParams['auteur'] = $auteur_filter;
                                if (!empty($genre_filter)) $cancelParams['genre'] = $genre_filter;
                                if (!empty($date_filter)) $cancelParams['date'] = $date_filter;
                                $cancelUrl = 'modifier-document.php' . (!empty($cancelParams) ? '?' . http_build_query($cancelParams) : '');
                                ?>
                                <a href="<?php echo $cancelUrl; ?>" class="cancel-btn">Annuler</a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>