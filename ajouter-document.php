<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un document - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="container">
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

        <div class="content">
            <div class="header">
                <h1>Ajouter un document</h1>
            </div>

            <div class="main-content">
                <form class="form-container">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" id="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" class="form-control">
                            <option>Livre</option>
                            <option>DVD</option>
                            <option>CD</option>
                            <option>Revue</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="author">Auteur</label>
                        <input type="text" id="author" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="year">Année</label>
                        <input type="text" id="year" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="date-acquisition">Date de parution</label>
                        <input type="date" id="date-acquisition" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <select id="genre" class="form-control">
                            <option>Roman</option>
                            <option>Science-fiction</option>
                            <option>Policier</option>
                            <option>Biographie</option>
                            <option>Histoire</option>
                            <option>Jeunesse</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="grid-column: span 2; text-align: center; margin-top: 20px;">
                        <button type="submit" class="submit-btn">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>