<?php session_start(); ?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />

</head>

<body>
    <?php include("header.php"); ?>

    <div id="wrapper">
        <?php
        /**
         * Etape 1: Le mur concerne un utilisateur en particulier
         * La première étape est donc de trouver quel est l'id de l'utilisateur
         * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
         * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
         * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
         */
        $userId = intval($_GET['user_id']);
        $sessionId = $_SESSION['connected_id']
            ?>
        <?php
        /**
         * Etape 2: se connecter à la base de donnée
         */
        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
        ?>

        <aside>
            <?php
            /**
             * Etape 3: récupérer le nom de l'utilisateur
             */
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice :
                    <?php echo $user['alias'] ?>
                    (n°
                    <?php echo $userId ?>)
                </p>

                <?php
                if ($userId != $sessionId) {
                    $sql = "SELECT COUNT(*) AS count_entries " .
                        "FROM followers " .
                        "WHERE following_user_id = $sessionId " .
                        "AND followed_user_id = $userId";
                    $result = $mysqli->query($sql);

                    if ($result === false) {
                        echo "Error: " . $mysqli->error;
                    } else {
                        $row = $result->fetch_assoc();
                        $count_entries = $row['count_entries'];

                        if ($count_entries > 0) {
                            echo "You are already following this user.";
                        } else {

                            ?>
                            <form action="" method="post" name="followForm">
                                <input type="submit" name="toFollow" value="Follow">
                            </form>
                            <?php
                        }
                    }
                }
                ?>

                <!-- This button works! Make it better though.... -->

            </section>
        </aside>
        <main>
            <?php

            // this is part of the button, it works, dont change it!(much)
            if (isset($_POST['toFollow'])) {
                $lInstructionToFollowSQL = "INSERT INTO followers "
                    . "(id, followed_user_id, following_user_id) "
                    . "VALUES (NULL, "
                    . $userId . ", "
                    . $sessionId .
                    ")";

                $ok = $mysqli->query($lInstructionToFollowSQL);
                if (!$ok) {
                    echo "Impossible de suivre: " . $mysqli->error;
                    echo $sessionId;
                }
            }

            $enCoursDeTraitement = isset($_POST['alias']);
            if ($enCoursDeTraitement) {

                $authorId = $_POST['alias'];
                $postContent = $_POST['content'];

                //Etape 3 : Petite sécurité
                $authorId = intval($mysqli->real_escape_string($authorId));
                $postContent = $mysqli->real_escape_string($postContent);
                //Etape 4 : construction de la requete
                $lInstructionSql = "INSERT INTO posts "
                    . "(id, user_id, content, created, parent_id) "
                    . "VALUES (NULL, "
                    . $authorId . ", "
                    . "'" . $postContent . "', "
                    . "NOW(), "
                    . "NULL);"
                ;
                // Etape 5 : execution
                $ok = $mysqli->query($lInstructionSql);
                if (!$ok) {
                    echo "Impossible d'ajouter le message: " . $mysqli->error;
                } else {
                    echo "Message posté en tant que : " . $listAuteurs[$authorId];
                }
            }

            /**
             * Etape 3: récupérer tous les messages de l'utilisatrice
             */
            $laQuestionEnSql = "
                    SELECT posts.content, 
                    posts.created, 
                    users.alias as author_name, 
                    users.id as author_id, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             */


            while ($post = $lesInformations->fetch_assoc()) {

                ?>
                <article>
                    <h3>
                        <time>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address><a
                            href="http://localhost/projet-collectif-reseau-social-les-pingouins-alambiques/Niveau1/wall.php?user_id=<?php echo $post['author_id'] ?>">
                            <?php echo $post['author_name'] ?>
                        </a></address>
                    <div>
                        <p>
                            <?php echo $post['content'] ?>
                        </p>
                    </div>
                    <footer>
                        <small>♥
                            <?php echo $post['like_number'] ?>
                        </small>
                        <a href="">
                            <?php echo $post['taglist'] ?>
                        </a>
                    </footer>
                </article>
            <?php } ?>

            <?php
            if ($userId == $sessionId) {
                ?>
                <form action="" method="post">
                    <input type='hidden' name='alias' value=<?php echo $_SESSION['connected_id'] ?>>
                    <dl>
                        <dt><label for='content'>Message</label></dt>
                        <dd><textarea name='content'></textarea></dd>
                    </dl>
                    <input type='submit'>
                </form>

            <?php } ?>




        </main>
    </div>
</body>

</html>