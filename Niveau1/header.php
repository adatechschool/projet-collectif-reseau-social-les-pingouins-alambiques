<?php
session_start();
?>
<header>
    <img src="logo.jpg" alt="Logo de notre réseau social" />
    <nav id="menu">
        <a href="news.php">Actualités</a>
        <a href="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mur</a>
        <a href="feed.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Flux</a>
        <a href="tags.php?tag_id=<?php echo $_SESSION['connected_id'] ?>">Mots-clés</a>
        <a href="login.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Connexion</a>
    </nav>
    <nav id="user">
        <?php

        ?>
        <a href="">
            <?php echo $_SESSION['connected_alias'] ?>
            
        </a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Paramètres</a></li>
            <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes suiveurs</a></li>
            <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes abonnements</a></li>
        </ul>

    </nav>
</header>