<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un emprunt - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .emprunt-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }
        .emprunt-section {
            background-color: #fff8f8;
            padding: 15px;
            border-radius: 5px;
        }
        .btn-container {
            grid-column: span 3;
            text-align: center;
            margin-top: 20px;
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
                <h1>Ajouter un emprunt</h1>
            </div>

            <div class="main-content">
                <form class="emprunt-container">
                    <div class="emprunt-section">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" class="form-control">
                        </div>
                    </div>
                    
                    <div class="emprunt-section">
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" id="titre" class="form-control">
                        </div>
                    </div>
                    
                    <div class="emprunt-section">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" class="form-control">
                                <option>Livre</option>
                                <option>DVD</option>
                                <option>CD</option>
                                <option>Revue</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="emprunt-section">
                        <div class="form-group">
                            <label for="date-emprunt">Date d'emprunt</label>
                            <input type="date" id="date-emprunt" class="form-control">
                        </div>
                    </div>
                    
                    <div class="emprunt-section">
                        <div class="form-group">
                            <label for="date-retour">Date de retour</label>
                            <input type="date" id="date-retour" class="form-control">
                        </div>
                    </div>
                    
                    <div class="emprunt-section">
                        <div class="form-group">
                            <label for="oui">Réservé</label>
                            <input type="radio" id="oui" name="question9" value="oui">
                        </div>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="submit-btn">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>