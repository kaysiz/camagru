<?php
    require_once 'funcs.inc.php';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['activate']))
            $token = trim($_GET['activate']);
            activate($token,$conn);
    }