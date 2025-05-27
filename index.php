
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médiathèque de la Rochefourchet</title>
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
                <h1>Médiathèque de la Rochefourchet</h1>
            </div>

            <div class="main-content">
                <h2>Statistiques</h2>
                
                <div class="stats-container">
                    <div class="left-panel">
                        <div class="stats-section">
                            <label for="year">Année</label>
                            <select id="year" class="stats-select">
                                <option selected>2023</option>
                                <option>2022</option>
                                <option>2021</option>
                            </select>
                        </div>

                        <div class="stats-section">
                            <label for="category">Catégorie de livres</label>
                            <select id="category" class="stats-select">
                                <option selected>Toutes les catégories</option>
                                <option>Roman</option>
                                <option>Documentaire</option>
                                <option>Jeunesse</option>
                            </select>
                        </div>

                        <div class="stats-section">
                            <label for="month">Mois</label>
                            <select id="month" class="stats-select">
                                <option selected>Tous les mois</option>
                                <option>Janvier</option>
                                <option>Février</option>
                                <option>Mars</option>
                                <option>Avril</option>
                                <option>Mai</option>
                                <option>Juin</option>
                                <option>Juillet</option>
                                <option>Août</option>
                                <option>Septembre</option>
                                <option>Octobre</option>
                                <option>Novembre</option>
                                <option>Décembre</option>
                            </select>
                        </div>

                        <button class="submit-btn">Chercher</button>
                    </div>

                    <div class="right-panel">
                        <h3>Top 5 des livres les plus empruntés</h3>
                        <ul>
                            <li><span class="book-title">Harry Potter et la coupe de feu</span> - <span class="book-author">J.K. Rowling</span></li>
                            <li><span class="book-title">Le Seigneur des Anneaux et la communauté de l'anneau</span> - <span class="book-author">J.R.R. Tolkien</span></li>
                            <li><span class="book-title">Les fourmis</span> - <span class="book-author">Bernard Werber</span></li>
                            <li><span class="book-title">Les lumières de l'aéroport</span> - <span class="book-author">Éric Adam</span></li>
                            <li><span class="book-title">Le vieil homme et la mer</span> - <span class="book-author">Ernest Hemingway</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
?