<?php session_start();?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru | Kaysiz</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div class="container">
            <div id="branding">
                <a href="index.php"><h1>Camagru</h1></a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <?php if (isset($_SESSION['loggedin'])): ?>
                    <li><a href="./includes/funcs.inc.php?logout=true">Logout</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn"><?=$_SESSION['username'];?></a>
                        <div class="dropdown-content">
                            <a href="dashboard.php">Dashboard</a>
                            <a href="dashboard.php?gallery=true">My Gallery</a>
                            <a href="dashboard.php?profile=true">Profile</a>
                            <a href="./includes/funcs.inc.php?delete=account&user=<?=$_SESSION['email'];?>" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</a>
                        </div>
                    </li>
                    <?php else: ?>
                    <li><a href="#login" onclick="document.getElementById('login').style.display='block'">Login</a></li>
                    <li><a href="#signup" onclick="document.getElementById('signup').style.display='block'">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    
    