<?php

$sip_conf = '';
$sip_user = '';
$sip_peer = '';
$sip_reg = '';

$f = fopen("./tmp/sip.tmp.conf","r");
if($f){
    while(!feof($f))
	$sip_conf.=fread($f,1024);
    fclose($f);
}

$f = fopen("./tmp/sip.user","r");
if($f){
    while(!feof($f))
	$sip_user.=fread($f,1024);
    fclose($f);
}

$f = fopen("./tmp/sip.peer","r");
if($f){
    while(!feof($f))
	$sip_peer.=fread($f,1024);
    fclose($f);
}

$q = "
select
 o.id as oid,
 o.name as org,
 u.intno,
 u.secret as usecret,
 p.id as pid,
 p.name as peer,
 p.tel as tel,
 p.host as host,
 p.username as username, 
 p.secret as psecret
from
 org as o,
 users as u,
 peers as p
where
 o.id=u.oid and
 o.id=p.oid and
 u.pid=p.id
order by
 u.intno";
 
//echo $q;
$r = _mysql_query($q);
$n = mysql_num_rows($r);

$u = '';
$p = '';
$rg = '';

for($i=0;$i<$n;$i++)
{
    $row = mysql_fetch_array($r);
    //regs
    //register => 100xxxx:PASS@89.208.190.2/100xxxx
    $sip_reg = "register => {username}:{secret}@{host}/{username}\n";
    $rr = '';
    $rr = $sip_reg;
    $rr = str_replace("{username}",$row['username'],$rr);
    $rr = str_replace("{secret}",$row['psecret'],$rr);
    $rr = str_replace("{host}",$row['host'],$rr);
    $rg[$row['peer']]=$rr;
    
    //peers
    $pp = '';
    $pp = $sip_peer;
    $pp = str_replace("{pid}",$row['pid'],$pp);
    $pp = str_replace("{oid}",$row['oid'],$pp);
    $pp = str_replace("{name}",$row['peer'],$pp);
    $pp = str_replace("{tel}",$row['tel'],$pp);
    $pp = str_replace("{org}",$row['org'],$pp);
    $pp = str_replace("{host}",$row['host'],$pp);
    $pp = str_replace("{username}",$row['username'],$pp);
    $pp = str_replace("{secret}",$row['psecret'],$pp);
    $pp = str_replace("{params}","",$pp);
    $p[$row['peer']]= $pp;
    //users
    $uu = '';
    $uu = $sip_user;
    $uu = str_replace("{org}",$row['org'],$uu);
    $uu = str_replace("{tel}",$row['tel'],$uu);
    $uu = str_replace("{intno}",$row['intno'],$uu);
    $uu = str_replace("{secret}",$row['usecret'],$uu);
    $uu = str_replace("{params}","",$uu);
    $u.=$uu;
}

$sip_conf = str_replace("{users}",$u,$sip_conf);
$pp = '';
foreach($p as $val) $pp.=$val;
$sip_conf = str_replace("{peers}",$pp,$sip_conf);
$rr = '';
foreach($rg as $val) $rr.=$val;
$sip_conf = str_replace("{register}",$rr,$sip_conf);

$sip_econf = nl2br($sip_conf);
require("./mods/regex.inc.php");
//econf
// [sip_no]
// ([sip])
$sip_econf = preg_replace($rx_head,$ht_head,$sip_econf);
// comment ;(.*)
$sip_econf = preg_replace($rx_comment,$ht_comment,$sip_econf);
// link to peer
// [peer:id:name]
// /\[peer:(\d+):(\w+)\]/
$sip_econf = preg_replace($rx_peer,$ht_peer,$sip_econf);
$sip_econf = preg_replace($rx_user,$ht_user,$sip_econf);
$sip_econf = preg_replace($rx_context,$ht_context,$sip_econf);
$sip_econf = preg_replace($rx_csip,$ht_csip,$sip_econf);

$hret = '';
$hret.= '
    <div style="font-size: 10pt; border: #ACA899 solid 1px; padding-left: 18px; background: #ECE9D8;">
	<div style="background: #FFFFFF; border-left: #808080 solid 1px; padding-left: 14px;">
';
$hret.=$sip_econf;
$hret.= '</div></div>';

//вывод
$hret.= '<pre style="font-size: 8pt; background: #d0d0d0; padding: 3px; border: solid 1px #000000;">';
$hret.=$sip_conf;
$file_sip = $sip_conf;
$hret.='</pre>';

?>









