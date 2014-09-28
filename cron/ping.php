<?php
/*
 * Записывает sql.devices.ip в file.in
 */
$db = mysql_connect("localhost", "root", "") or die ("Not connect:" . mysql_error());
mysql_select_db('asterisk');
$q ="SELECT ip FROM  `devices`";
//echo $query;
$r = mysql_query ($q);
$dir = dirname(__FILE__);
$inf = fopen("$dir/in","w");
while ($line = mysql_fetch_array($r, MYSQL_ASSOC)){
    $in = $line['ip']."\n";
    fwrite($inf,$in);
}
//заглушка для ожидания ответа от наших мертвых IP
$in = "127.0.0.1\n";
fwrite($inf,$in);
fclose($inf);

?>
