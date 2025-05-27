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
                    <li><a href="index.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/index.php') ? 'color: #442424;' : '' ?>">Statistiques</a></li>
                    <li><a href="consulter-documents.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/consulter-documents.php') ? 'color: #442424;' : '' ?>">Consulter les documents</a></li>
                    <li><a href="ajouter-document.php"style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/ajouter-document.php') ? 'color: #442424;' : '' ?>" >Ajouter un document</a></li>
                    <li><a href="modifier-document.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/modifier-document.php') ? 'color: #442424;' : '' ?>">Modifier/supprimer un document</a></li>
                    <li><a href="consulter-adherents.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/consulter-adherents.php') ? 'color: #442424;' : '' ?>">Consulter les adhérents</a></li>
                    <li><a href="ajouter-adherent.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/ajouter-adherent.php') ? 'color: #442424;' : '' ?>">Ajouter un adhérent</a></li>
                    <li><a href="modifier-adherent.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/modifier-adherent.php') ? 'color: #442424;' : '' ?>">Modifier/supprimer un adhérent</a></li>
                    <li><a href="consulter-emprunts.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/consulter-emprunts.php') ? 'color: #442424;' : '' ?>">Consulter les emprunts</a></li>
                    <li><a href="ajouter-emprunt.php"style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/ajouter-emprunt.php') ? 'color: #442424;' : '' ?>">Ajouter un emprunt</a></li>
                    <li><a href="modifier-emprunt.php" style="<?= ($_SERVER['PHP_SELF'] == '/rochefourchat/modifier-emprunt.php') ? 'color: #442424;' : '' ?>">Modifier/supprimer un emprunt</a></li>
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