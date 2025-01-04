<?php

session_start();

$path = $_SERVER['REQUEST_SCHEME'] . "://". $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
$path = str_replace('index.php','', $path);

 //define used for create constants
define('ROOT', $path);
define('ASSETS', $path . "assets/");

include "../app/init.php";

//show(ASSETS);

$app = new App();

//Echo "This is the home page<br>";

//print_r($_GET['url']);