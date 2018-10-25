<?php 
    include "./includes/header.inc.php";
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perpage = 5;
    $start = ($page > 1) ? ($page * $perpage) - $perpage : 0;
    //query
    $query = $conn->prepare("SELECT  SQL_CALC_FOUND_ROWS * FROM images limit {$start},{$perpage} ");
    $query->execute();

    $images = $query->fetchAll(PDO::FETCH_ASSOC);

    //pages
    $total = $conn->prepare("SELECT FOUND_ROWS() as total");
    $total->execute();
    $total = $total->fetch()['total'];
    $pages =  ceil($total / $perpage);
?>

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
            <?php elseif ($_GET['reset'] == 'false'): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Password Reset failed</p>
            <?php elseif ($_GET['pwdreset']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Password Reset email sent!</p>
            <?php elseif ($_GET['accountdelete']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account Deleted successfully!! Pheeeeeeew!!! finally you are gone</p>
            <?php endif; ?>
            <h1>Welcome to Camagru</h1>
        </div>
        <div class="container gallery-container">
            <?php foreach($images as $image): ?>
            <div class="gallery-item">
                <a href=""><img src="./images/public/<?= $image['imgName'];?>" alt=""></a>
                <div class="gallery-desc">
                    <span><a href="#" data-img="<?=$image['imgId'];?>" <?=($_SESSION['loggedin'] ? 'onclick="likes(this)"' : '');?>><i id="heart" class="fas <?= ($image['likes'] > 0) ? 'liked ': '';?>fa-heart fa-2x"></i></a></span>
                    <span><a href="<?=($_SESSION['loggedin'] ? 'dashboard.php?comment=true&imgkey='.$image['imgId'] : '#');?>"><i class="far fa-comments fa-2x"></i></a></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="container">
        <?php if ($page <= $pages): ?>
            <a type="button" href="?page=<?php echo $page-1;?>">previous</a>
            <a type="button" href="?page=<?php echo $page+1;?>">next</a>
        <?php endif; ?>
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
                <span class="psw">Forgot <a href="#pwdreset" onclick="document.getElementById('pwdreset').style.display='block';document.getElementById('login').style.display='none'">password?</a></span>
            </div>
        </form>
    </div>

    <!-- Signup-->
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

    <!-- pwdreset-->
    <div id="pwdreset" class="modal container">
    <!-- Modal Content -->
        <form class="modal-content animate" action="./includes/funcs.inc.php" method="post">
            <span onclick="document.getElementById('pwdreset').style.display='none'"
            class="close" title="Close Modal">&times;</span>
            <div class="container">
                <p><h3>Password reset</h3></p>
                <input type="email" placeholder="Enter Email" name="email" required>
                <input type="hidden" name="pwdreset" value="pwdreset">
                <button type="submit">Reset password</button>
            </div>
        </form>
    </div> 
    <!-- End Modal forms -->
    <script>
        /*
        * password validation
        */
        var myInput = document.getElementById("psw");
        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var length = document.getElementById("length");

        // When the user clicks on the password field, show the message box
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
        }

        // When the user clicks outside of the password field, hide the message box
        myInput.onblur = function() {
            document.getElementById("message").style.display = "none";
        }

        // When the user starts to type something inside the password field
        myInput.onkeyup = function() {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        }

        /*
        * Likes system
        */
        var xhttp = new XMLHttpRequest();
        var img = document.getElementById("heart");
        function likes(d) {
            var user = "<?=$_SESSION['username'];?>";
            if (localStorage.getItem(d.getAttribute("data-img")) !== null) {
                var data = JSON.parse(localStorage.getItem(d.getAttribute("data-img")));
                if(data.includes(user))
                {
                    data.splice( list.indexOf(user), 1 );
                    localStorage.setItem(d.getAttribute("data-img"), JSON.stringify(data));
                    img.classList.remove("liked");
                    unlike(d.getAttribute("data-img"));
                }else {
                    data.push(user);
                    localStorage.setItem(d.getAttribute("data-img"), JSON.stringify(data));
                    img.classList.add("liked");
                    like(d.getAttribute("data-img"));
                }
            }else {
                var user = [user]
                localStorage.setItem(d.getAttribute("data-img"), JSON.stringify(user));
                like(d.getAttribute("data-img"));
            }
        }

        function like(imgid) {
            xhttp.open("POST", "http://localhost:8080/camagru/includes/funcs.inc.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("like=true&imgkey="+imgid);
        }

        function unlike(imgid) {
            xhttp.open("POST", "http://localhost:8080/camagru/includes/funcs.inc.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("unlike=true&imgkey="+imgid);
        }
    </script>
<?php include "./includes/footer.inc.php";?>