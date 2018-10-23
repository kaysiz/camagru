<?PHP
session_start();
var_dump($_SESSION['username'].substr(sha1(mt_rand()),17,6).'.png');
die();
?>