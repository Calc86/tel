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
 u.dtmfmode as dtmfmode,
 p.id as pid,
 p.name as peer,
 p.tel as tel,
 p.host as host,
 p.username as username, 
 p.secret as psecret,
 p.nid as nid,
 pn.no as pno,
 p.ban as ban,
 u.flags as flags,
 u.call_limit as call_limit,
 p.call_limit as pcall_limit
from
 org as o,
 users as u,
 peers as p,
 num_pool as pn
where
 o.id=u.oid and
 o.id=p.oid and
 u.pid=p.id and
 p.nid=pn.id
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
    //$row = mysql_fetch_array($r);
    list($oid,$org,$intno,$usecret,$dtmfmode,$pid,$peer,$tel,$host,$un,$psecret,$nid,$pno,$ban,$flags,$call_limit,$pcall_limit) = mysql_fetch_array($r);
    if($nid) $tel = $pno.'_'.$pid;
    if($nid) $peer = 'p'.$pno.'_'.$pid;
    //regs
    //register => 100xxxx:PASS@89.208.190.2/100xxxx
    $sip_reg = ($ban ? ';' : '')."register => {username}:{secret}@{host}/{username}\n";
    $rr = '';
    $rr = $sip_reg;
    $rr = str_replace("{username}",$un,$rr);
    $rr = str_replace("{secret}",$psecret,$rr);
    $rr = str_replace("{host}",$host,$rr);
    $rg[$peer]=$rr;
    
    //peers
    $pp = '';
    $pp = $sip_peer;
    $pp = str_replace("{pid}",$pid,$pp);
    $pp = str_replace("{oid}",$oid,$pp);
    $pp = str_replace("{name}",$peer,$pp);
    $pp = str_replace("{tel}",$tel,$pp);
    $pp = str_replace("{org}",$org,$pp);
    $pp = str_replace("{host}",$host,$pp);
    $pp = str_replace("{username}",$un,$pp);
    $pp = str_replace("{secret}",$psecret,$pp);
    $pp = str_replace("{params}","",$pp);
    $pp = str_replace("{pcall_limit}",$pcall_limit,$pp);
    $pp = str_replace("{banned}",$ban ? '(!)' : '',$pp);
    $p[$peer]= $pp;
    
    //users
    $t38    = unpack_byte($flags, 0, 1); //echo $t38;
    $crinv  = unpack_byte($flags, 1, 1); //echo $crinv;
    $nat    = unpack_byte($flags, 2, 2); //echo $nat;
    $dtmf2  = unpack_byte($flags, 4, 2); //echo $dtmf2;
    $uu = '';
    $uu = $sip_user;
    $uu = str_replace("{org}",$org,$uu);
    $uu = str_replace("{tel}",$tel,$uu);
    $uu = str_replace("{intno}",$intno,$uu);
    $uu = str_replace("{secret}",$usecret,$uu);
    $uu = str_replace("{dtmf}",$dtmfmode,$uu);
    $uu = str_replace("{params}","",$uu);
    $uu = str_replace("{t38}", y_n($t38),$uu);
    $uu = str_replace("{crinv}", y_n($crinv),$uu);
    $uu = str_replace("{call_limit}", $call_limit,$uu);
    $uu = str_replace("{nat}", y_n($nat,array('no','yes','never')),$uu);
    $uu = str_replace("{dtmf2}", y_n($dtmf2,array('auto','inband','rfc2833','info')),$uu);
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









