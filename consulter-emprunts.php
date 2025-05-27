<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les emprunts - Médiathèque de la Rochefourchet</title>
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
        .status-overdue {
            color: red;
            font-weight: bold;
        }
        .status-active {
            color: green;
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
                <h1>Consulter les emprunts</h1>
            </div>

            <div class="main-content">
                <div class="search-box">
                    <input type="text" placeholder="Recherche par adhérent..." class="search-input">
                    <button class="search-btn">Rechercher</button>
                </div>

                <div class="filter-container">
                    <div class="filter-item">
                        <label>Date d'emprunt</label>
                        <select class="filter-select">
                            <option>Toutes</option>
                            <option>Cette semaine</option>
                            <option>Ce mois</option>
                            <option>Ce trimestre</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Type d'emprunt</label>
                        <select class="filter-select">
                            <option>Tous</option>
                            <option>Livre</option>
                            <option>DVD</option>
                            <option>CD</option>
                            <option>Revue</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Statut</label>
                        <select class="filter-select">
                            <option>Tous</option>
                            <option>En cours</option>
                            <option>En retard</option>
                            <option>Terminé</option>
                        </select>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Adhérent</th>
                            <th>Titre</th>
                            <th>Type d'emprunt</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour prévue</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dupont Jean</td>
                            <td>Harry Potter et la coupe de feu</td>
                            <td>Livre</td>
                            <td>01/05/2023</td>
                            <td>15/05/2023</td>
                            <td class="status-active">En cours</td>
                        </tr>
                        <tr>
                            <td>Martin Marie</td>
                            <td>Inception</td>
                            <td>DVD</td>
                            <td>25/04/2023</td>
                            <td>02/05/2023</td>
                            <td class="status-overdue">En retard</td>
                        </tr>
                        <tr>
                            <td>Durand Pierre</td>
                            <td>Les Misérables</td>
                            <td>Livre</td>
                            <td>10/04/2023</td>
                            <td>24/04/2023</td>
                            <td>Terminé</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>