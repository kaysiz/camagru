<?php
    session_start();

    if (!isset($_SESSION['loggedin'])) {
      header('Location: index.php?loggedin=false');  
    }

    include "./includes/header.inc.php";

    if ($_GET['gallery']) {

        include "./dashboard/dash.gallery.php";

    } elseif ($_GET['profile']) {

        include "./dashboard/dash.profile.php";

    } elseif ($_GET['manual']) {

        include "./dashboard/dash.manual.php";

    } elseif ($_GET['comment']) {

        include "./dashboard/dash.comment.php";

    }else {

        include "./dashboard/dash.index.php";

    } 
    
    include "./includes/footer.inc.php";
?>