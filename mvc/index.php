<?php
include 'app/lib/autoload.php';
include 'app/lib/constant.php';

$url = '';
$url = isset($_GET['url']) ? $_GET['url'] : "";	
$route = new route($url);

?>