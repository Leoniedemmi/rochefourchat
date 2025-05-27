<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Médiathèque - Admin</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="admin-header">Admin</div>
      <ul class="sidebar-menu">
        <li><img src="image/doc.png" alt="logo doc" class="logo"><a href="documents.php"> Consulter les Documents</a> </li>
        <li><img src="image/+doc.png" alt="logo consultdoc" class="logo"><a href="ajoutdoc.php"> Ajouter un document</a></li>
        <li><img src="image/modif.png" alt="logo modif" class="logo"><a href="modifdoc.php"> Modifier/supprimer un document</a></li>
        <br><hr>
        <li><img src="image/consulterade.png" alt="logo consulterade" class="logo"><a href="adherents.php"> Consulter les Adhérents</a></li>
        <li><img src="image/+ade.png" alt="logo ajoutade" class="logo"><a href="ajoutade.php">Ajouter un adhérent</li>
        <li><img src="image/modif.png" alt="logo modif" class="logo"><a href="modifade.php"> Modifier/supprimer un adhérent</a></li>
        <br><hr>
        <li><img src="image/consulteremp.png" alt="logo consulteremp" class="logo"><a href="emprunts.php">Consulter les emprunts</a></li>
        <li><img src="image/+emp.png" alt="logoconsultemp" class="logo"><a href="ajoutemp.php">Ajouter un emprun</a></li>
        <li><img src="image/modif.png" alt="logo modif" class="logo"><a href="modifemp.php">Modifier/supprimer un utilisateur</a></li>
      </ul>
    </aside>

    <main class="main">
    <div class="main-header" class="bar">
        <h1>Médiathèque de la Rochefourchat</h1>
    </div>

    <div class="content-container">
        <section class="stats-box">
            <h2>Statistiques</h2>
            <div class="stats-container">
                <div class="title-stats">
                    <h3>Livre</h3>
                    <h3>VHS</h3>
                    <h3>Cassette audio</h3>
                    <h3>DVD</h3>
                    <h3>CD/Vinyle</h3>
                </div>
                <div class="statistics">
                    <div>Valeur 1</div>
                    <div>Valeur 2</div>
                    <div>Valeur 3</div>
                </div>
            </div>
        </section>

        <section class="intro">
            <p><strong>Bienvenue sur l’interface d’administration de la Médiathèque de Rochefourchat.</strong></p>
            <p>Cet espace est dédié à la gestion et à l’organisation de notre médiathèque. Vous pouvez :</p>
            <ul>
                <li>📚 Gérer le catalogue des livres, films et autres ressources</li>
                <li>✏️ Ajouter, modifier ou supprimer des fiches utilisateur</li>
                <li>🕒 Suivre les emprunts et les retours en temps réel</li>
                <li>📊 Accéder aux statistiques et rapports d’utilisation</li>
            </ul>
        </section>
    </div>
</main>
  </div>
</body>
</html>
