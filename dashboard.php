<?php
    session_start();
    if (!isset($_SESSION['loggedin'])) {
      header('Location: index.php?loggedin=false');  
    }
    include "./includes/header.inc.php"; 
?>
    <div class="container">
        <div class="row" style="height:100vh;">
        <?php
            if ($_GET['gallery']) {
                include "./dashboard/dash.gallery.php";
            } elseif ($_GET['profile']) {
                include "./dashboard/dash.profile.php";
            } else {
                include "./dashboard/dash.index.php";
            } 
        ?>
        </div>
    </div>
<?php include "./includes/footer.inc.php";?>