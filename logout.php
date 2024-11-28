<?php

include "layout/header.php";
session_start();

$_SESSION = array();

session_destroy();

header("Location: login.php");
exit();

include "layout/footer.php";
?>
