<?php

//вычисляет стоимость звонка. По идее запускается последним

$db = mysql_connect("localhost","root","");
mysql_select_db("asterisk");

$cdate = date("Y-m-01 00:00:00",mktime()-60*60*24); //за сутки до первого дня месяца

$q = "
        select
            s.id as id,
            p.cost as cost,
            s.billsec as billsec
        from stat as s left join price as p on s.kod=p.kod
        where
            p.ptid=0 and
            s.minute=0 and
            s.direction in ('out','otr','redir') and
            s.cd>='$cdate'
        ";
//echo $q."<br>";
$r = mysql_query($q);
$n = mysql_num_rows($r);
echo $n."\n";
//$n = 30;
for($i=0;$i<$n;$i++) {
    $row = mysql_fetch_array($r);
    $id = $row['id'];
    $minute = ($row['cost']=='') ? 0 : $row['cost'];	// -1 - так и не определили стоимость звонка, нет в прайсе
    // посекундная тарификация
    //$cost = round($minute/60 * $row['billsec'],2);	// сколько стоит
    //поминутная тарификация 2015-09-11
    $cost = round(ceil($row['billsec']/60) * $minute, 2);
    // записать в таблицу
    $qu = "update stat set minute='$minute', cost='$cost' where id=$id limit 1";
    mysql_query($qu);
    //echo $qu."<br>";
}
mysql_close($db);

?>
