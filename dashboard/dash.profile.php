<div class="container">
    <div class="container heading">
        <?php if ($_GET['usernameexists']): ?>
        <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>The username is taken!</p>
        <?php elseif ($_GET['update']): ?>
        <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Profile successfully updated!</p>    
        <?php endif; ?>
        <h1>Gallery</h1>
    </div>
    <form class="modal-content animate" action="./includes/funcs.inc.php" method="post" style="width:90% !important;">
        <div class="container">
            <p style="text-align:center;"><h3>Edit Profile</h3></p>
            <input type="text" value="<?= $_SESSION['username'];?>" name="username" required>
            <input type="email" value="<?= $_SESSION['email'];?>" name="email" required>
            <input type="password" placeholder="Enter Password" name="password" required>
            <input type="hidden" name="update" value="Update">
            <button type="submit">Update</button>
        </div>
    </form>
</div>