<?php
require_once "vendor/autoload.php";

function dd($var) {
    var_dump($var);die;
}

function pr($var){
    echo "<pre>".print_r($var)."</pre>";die;
}

function splitText($text, $maxLenth) {
    $output = array();
    while (strlen($text) > $maxLenth) {
        $index = strpos($text, ' ', $maxLenth);
        $output[] = trim(substr($text, 0, $index));
        $text = substr($text, $index);
    }
    $output[] = trim($text);
    return $output;
}