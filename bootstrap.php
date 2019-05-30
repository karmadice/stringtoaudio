<?php
require_once "vendor/autoload.php";

function dd($var) {
    var_dump($var);die;
}

function pr($var){
    echo "<pre>".print_r($var)."</pre>";die;
}