<?php
require_once "utils/require.php";


$path =   explode( "/", $_SERVER['REQUEST_URI']);
$globalPath = $path[2];

switch($globalPath){
    case "home" :
        require_once "homepage.php";
        break;
    case "" :
        require_once "homepage.php";
        break;
    case "backoffice" :
        require_once "adminpage.php";
        break;
    default :
        require_once "404.php";
}
