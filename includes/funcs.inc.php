<?php
    session_start();
    include "../config/database.php";
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['logout']))
            logout();
        elseif (isset($_GET['delete'])) {
            if ($_GET['delete'] == 'account') {
                if ($_GET['user']) {
                    if (deleteuser(trim($_GET['user']), $conn)) {
                        session_destroy();
                        header('Location: ../index.php?accountdelete=true');
                    }
                }
            }
        }elseif (isset($_GET['reset'])) {
            $email = trim($_GET['email']);
            $token = trim($_GET['token']);
            pwdreset($email, $token, $conn);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['adduser'])) {
            $name = $_POST['name'];
            $email = $_POST['password'];
            $role = $_POST['role'];
            adduser($name, $email, $role, $conn);
        } elseif (isset($_POST['update'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = array($username, $email, $password);
            updateuser($user, $conn);
        } elseif (isset($_POST['editPlan'])) {
            
        } elseif (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            try {
                $sel_user = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
                $sel_user->bindParam(':username', $username);
                $sel_user->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            $row = $sel_user->fetch(PDO:: FETCH_ASSOC);
            $num = $sel_user->rowCount();
            $hash = $row['password'];

            if ($num > 0) {
                if ($row['isActive'] != 1){
                    header("Refresh:0; ../index.php?inactive=true");
                    exit();
                }
                if (password_verify($password, $hash)) {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['loggedin'] = true;
                    session_write_close();
                    if ($row['role'] == 'admin') {
                        header("Refresh:0; ../index.php");
                    }
                    else {
                        header("Refresh:0; ../index.php");
                    }
                    exit();
                } else {
                    header("Refresh:0; url=../index.php?login_error=true");
                }
            } else {
                header("Refresh:0; url=../index.php?login_error=true");
            }
        } elseif (isset($_POST['register'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT, array('cost' => 5));
            $user = array($username, $email, $password);
            signup($user, $conn);
        }elseif (isset($_POST['img'])) {
            file_put_contents('img.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['key'])));
        }elseif (isset($_POST['pwdreset'])) {
            $email = trim($_POST['email']);
            $token = md5(md5(time().$email.rand(0,9999)));
            passreset($email, $token, $conn);
        }
    }

    /*
    * Functions for adding
    */

    //signup function
    function signup($user, $conn)
    {
        $token = md5(md5(time().$user[1].rand(0,9999)));
        //check if user exists
        try {
            $check = $conn->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
            $check->bindParam(':email', $user[1]);
            $check->bindParam(':username', $user[0]);
            $check->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        // check if user EXISTS
        $num = $check->rowCount();
        $usernamecheck = $check -> fetch(PDO::FETCH_ASSOC);
        if ($num > 0) {
            if ($usernamecheck['username'] == $user[0]) {
                header('Location: ../index.php?usernameexists=true');
            }else{
                header('Location: ../index.php?userexists=true');
            }   
        } else{
            try {
                $signup = $conn->prepare("INSERT INTO users(username, email, `password`, token)VALUES (:username, :email, :pwd, :token)");
                $signup->bindParam(':username', $user[0]);
                $signup->bindParam(':email', $user[1]);
                $signup->bindParam(':pwd', $user[2]);
                $signup->bindParam(':token', $token);
                $signup->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            if ($signup) {
                regmail($user[1], $token);
                header('Location: ../index.php?register=true');
            }
            die();
        }
    }

    // Activate account
    function activate($token, $conn) {
        //check if token exists
        try {
            $check = $conn->prepare('SELECT * FROM users WHERE token = :token');
            $check->bindParam(':token', $token);
            $check->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        // check if user EXISTS
        $num = $check->rowCount();
        $statuscheck = $check -> fetch(PDO::FETCH_ASSOC);
        if ($num > 0) {
            if ($statuscheck['isActive'] == 1) {
                header('Location: ../index.php?active=true');
            } else{
                try {
                    $activate = $conn->prepare('UPDATE users SET isActive = 1 WHERE email = :email');
                    $activate->bindParam(':email', $statuscheck['email']);
                    $activate->execute();
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
    
                if ($activate) {
                    $_SESSION['username'] = $statuscheck['username'];
                    $_SESSION['email'] = $statuscheck['email'];
                    $_SESSION['loggedin'] = true;
                    session_write_close();
                    header('Location: ../index.php?activated=true');
                }
                die();
            } 
        }else{
            header('Location: ../index.php?notoken=true');
        }
    }

    function passreset($email, $token, $conn){
        //Insert reset token
        try {
            $reset = $conn->prepare('INSERT INTO pwdreset(email, token) VALUES(:email, :token)');
            $reset->bindParam(':email', $email);
            $reset->bindParam(':token', $token);
            $reset->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        if ($reset) {
            pwdmail($email, $token, $conn);
            header('Location: ../index.php?pwdreset=true');
        }
    }

    function pwdreset($email, $token, $conn) {
        //check if token exists
        try {
            $check = $conn->prepare('SELECT * FROM pwdreset WHERE token = :token');
            $check->bindParam(':token', $token);
            $check->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        // check if user EXISTS
        $num = $check->rowCount();
        $statuscheck = $check -> fetch(PDO::FETCH_ASSOC);
        if ($num > 0) {
            try {
                $reset = $conn->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
                $reset->bindParam(':email', $statuscheck['email']);
                $reset->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            $checkuser = $reset -> fetch(PDO::FETCH_ASSOC);
            if ($reset->rowCount() > 0) {
                if ($checkuser['isActive'] == 1) {
                    $_SESSION['username'] = $checkuser['username'];
                    $_SESSION['email'] = $checkuser['email'];
                    $_SESSION['loggedin'] = true;
                    session_write_close();
                    header('Location: ../dashboard.php?profile=true&reset=true');
                }else {
                    header("Location: ../index.php?reset=false");
                }
            }
        }else{
            header('Location: ../index.php?notoken=true');
        }
    }

    /*
    * Functions to get
    */
    //get users
    function getusers($conn) {
        try{
            $access = $conn->prepare('SELECT * FROM users');
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $users =$access->fetchAll(PDO:: FETCH_ASSOC);
        return $users;
    }
    function deleteuser($email, $conn) {
        try{
            $access = $conn->prepare('DELETE FROM users WHERE email = :email');
            $access->bindParam(':email', $email);
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        if ($access) {
            return true;
        }else{
            return false;
        }
    }

    function updateuser($user, $conn){
        try {
            $check = $conn->prepare('SELECT * FROM users WHERE username = :username AND email != :email');
            $check->bindParam(':username', $user[0]);
            $check->bindParam(':email', $user[1]);
            $check->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        $num = $check->rowCount();
        if ($num > 0) {
            header('Location: ../dashboard.php?profile=true&usernameexists=true');
        }else{
            $password = password_hash(trim($user[2]), PASSWORD_BCRYPT, array('cost' => 5));
            try {
                $update = $conn->prepare('UPDATE users SET username = :username, email = :email, password = :password');
                $update->bindParam(':username', $user[0]);
                $update->bindParam(':email', $user[1]);
                $update->bindParam(':password', $password);
                $update->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        if ($update) {
            header('Location: ../dashboard.php?profile=true&update=true');
        }
    }

    function getuser($username,$conn) {
        try{
            $access = $conn->prepare('SELECT * FROM users WHERE username = '.$username.' LIMIT 1');
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $users =$access->fetchAll(PDO:: FETCH_ASSOC);
        return $users;
    }

    function getimage($key, $conn) {
        try{
            $access = $conn->prepare('SELECT * FROM images WHERE imgKey = '.$key.' LIMIT 1');
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $images = $access->fetchAll(PDO:: FETCH_ASSOC);
        return $images;
    }

    function getimages($conn) {
        try{
            $access = $conn->prepare('SELECT * FROM images');
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $images = $access->fetchAll(PDO:: FETCH_ASSOC);
        return $images;
    }

    /*
    * Emails
    */
    //registration email
    function regmail($email, $token) {
        $to = $email;
        
        // Subject
        $subject = 'Activate your Camagru account';

        // Message
        $message = '
        <html>
        <head>
        <title>Activate your Camagru account</title>
        </head>
        <body>
        <p>To activate your Camagru account click <a href="http://localhost:8080/camagru/includes/activate.php?activate='. $token.'">here.</a></p>
        </body>
        </html>
        ';
        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        $headers[] = 'From: Camagru <no-reply@camagru.africa>';
        // Mail it
        if (mail($to, $subject, $message, implode("\r\n", $headers)))
            return true;
        else
            return false;
    }

    //pwdreset email
    function pwdmail($email, $token) {
        $to = $email;
        
        // Subject
        $subject = 'Reset Camagru account password';

        // Message
        $message = '
        <html>
        <head>
        <title>Reset your Camagru account password</title>
        </head>
        <body>
        <p>To reset your Camagru account password click <a href="http://localhost:8080/camagru/includes/func.inc.php?reset=true&email='.$email.'&token='. $token.'">here.</a></p>
        </body>
        </html>
        ';
        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        $headers[] = 'From: Camagru <no-reply@camagru.africa>';
        // Mail it
        if (mail($to, $subject, $message, implode("\r\n", $headers)))
            return true;
        else
            return false;
    }

    //logout
    function logout() {
        if (session_destroy())
            header('Location: ../index.php');
    }

    function saveimg() {
        return "Hey wassup";
    }