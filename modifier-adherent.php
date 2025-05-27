<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier/supprimer un adhérent - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .form-modifier {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .document-list {
            margin-top: 20px;
            background-color: #fff8f8;
            padding: 15px;
            border-radius: 5px;
        }
        .document-item {
            margin-bottom: 10px;
            padding: 5px;
            border-bottom: 1px solid #eee;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .modify-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
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
                <h1>Modifier/supprimer un adhérent</h1>
            </div>

            <div class="main-content">
                <div class="search-box">
                    <input type="text" placeholder="Rechercher un adhérent..." class="search-input">
                    <button class="search-btn">Rechercher</button>
                </div>
                
                <div class="document-list">
                    <div class="document-item">
                        <p><strong>Adhérent 1</strong></p>
                    </div>
                    <div class="document-item">
                        <p><strong>Adhérent 2</strong></p>
                    </div>
                    <div class="document-item">
                        <p><strong>Adhérent 3</strong></p>
                    </div>
                </div>

                <div class="form-container" style="margin-top: 20px;">
                    <div class="form-modifier">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" class="form-control" value="nom">
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" class="form-control" value="prenom">
                        </div>
                        <div class="form-group">
                            <label for="prenom">mail</label>
                            <input type="text" id="mail" class="form-control" value="mail">
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button class="modify-btn">Modifier</button>
                        <button class="delete-btn">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>