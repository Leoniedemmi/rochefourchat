<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les documents - Médiathèque de la Rochefourchet</title>
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-item {
            flex: 1;
            min-width: 120px;
        }
        .filter-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
                <h1>Consulter les documents</h1>
            </div>

            <div class="main-content">
                <div class="search-box">
                    <input type="text" placeholder="Recherche..." class="search-input">
                    <button class="search-btn">Rechercher</button>
                </div>

                <div class="filter-container">
                    <div class="filter-item">
                        <label>Type</label>
                        <select class="filter-select">
                            <option>Tous</option>
                            <option>Livre</option>
                            <option>DVD</option>
                            <option>CD</option>
                            <option>Revue</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Auteur</label>
                        <select class="filter-select">
                            <option>Tous</option>
                            <option>J.K. Rowling</option>
                            <option>Victor Hugo</option>
                            <option>Ernest Hemingway</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Genre</label>
                        <select class="filter-select">
                            <option>Tous</option>
                            <option>Roman</option>
                            <option>Science-fiction</option>
                            <option>Policier</option>
                            <option>Jeunesse</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Date</label>
                        <select class="filter-select">
                            <option>Toutes</option>
                            <option>2023</option>
                            <option>2022</option>
                            <option>2021</option>
                            <option>2020</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Statut</label>
                        <select class="filter-select">
                            <option>Tous</option>
                            <option>Disponible</option>
                            <option>Emprunté</option>
                            <option>Réservé</option>
                        </select>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Genre</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Dernière consultation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Livre</td>
                            <td>Harry Potter et la coupe de feu</td>
                            <td>J.K. Rowling</td>
                            <td>Fantasy</td>
                            <td>2000</td>
                            <td>Disponible</td>
                            <td>12/04/2023</td>
                        </tr>
                        <tr>
                            <td>DVD</td>
                            <td>Inception</td>
                            <td>Christopher Nolan</td>
                            <td>Science-fiction</td>
                            <td>2010</td>
                            <td>Emprunté</td>
                            <td>23/05/2023</td>
                        </tr>
                        <tr>
                            <td>Livre</td>
                            <td>Le Seigneur des Anneaux</td>
                            <td>J.R.R. Tolkien</td>
                            <td>Fantasy</td>
                            <td>1954</td>
                            <td>Disponible</td>
                            <td>15/03/2023</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>