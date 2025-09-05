<?php
function make_slug($text){
    $text = preg_replace('~[\p{Z}\s]+~u', '-', trim($text));
    $text = preg_replace('~[^\pL\pN\-]+~u', '', $text);
    $text = trim($text, '-');
    $text = mb_strtolower($text);
    return $text ?: (string)time();
}
 
function excerpt($html, $len = 160){
    $text = trim(strip_tags($html));
    if(mb_strlen($text) <= $len) return $text;
    return mb_substr($text, 0, $len - 1).'…';
}
 
function categories(){
    return ['Technology','Lifestyle','Business','Travel'];
}
?>
