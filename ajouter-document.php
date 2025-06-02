
<?php
// Configuration de la base de données
require_once 'config/database.php';

$message = '';
$messageType = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupération des données du formulaire
        $titre = trim($_POST['title']);
        $type = $_POST['type'];
        $auteur = trim($_POST['author']);
        $annee = trim($_POST['year']);
        $dateParution = $_POST['date-acquisition'];
        $genre = $_POST['genre'];
        $editeur = trim($_POST['editeur']);
        $nombreExemplaires = (int)$_POST['nombre_exemplaires'];
        
        // Validation des données
        if (empty($titre) || empty($auteur) || empty($annee) || empty($editeur) || $nombreExemplaires <= 0) {
            throw new Exception("Tous les champs obligatoires doivent être renseignés.");
        }
        
        // Détermination du support basé sur le type
        $support = $type;
        
        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO PRODUIT (Type, Titre, Auteur, Date_Parution, Genre, Support, nombre_exemplaires, Editeur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$type, $titre, $auteur, $annee, $genre, $support, $nombreExemplaires, $editeur]);
        
        $message = "Document ajouté avec succès !";
        $messageType = 'success';
        
        // Réinitialisation du formulaire
        $_POST = array();
        
    } catch (PDOException $e) {
        $message = "Erreur de base de données : " . $e->getMessage();
        $messageType = 'error';
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un document - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                position: relative;
            }
            
            .form-container {
                grid-template-columns: 1fr;
            }
            
            .submit-btn {
                grid-column: span 1;
            }
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
                <h1>Ajouter un document</h1>
            </div>

            <div class="main-content">
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form-container">
                    <div class="form-group">
                        <label for="title">Titre <span class="required">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="type">Type <span class="required">*</span></label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Livre" <?php echo (($_POST['type'] ?? '') === 'Livre') ? 'selected' : ''; ?>>Livre</option>
                            <option value="DVD" <?php echo (($_POST['type'] ?? '') === 'DVD') ? 'selected' : ''; ?>>DVD</option>
                            <option value="CD" <?php echo (($_POST['type'] ?? '') === 'CD') ? 'selected' : ''; ?>>CD</option>
                            <option value="VHS" <?php echo (($_POST['type'] ?? '') === 'VHS') ? 'selected' : ''; ?>>VHS</option>
                            <option value="Vinyle" <?php echo (($_POST['type'] ?? '') === 'Vinyle') ? 'selected' : ''; ?>>Vinyle</option>
                            <option value="Cassette Audio" <?php echo (($_POST['type'] ?? '') === 'Cassette Audio') ? 'selected' : ''; ?>>Cassette Audio</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="author">Auteur <span class="required">*</span></label>
                        <input type="text" id="author" name="author" class="form-control" required 
                               value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="year">Année <span class="required">*</span></label>
                        <input type="text" id="year" name="year" class="form-control" required 
                               pattern="[0-9]{4}" title="Format: YYYY"
                               value="<?php echo htmlspecialchars($_POST['year'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="date-acquisition">Date de parution</label>
                        <input type="date" id="date-acquisition" name="date-acquisition" class="form-control"
                               value="<?php echo htmlspecialchars($_POST['date-acquisition'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre" class="form-control">
                            <option value="">Sélectionner un genre</option>
                            <option value="Roman" <?php echo (($_POST['genre'] ?? '') === 'Roman') ? 'selected' : ''; ?>>Roman</option>
                            <option value="Science-fiction" <?php echo (($_POST['genre'] ?? '') === 'Science-fiction') ? 'selected' : ''; ?>>Science-fiction</option>
                            <option value="Policier" <?php echo (($_POST['genre'] ?? '') === 'Policier') ? 'selected' : ''; ?>>Policier</option>
                            <option value="Biographie" <?php echo (($_POST['genre'] ?? '') === 'Biographie') ? 'selected' : ''; ?>>Biographie</option>
                            <option value="Histoire" <?php echo (($_POST['genre'] ?? '') === 'Histoire') ? 'selected' : ''; ?>>Histoire</option>
                            <option value="Jeunesse" <?php echo (($_POST['genre'] ?? '') === 'Jeunesse') ? 'selected' : ''; ?>>Jeunesse</option>
                            <option value="Drame" <?php echo (($_POST['genre'] ?? '') === 'Drame') ? 'selected' : ''; ?>>Drame</option>
                            <option value="Action" <?php echo (($_POST['genre'] ?? '') === 'Action') ? 'selected' : ''; ?>>Action</option>
                            <option value="Comédie" <?php echo (($_POST['genre'] ?? '') === 'Comédie') ? 'selected' : ''; ?>>Comédie</option>
                            <option value="Animation" <?php echo (($_POST['genre'] ?? '') === 'Animation') ? 'selected' : ''; ?>>Animation</option>
                            <option value="Thriller" <?php echo (($_POST['genre'] ?? '') === 'Thriller') ? 'selected' : ''; ?>>Thriller</option>
                            <option value="Rock" <?php echo (($_POST['genre'] ?? '') === 'Rock') ? 'selected' : ''; ?>>Rock</option>
                            <option value="Pop" <?php echo (($_POST['genre'] ?? '') === 'Pop') ? 'selected' : ''; ?>>Pop</option>
                            <option value="Hip-Hop" <?php echo (($_POST['genre'] ?? '') === 'Hip-Hop') ? 'selected' : ''; ?>>Hip-Hop</option>
                            <option value="Jazz" <?php echo (($_POST['genre'] ?? '') === 'Jazz') ? 'selected' : ''; ?>>Jazz</option>
                            <option value="Classique" <?php echo (($_POST['genre'] ?? '') === 'Classique') ? 'selected' : ''; ?>>Classique</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="editeur">Éditeur <span class="required">*</span></label>
                        <input type="text" id="editeur" name="editeur" class="form-control" required 
                               value="<?php echo htmlspecialchars($_POST['editeur'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="nombre_exemplaires">Nombre d'exemplaires <span class="required">*</span></label>
                        <input type="number" id="nombre_exemplaires" name="nombre_exemplaires" class="form-control" 
                               min="1" required value="<?php echo htmlspecialchars($_POST['nombre_exemplaires'] ?? '1'); ?>">
                    </div>
                    
                    <button type="submit" class="submit-btn">Ajouter le document</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validation côté client
        document.querySelector('form').addEventListener('submit', function(e) {
            const titre = document.getElementById('title').value.trim();
            const type = document.getElementById('type').value;
            const auteur = document.getElementById('author').value.trim();
            const annee = document.getElementById('year').value.trim();
            const editeur = document.getElementById('editeur').value.trim();
            const nombreExemplaires = document.getElementById('nombre_exemplaires').value;

            if (!titre || !type || !auteur || !annee || !editeur || !nombreExemplaires) {
                alert('Veuillez remplir tous les champs obligatoires.');
                e.preventDefault();
                return false;
            }

            if (!/^\d{4}$/.test(annee)) {
                alert('L\'année doit être au format YYYY (ex: 2023)');
                e.preventDefault();
                return false;
            }

            if (parseInt(nombreExemplaires) < 1) {
                alert('Le nombre d\'exemplaires doit être au moins de 1');
                e.preventDefault();
                return false;
            }
        });

        // Auto-hide success message after 5 seconds
        setTimeout(function() {
            const successMessage = document.querySelector('.message.success');
            if (successMessage) {
                successMessage.style.opacity = '0';
                successMessage.style.transition = 'opacity 0.5s';
                setTimeout(() => successMessage.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>