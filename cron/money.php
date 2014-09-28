<?php

define("INDEX", "/index.php");       //для ограничения прямого доступа к файлам, к которым не должно быть доступа
require('../mods/func.php');
require('../mods/functions.php');

$db = mysql_connect('localhost','root','');
mysql_select_db("asterisk");

mysql_query("SET character_set_client='utf8'");
mysql_query("SET character_set_connection='utf8'");
mysql_query("SET character_set_results='utf8'");

/*
select sum(cost)
from stat as s
where
    (
	(s.ch='SIP/206'or s.ch='SIP/205'or s.ch='SIP/207'or s.ch='SIP/10020'or s.ch='SIP/10021')
	or 
	(s.dstch='SIP/206'or s.dstch='SIP/205'or s.dstch='SIP/207'or s.dstch='SIP/10020'or s.dstch='SIP/10021')
    )
    and
    s.cd >= '2009-10-01 00:00:00' and s.cd <= '2009-10-31 23:59:59'
    and
    cost>=0;
    
select sum(cost)
from stat as s
where
    (
	s.ch='%SIP%'
	or 
	s.dstch='%SIP%'
    )
    and
    s.cd >= '%YEAR%-%MON%-01 00:00:00' and s.cd <= '%YEAR%-%MON%-%MAXD% 23:59:59'
    and
    cost>=0;
*/

$tmp_q = "
    select sum(cost)
    from stat as s
    where
    (s.ch='%SIP%' or s.dstch='%SIP%')
    and
    s.cd >= '%YEAR%-%MON%-01 00:00:00' and s.cd <= '%YEAR%-%MON%-%MAXD% 23:59:59'
    and
    cost>=0;";
$tmp_find = array('%YEAR%', '%MON%', '%MAXD%', '%SIP%');


$cmon = get_var('m',date("m"));
$cyear = get_var('y',date("Y"));
$a = op_month($cmon, $cyear, -1);

$year = $a['y'];
$mon = $a['m'];
$maxd = date("t", strtotime("$year-$mon-01 12:00:00"));

$tmp_repl = array($year,$mon,$maxd);

$q = "select * from users";
$r = mysql_query($q);
$n = mysql_num_rows($r);

for($i=0;$i<$n;$i++) {
    $row = mysql_fetch_array($r);
    $tmp_repl[3] = 'SIP/'.$row['intno'];
    $qs = str_replace($tmp_find, $tmp_repl, $tmp_q);
    $rs = mysql_query($qs);
    $rows = mysql_fetch_array($rs);
    $oid = $row['oid'];
    $dt = $year.'-'.$mon.'-'.$maxd.' 23:59:59';
    $sum = -$rows[0];
    if($sum == '') $sum=0;
    $name = 'Тарификация, аккаунт SIP/'.$row['intno'];
    $comment = '';
    $op = $row['intno'].'-'.$year.'-'.$mon.'-'.'MONTH';
    $qi = "insert into money values(0,$oid,'$dt',$maxd,0,'$sum','$name','$comment','$op')";
    //echo $qi.'<br>';
    $ri = mysql_query($qi);
}
echo 'end month op OK!<br>';

$q = "select * from org";
$r = mysql_query($q);
$n = mysql_num_rows($r);

for($i=0;$i<$n;$i++) {
    $row = mysql_fetch_array($r);
    $oid = $row['id'];
    $qm = "select sum(sum) from money where (dt>='$year-$mon-01 00:00:00' and dt<='$year-$mon-$maxd 23:59:59') and oid=$oid";
    //echo $qm.'<br>';
    $rm = mysql_query($qm);
    $nm = mysql_num_rows($rm);
    $rowm = mysql_fetch_array($rm);
    //print_r($rowm);
    //echo $oid.': '.$rowm[0].'<br>';
    //$dt = date('Y-m-01 00:00:00');
    $a = op_month($mon, $year, +1);
    $dt = $a['y'].'-'.$a['m'].'-01 00:00:00';
    $sum = $rowm[0];
    if($sum == '') $sum=0;
    $name = 'Остаток на начало месяца';
    $comment = '';
    //$op = $oid.'-'.date('Y').'-'.date('m').'-01-'.'MONTH';
    $op = $oid.'-'.$cyear.'-'.$cmon.'-01-'.'MONTH';
    $qi = "insert into money values(0,$oid,'$dt',0,0,'$sum','$name','$comment','$op')";
    echo $qi.'<br>';
    $ri = mysql_query($qi);
    $qu = "update org set money='$sum' where id=$oid limit 1";
    $ru = mysql_query($qu);
}
echo 'start month op OK!<br>';


?>
