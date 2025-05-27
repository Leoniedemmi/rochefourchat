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
                <h1>Consulter les adhérents</h1>
            </div>

            <div class="main-content">
                <div class="search-box">
                    <input type="text" placeholder="Recherche..." class="search-input">
                    <button class="search-btn">Rechercher</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th>Numéro de tel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Jean</td>
                            <td>Dupont</td>
                            <td>12 rue des Lilas, 75020 Paris</td>
                            <td>01 23 45 67 89</td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Marie</td>
                            <td>Martin</td>
                            <td>45 avenue Victor Hugo, 92100 Boulogne</td>
                            <td>06 12 34 56 78</td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Pierre</td>
                            <td>Durand</td>
                            <td>8 boulevard Voltaire, 75011 Paris</td>
                            <td>07 65 43 21 09</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>