<?php

$db = mysql_connect("localhost","root","");
mysql_select_db("asterisk");

// Астериск по каким то непонятным причинам сбрасывает в cdr одинаковые записи без forkcdr и т.д.
// задача найти их и убрать.

//проверка на трансфер
$transfer = array();
$q = "
select
 c.id, c.calldate, c.src, c.channel, c.lastdata, c.duration, c.billsec, c.disposition, c.uniqueid
from
 cdr as c left join stat as s on c.id=s.cdrid
where
 c.lastapp='Transferred Call' and
 c.calldate>='2010-04-01 00:00:00';";
//and
// s.id is NULL;
//";
$r = mysql_query($q);
$n = mysql_num_rows($r);
for($i=0;$i<$n;$i++) {
    list($id,$cd,$src, $ch, $ldata,$dur,$bs,$disp,$uid) = mysql_fetch_array($r);
    $transfer[$ch] = $id.';;'.$cd.';;'.$src.';;'.$ldata.';;'.$dur.';;'.$bs.';;'.$disp.';;'.$uid;
}

print_r($transfer);

$q = "
select
 c.id as cdrid,
 c.dcontext as dcontext,
 c.calldate as calldate,
 c.src as src,
 c.dst as dst,
 c.channel as channel,
 c.dstchannel as dstchannel,
 c.duration as duration,
 c.billsec as billsec,
 c.disposition as disposition,
 c.uniqueid as uniqueid,
 s.id as sid
from
 cdr as c left join stat as s on c.id=s.cdrid
where
 s.id is NULL";

//echo $q."<br>";
$r = mysql_query($q);
$n = mysql_num_rows($r);
echo date("Y-m-d H:i:s: ").$n."\n";
//$n = 30;

$io = '';
$cdrid = 0;
$cd = '';
for($i=0;$i<$n;$i++) {
    $row = mysql_fetch_array($r);
    //print_r($row);
    $cdrid = $row['cdrid'];
    if(substr($row['dcontext'],0,2) == "ip" || $row['dcontext']=='inbound') // ip - old, inbound - new
        $io = "in";
    if(substr($row['dcontext'],0,1) == "c" || substr($row['dcontext'],0,1) == "p")
        $io = "out";
    $cd = $row['calldate'];
    $src = $row['src'];
    $dst = $row['dst'];
    $kod = '-1';	//самая главная штука :) -1 = не обсчитан
    //$chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    $aa = preg_split('/-/', $row['channel'], -1, PREG_SPLIT_NO_EMPTY);
    $ch = $aa[0];
    $aa = preg_split('/-/', $row['dstchannel'], -1, PREG_SPLIT_NO_EMPTY);
    $dstch = $aa[0];
    $duration = $row['duration'];
    $billsec = $row['billsec'];
    $cause = $row['disposition'];
    $uid = $row['uniqueid'];
    //$tr = isset($transfer[$row['dstchannel']]);
    $tr = 0;
    $qt='';
    /*if(isset($transfer[$row['dstchannel']])){
        $tch = $row['dstchannel'];
        $a = str_getcsv($transfer[$tch],';;');
        list($tid,$tcd,$tsrc,$tldata,$tdur,$tbs,$tdisp,$tuid) = $a;
        if($tcd==$cd){
            //у нас есть трансфер
            $qt = "insert into stat values(0,$tid,'tr','$cd',NOW(),'$tsrc','$dst','$kod','$ch','$tldata','$tdur','$tbs','$tdisp','$tuid','0.00','0.00');";
            echo $qt."\n";
            $kod = -5;
        }
    }*/

    $qu = "insert into stat values(0,$cdrid,'$io','$cd',NOW(),'$src','$dst','$kod','$ch','$dstch','$duration','$billsec','$cause','$uid','0.00','0.00');";
    $ru = mysql_query($qu);
    if($kod==-5) $rt = mysql_query($qt);
    //if(isset($transfer[$row['dstchannel']])) echo $qu."\n";
    //echo '<br><br>';
}

mysql_close($db);

function str_getcsv($p,$d) {
    return preg_split("/$d/", $p);
}

?>
