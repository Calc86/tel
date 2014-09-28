<?php

$f = fopen("./proshel","r");
$buf = '';
if($f)
{
    while(!feof($f))
	$buf.= fread($f,1024);
	
    fclose($f);
}

echo 'log uspeshnigo prohojdeniya faxa:<br>';
echo '<pre>';
echo $buf;
echo '</pre>';



?>