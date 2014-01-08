<?php
session_start();

header("Content-type: image/jpeg");

echo $_SESSION['picture0'];

?>