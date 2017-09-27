<?php

function numberToYearOrYears($numberIn){
    if (strlen($numberIn) > 2)
        $number = mb_substr($numberIn, strlen($numberIn)-2);
    else
        $number = $numberIn;
    if($number > 20)
        $number = mb_substr($number, 1);

    switch ($number){
        case 1: $numberIn .= " год";
            break;
        case 2:
        case 3:
        case 4: $numberIn .= " года";
            break;
        default: $numberIn .= " лет";
    }
    return $numberIn;
}

function translation($word) {
    $word = trim(strip_tags((string)$word));
    $word = str_replace(array("\n", "\r"), " ", $word);
    $word = preg_replace("/\s+/", ' ', $word);
    $word = function_exists('mb_strtolower') ? mb_strtolower($word) : strtolower($word);
    $word = strtr($word, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $word = preg_replace("/[^0-9a-z-_ ]/i", "", $word);
    $word = str_replace(" ", "-", $word);
    return $word;
}




