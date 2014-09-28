<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$ajax = get_var('ajax',0);

$alpha = array(
    "a","b","c","d","e","f","g","h","i","j",
    "k","l","m","n","o","p","q","r","s","t",
    "u","v","w","x","y","z","0","1","2","3",
    "4","5","6","7","8","9","0"
);

$size_min = get_var("min",6);  //минимальный размер пароля
$size_max = get_var("max",10);; //максимальный размер пароля

$size = rand($size_min, $size_max); //размер пароля

$passwd =array();
for($i=0;$i<$size;$i++){
    $a = rand(0,count($alpha)-1);
    $a = $alpha[$a];
    $a = rand(0,1) ? strtoupper($a) : $a;
    array_push($passwd,$a);
}

//usort($passwd, "cmp");

$pass = "";
foreach($passwd as $v){
    $pass.= $v;
}

if(!$ajax)
    $body.= $pass;
else {
    echo $pass;
    exit;
}


/*function cmp($a,$b) {
    $cmp = rand(0,1);
    if($cmp) return 1;
    else return -1;
}*/

?>
