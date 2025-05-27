<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier/supprimer un emprunt - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .form-modifier {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .emprunt-list {
            margin-top: 20px;
            background-color: #fff8f8;
            padding: 15px;
            border-radius: 5px;
        }
        .emprunt-item {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 10px;
            padding: 5px 0;
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
        .status-actif {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-retard {
            color: #f44336;
            font-weight: bold;
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
                <h1>Modifier/supprimer un emprunt</h1>
            </div>

            <div class="main-content">
                <div class="search-box">
                    <input type="text" placeholder="Rechercher un emprunt..." class="search-input">
                    <button class="search-btn">Rechercher</button>
                </div>
                
                <div class="emprunt-list">
                    <div class="emprunt-item">
                        <span>Emprunt 1</span>
                        <span>Adhérent: Martin Dupont</span>
                        <span>Document: Harry Potter</span>
                        <span>Date emprunt: 15/01/2024</span>
                        <span class="status-actif">Statut: Actif</span>
                    </div>
                    <div class="emprunt-item">
                        <span>Emprunt 2</span>
                        <span>Adhérent: Sophie Bernard</span>
                        <span>Document: Le Seigneur des Anneaux</span>
                        <span>Date emprunt: 10/01/2024</span>
                        <span class="status-retard">Statut: En retard</span>
                    </div>
                    <div class="emprunt-item">
                        <span>Emprunt 3</span>
                        <span>Adhérent: Pierre Moreau</span>
                        <span>Document: Inception</span>
                        <span>Date emprunt: 20/01/2024</span>
                        <span class="status-actif">Statut: Actif</span>
                    </div>
                </div>

                <div class="form-container" style="margin-top: 20px;">
                    <div class="form-modifier">
                        <div class="form-group">
                            <label for="adherent">Adhérent</label>
                            <select id="adherent" class="form-control">
                                <option selected>Martin Dupont</option>
                                <option>Sophie Bernard</option>
                                <option>Pierre Moreau</option>
                                <option>Marie Dubois</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="document">Document</label>
                            <select id="document" class="form-control">
                                <option selected>Harry Potter et la coupe de feu</option>
                                <option>Le Seigneur des Anneaux</option>
                                <option>Inception</option>
                                <option>1984</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="date-emprunt">Date d'emprunt</label>
                            <input type="date" id="date-emprunt" class="form-control" value="2024-01-15">
                        </div>
                        
                        <div class="form-group">
                            <label for="date-retour">Date de retour prévue</label>
                            <input type="date" id="date-retour" class="form-control" value="2024-02-15">
                        </div>
                        
                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <select id="statut" class="form-control">
                                <option selected>Actif</option>
                                <option>Rendu</option>
                                <option>En retard</option>
                                <option>Perdu</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaires">Commentaires</label>
                            <textarea id="commentaires" class="form-control" rows="3" placeholder="Commentaires optionnels..."></textarea>
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