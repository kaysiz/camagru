<?php
    session_start();
    $DB_DSN = 'localhost';
    $DB_USER = 'root';
    $DB_PASSWORD = 'rooting';
    $DB_NAME = 'camagru';
    //connect to the newly created database
    try {
        $conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
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
        }elseif(isset($_GET['comments'])) {
            getcomments(trim($_GET['imgkey']), $conn);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['comment'])) {
            $imgId = trim($_POST['imgkey']);
            $comment = trim($_POST['data']);
            comment($imgId, $comment, $conn);
        } elseif (isset($_POST['update'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $notify = ($_POST['notify'] == 'yes') ? 1 : 0;
            $user = array($username, $email, $notify, $password);
            // print_r($user);
            // die();
            updateuser($user, $conn);
        } elseif (isset($_POST['delimg'])) {
            $img = trim($_POST['key']);
            deleteimg($img, $conn);
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
            saveimg(trim($_POST['key']), trim($_POST['filter']), $conn);
        }elseif (isset($_POST['pwdreset'])) {
            $email = trim($_POST['email']);
            $token = md5(md5(time().$email.rand(0,9999)));
            passreset($email, $token, $conn);
        }elseif (isset($_POST['like'])) {
            $imgId = trim($_POST['imgkey']);
            like($imgId, $conn);
        }elseif (isset($_POST['unlike'])) {
            $imgId = trim($_POST['imgkey']);
            unlike($imgId, $conn);
        }elseif (isset($_POST['email'])) {
            $img = trim($_POST['imgkey']);
            commentemail($img, $conn);
            // echo $img;
        }elseif (isset($_POST['manual'])) {  
            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            
            if(empty($errors)==true) {
                if(move_uploaded_file($file_tmp,"../images/raw/".$file_name)) {
                    saveimg($file_name, trim($_POST['imgoverlay']), $conn);
                }
            }
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

    function deleteimg($img, $conn) {
        //delete image
        try{
            $image = $conn->prepare('DELETE FROM images WHERE imgId = :imgId');
            $image->bindParam(':imgId', $img);
            $image->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }

        //delete comments for the image
        try{
            $comments = $conn->prepare('DELETE FROM comments WHERE imgId = :imgId');
            $comments->bindParam(':imgId', $img);
            $comments->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
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
            $pwd = password_hash(trim($user[3]), PASSWORD_BCRYPT, array('cost' => 5));
            try {
                $update = $conn->prepare('UPDATE users SET username = :username, email = :email, password = :pwd, notify = :notify WHERE email = :email');
                $update->bindParam(':username', $user[0]);
                $update->bindParam(':email', $user[1]);
                $update->bindParam(':pwd', $pwd);
                $update->bindParam(':notify', $user[2]);
                // var_dump($update);
                // die();
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

    function getpublicimage($key, $conn) {
        try{
            $access = $conn->prepare('SELECT * FROM images WHERE imgId = :imgId LIMIT 1');
            $access->bindParam(':imgId', $key);
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $image = $access->fetchAll(PDO:: FETCH_ASSOC);
        return $image;
    }

    function getpublicimages($conn) {
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

    function getprivateimages($conn) {
        try{
            $access = $conn->prepare('SELECT * FROM images WHERE userId = :userId');
            $access->bindParam(':userId', $_SESSION['username']);
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

    function commentemail($img, $conn) {
        try{
            $access = $conn->prepare('SELECT users.email, users.notify FROM users INNER JOIN images ON users.username=images.userId where images.imgId = :imgid');
            $access->bindParam(':imgid', $img);
            $access->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $user = $access->fetch(PDO:: FETCH_ASSOC);
        
        if ($user['notify'] == 1) {
            $to = $user['email'];;
        
            // Subject
            $subject = 'New comment from Camagru';

            // Message
            $message = '
            <html>
            <head>
            <title>New Comment from Camagru</title>
            </head>
            <body>
            <p>'.$_SESSION['username'].' commented on your picture click <a href="http://localhost:8080/camagru/dashboard.php?comment=true&imgkey='. $img .'">here to view comment.</a></p>
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
        <p>To reset your Camagru account password click <a href="http://localhost:8080/camagru/includes/funcs.inc.php?reset=true&email='.$email.'&token='. $token.'">here.</a></p>
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

    function saveimg($img, $overlay,$conn) {
        header("content-type: image/jpg");
        $imgkey = substr(sha1(mt_rand()),17,6);
        $username = $_SESSION['username'];
        $imagename = $username.$imgkey.'.png';

        if (strpos($img, 'data:image') !== false) {
            file_put_contents('../images/raw/temp.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img)));
        }
        $second = imagecreatefrompng('.'.$overlay);
        $first = imagecreatefrompng('../images/raw/temp.png');                                                   
        
        imagecopy($first,$second,0,0,0,0,500,500);
        
        imagejpeg($first, '../images/public/'.$imagename, 100);
        
        imagedestroy($first);
        imagedestroy($second);
        try {
            $imgupload = $conn->prepare("INSERT INTO images(imgName, imgId, userId)VALUES (:imgName, :imgId, :userId)");
            $imgupload->bindParam(':imgName', $imagename);
            $imgupload->bindParam(':imgId', $imgkey);
            $imgupload->bindParam(':userId', $_SESSION['username']);
            $imgupload->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    //comments

    function comment($imgId, $comment, $conn) {
        try {
            $postcomment = $conn->prepare("INSERT INTO comments(imgId, userId, comment)VALUES (:imgId, :userId, :comment)");
            $postcomment->bindParam(':imgId', $imgId);
            $postcomment->bindParam(':userId', $_SESSION['username']);
            $postcomment->bindParam(':comment', $comment);
            $postcomment->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function getcomments($imgkey, $conn){
        try{
            $comments = $conn->prepare('SELECT * FROM comments WHERE imgId = :imgId');
            $comments->bindParam(':imgId', $imgkey);
            $comments->execute();
        }
        catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
        $data = $comments->fetchAll(PDO:: FETCH_ASSOC);
        echo json_encode($data);
    }


    // Likes System
    function like($imgId, $conn) {
        try {
            $like = $conn->prepare('UPDATE images SET likes = likes + 1 WHERE imgId = :imgId');
            $like->bindParam(':imgId', $imgId);
            $like->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function unlike($imgId, $conn) {
        try {
            $unlike = $conn->prepare('UPDATE images SET likes = likes - 1 WHERE imgId = :imgId AND like != 0');
            $unlike->bindParam(':imgId', $imgId);
            $unlike->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    //security XSS
    function e($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }