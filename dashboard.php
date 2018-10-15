<?php include "./includes/header.inc.php"; ?>
    <br>
    <div class="container">
        <div class="sidebar">
            <a href="dashboard.php">Home</a>
            <a href="dashboard.php?gallery=true">My Gallery</a>
            <a href="dashboard.php?profile=true">Edit Profile</a>
        </div>

        <div class="content">
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