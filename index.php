<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);include "./includes/header.inc.php"; ?>

    <!-- Gallery Section -->
    <section id="gallery">
        <div class="container heading">
            <?php if ($_GET['userexists']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account already exists!</p>
            <?php elseif ($_GET['usernameexists']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>The username is taken!</p>
            <?php elseif ($_GET['login_error']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>There was an error signing in!</p>
            <?php elseif ($_GET['register']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account created successfully! Follow instructions sent to your email to activate account.</p>
            <?php elseif ($_GET['active']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account is aleady activated!</p>
            <?php elseif ($_GET['activated']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account successfully activated!</p>
            <?php elseif ($_GET['inactive']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account is not activated! Check your email for link</p>
            <?php elseif ($_GET['loggedin'] == 'false'): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>You need to log in to access the dashboard</p>
            <?php elseif ($_GET['notoken']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>The activation token does not exists!!</p>
            <?php elseif ($_GET['accountdelete']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account Deleted successfully!! Pheeeeeeew!!! finally you are gone</p>
            <?php endif; ?>
            <h1>Gallery</h1>
        </div>
        <div class="container gallery-container">
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><i class="fas fa-heart fa-3x" onclick="localstr();"></i></span><span class="notification badge">3</span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
            <div class="gallery-item">
                <a href=""><img src="./images/medium_ksiziva.jpg" alt=""></a>
                <div class="gallery-desc">
                    <span><a href=""><i class="fas fa-heart fa-3x"></i></a></span>
                    <span><a href=""><i class="far fa-comments fa-3x"></i></a></span>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal forms -->
    <!-- Login-->
    <div id="login" class="modal container">
    <!-- Modal Content -->
        <form class="modal-content animate" action="./includes/funcs.inc.php" method="post">
            <span onclick="document.getElementById('login').style.display='none'"
            class="close" title="Close Modal">&times;</span>
            <div class="container">
                <p><h3>Login</h3></p>
                <input type="text" placeholder="Enter Username" name="username" required>

                <input type="password" placeholder="Enter Password" name="password" required>
                <input type="hidden" name="login" value="login">
                <button type="submit">Login</button>
            </div>
            <div class="container">
                <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
            </div>
        </form>
    </div>

    <!-- Login-->
    <div id="signup" class="modal container">
    <!-- Modal Content -->
        <form class="modal-content animate" action="./includes/funcs.inc.php" method="post">
            <span onclick="document.getElementById('signup').style.display='none'"
            class="close" title="Close Modal">&times;</span>
            <div class="container">
                <p><h3>Register</h3></p>
                <input type="text" placeholder="Enter Username" name="username" required>
                <input type="email" placeholder="Enter Email" name="email" required>
                <input type="password" id="psw" placeholder="Enter Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                <input type="hidden" name="register" value="register">
                <button type="submit">Register</button>
            </div>

            <div class="container" style="background-color:#f1f1f1">
                <button type="button" onclick="document.getElementById('signup').style.display='none'" class="cancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
                <div id="message">
                    <h3>Password must contain the following:</h3>
                    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number" class="invalid">A <b>number</b></p>
                    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                </div>
            </div>
        </form>
    </div> 
    <!-- End Modal forms -->
<?php include "./includes/footer.inc.php";?>