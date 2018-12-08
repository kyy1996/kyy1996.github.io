<?php
$url = "/".implode("/", array_filter(explode("/", urldecode($_SERVER['REQUEST_URI']))));
$url .= ".html";

if(!file_exists(__DIR__.$url)) {
        header("HTTP/1.1 404 Not Found", true, 404);
        echo("Not Found");
        exit();
}


header("location: ".$url);
