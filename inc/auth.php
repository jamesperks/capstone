<?php
if($_SESSION['logged_in'] !== TRUE) {
    $_SESSION=array();
    header('Location:index.php');
}
?>