<div class="container">
    <div class="container heading">
        <?php if ($_GET['usernameexists']): ?>
        <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>The username is taken!</p>
        <?php elseif ($_GET['update']): ?>
        <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Profile successfully updated!</p>
        <?php endif; ?>
    </div>
    <form class="modal-content animate" action="./includes/funcs.inc.php" method="post" style="width:90% !important;">
        <div class="container">
            <p style="text-align:center;"><?php if ($_GET['reset']): ?><h3>Reset your password</h3><?php else: ?><h3>Edit Profile</h3><?php endif; ?></p>
            <input type="text" value="<?= $_SESSION['username'];?>" name="username" required>
            <input type="email" value="<?= $_SESSION['email'];?>" name="email" required>
            <input type="password" placeholder="<?php if ($_GET['reset']): ?>Enter new password<?php else: ?>Enter password<?php endif; ?>" name="password" required>
            <input type="checkbox" name="notify" value="yes" checked="checked"> Do you want to receive notification emails?
            <input type="hidden" name="update" value="update">
            <button type="submit"><?php if ($_GET['reset']): ?>Reset your password<?php else: ?>Update profile<?php endif; ?></button>
        </div>
    </form>
    <div class='push'></div>
</div>