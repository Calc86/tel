<?php

$db = mysql_connect("localhost","root","");
mysql_select_db("asterisk");

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
 s.id as sid,
 c.lastapp as lastapp,
 c.lastdata as lastdata
from
 cdr as c left join stat as s on c.id=s.cdrid
where
 s.id is NULL and c.lastapp!='Transferred Call' and c.lastapp!='AppDial'";

$r = mysql_query($q);
$n = mysql_num_rows($r);
echo date("Y-m-d H:i:s: ").$n."\n";

$io = '';
$cdrid = 0;
$cd = '';
for($i=0;$i<$n;$i++) {
    $row = mysql_fetch_array($r,MYSQL_ASSOC);
    $cdrid = $row['cdrid'];
    if(substr($row['dcontext'],0,2) == "ip" || $row['dcontext']=='inbound'){ // ip - old, inbound - new
        if(substr($row['dstchannel'],0,5) == "Local")
            $io = "redir";
        else
            $io = "in";
    }
    if(substr($row['dcontext'],0,1) == "c" || substr($row['dcontext'],0,1) == "p")
        $io = "out";
    $lapp = $row['lastapp'];

    $cd = $row['calldate'];
    $src = $row['src'];
    $dst = $row['dst'];
    $kod = '-1';	//самая главная штука :) -1 = не обсчитан
    //$chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    $aa = preg_split('/-/', $row['channel'], -1, PREG_SPLIT_NO_EMPTY);
    $ch = $aa[0];
    $aa = preg_split('/-/', $row['dstchannel'], -1, PREG_SPLIT_NO_EMPTY);
    //$dstch = isset($aa[0]) ? $aa[0] : $aa;
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
        if($tcd==$cd && $transfer[$tch]!=''){   //фильтр на повторную запись...
            //у нас есть трансфер
            $transfer[$tch] = '';   //чтобы больше небыло повтора
            $qt = "insert into stat values(0,$tid,'tr','$cd',NOW(),'$tsrc','$dst','$kod','$ch','$tldata','$tdur','$tbs','$tdisp','$tuid','0.00','0.00');";
            echo $qt."\n";
            $kod = -5;
        }
    }*/

    $qu = "insert into stat values(0,$cdrid,'$io','$cd',NOW(),'$src','$dst','$kod','$ch','$dstch','$duration','$billsec','$cause','$uid','0.00','0.00');";
    $ru = mysql_query($qu);
    //if($kod==-5) $rt = mysql_query($qt);
    echo $qu."\n";
    //echo '<br><br>';
}

//проверка на трансфер
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
 s.id as sid,
 c.lastapp as lastapp,
 c.lastdata as lastdata
from
 cdr as c left join stat as s on c.id=s.cdrid
where
 s.id is NULL and c.lastapp='Transferred Call'";

$r = mysql_query($q);
$n = mysql_num_rows($r);

echo date("Y-m-d H:i:s: ").'t'.$n."\n";

for($i=0;$i<$n;$i++) {
    $row = mysql_fetch_array($r,MYSQL_ASSOC);
    $cdrid = $row['cdrid'];
    $cd = $row['calldate'];
    $src = $row['src'];
    $dst = $row['dst'];
    $kod = '-1';	//самая главная штука :) -1 = не обсчитан
    //$chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    $aa = preg_split('/-/', $row['channel'], -1, PREG_SPLIT_NO_EMPTY);
    $ch = $aa[0];
    $aa = preg_split('/-/', $row['dstchannel'], -1, PREG_SPLIT_NO_EMPTY);
    $dstch = isset($aa[0])? $aa[0] : $aa;
    $duration = $row['duration'];
    $billsec = $row['billsec'];
    $cause = $row['disposition'];
    $uid = $row['uniqueid'];
    
    $io = 'otr';
    //print_r($row);
    $qt = "select * from stat where src='$src' and cd='$cd' and direction='out' || direction='redir'";
    echo $qt."\n";
    $rt = mysql_query($qt);
    $n1 = mysql_num_rows($rt);
    if(!$n1) $io = 'itr';
    for($j=0;$j<$n1;$j++){
        if($j==2) break;
        if($j==0){
            $rowt = mysql_fetch_array($rt);
            //задаем наши переменные
            //$qt = "(0,$tid,'tr','$cd',NOW(),'$tsrc','$dst','$kod','$ch','$tldata','$tdur','$tbs','$tdisp','$tuid','0.00','0.00');";
            $q1 = "update stat set kod='-5' where src='$src' and cd='$cd' and direction='out'";
            echo $q1."\n";
            $r1 = mysql_query($q1);
            $ch = $rowt['ch'];
            $dst = $rowt['dst'];
            ($rowt['direction']=='in') ? $tr='itr' : $tr='otr';
        }
        if($j==1){
            //удаляем излишек
            //$qd = "delete from stat where src='$src' and cd='$cd' and direction='out' limit 1";
            $qd = "update stat set ch='transfer_dbl' and dstch=ch='transfer_dbl' where src='$src' and cd='$cd' and direction='out' limit 1";
            echo $qd."\n";
            $rd = mysql_query($qd);
        }
    }

    $dstch = $row['lastdata'];
    $qu = "insert into stat values(0,$cdrid,'$io','$cd',NOW(),'$src','$dst','$kod','$ch','$dstch','$duration','$billsec','$cause','$uid','0.00','0.00');";
    $ru = mysql_query($qu);
}

mysql_close($db);

function str_getcsv($p,$d) {
    return preg_split("/$d/", $p);
}

?>
