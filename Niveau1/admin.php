<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Administration</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include("header.php"); ?>

    <?php
    /**
     * Etape 1: Ouvrir une connexion avec la base de donnée.
     */
    // on va en avoir besoin pour la suite
    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
    //verification
    if ($mysqli->connect_errno) {
        echo ("Échec de la connexion : " . $mysqli->connect_error);
        exit();
    }
    ?>
    <div id="wrapper" class='admin'>
        <aside>
            <h2>Mots-clés</h2>
            <?php
            /*
             * Etape 2 : trouver tous les mots clés
             */
            $laQuestionEnSql = "
                SELECT posts.content,
                posts.created,
                users.alias as author_name,  
                count(likes.id) as like_number,  
                GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                FROM posts
                JOIN users ON  users.id=posts.user_id
                LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                LEFT JOIN likes      ON likes.post_id  = posts.id 
                GROUP BY posts.id
                ORDER BY posts.created DESC  
                LIMIT 5
                ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            /*
             * Etape 3 : @todo : Afficher les mots clés en s'inspirant de ce qui a été fait dans news.php
             * Attention à en pas oublier de modifier tag_id=321 avec l'id du mot dans le lien
             */
            while ($tags = $lesInformations->fetch_assoc()) {
                echo "<pre>" . print_r($users, 1) . "</pre>";
                ?>
                <article>
                    <h3>
                        <?php echo $tags['label'] ?>
                    </h3>
                    <p>
                        <?php echo $tags['id'] ?>
                    </p>
                    <nav>
                        <a href="tags.php?tag_id=tags.id">Messages</a>
                    </nav>
                </article>
            <?php } ?>
        </aside>
        <main>
            <h2>Utilisatrices</h2>
            <?php
            /*
             * Etape 4 : trouver tous les mots clés
             * PS: on note que la connexion $mysqli à la base a été faite, pas besoin de la refaire.
             */
            $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            /*
             * Etape 5 : @todo : Afficher les utilisatrices en s'inspirant de ce qui a été fait dans news.php
             * Attention à en pas oublier de modifier dans le lien les "user_id=123" avec l'id de l'utilisatrice
             */
            while ($tags = $lesInformations->fetch_assoc()) {

                ?>
                <article>
                    <p>
                        <?php echo $tags['email'] ?>
                    </p><br>
                    <p>
                        <?php echo $tags['id'] ?>
                    </p><br>
                    <p>
                        <?php echo $tags['password'] ?>
                    </p><br>
                    <p>
                        <?php echo $tags['alias'] ?>
                    </p>
                    <nav>
                        <a href="wall.php?user_id">Mur</a>
                        | <a href="feed.php?user_id">Flux</a>
                        | <a href="settings.php?user_id">Paramètres</a>
                        | <a href="followers.php?user_id">Suiveurs</a>
                        | <a href="subscriptions.php?user_id">Abonnements</a>
                    </nav>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>