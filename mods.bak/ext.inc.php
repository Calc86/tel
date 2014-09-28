<?php



$ext_conf = '';
$out_context = '';
$in_context = '';
$inbound = '';

$f = fopen("./tmp/ext.tmp.conf","r");
if($f){
    while(!feof($f))
	$ext_conf.=fread($f,1024);
    fclose($f);
}

$f = fopen("./tmp/ext.in_context","r");
if($f){
    while(!feof($f))
	$in_context.=fread($f,1024);
    fclose($f);
}

$f = fopen("./tmp/ext.out_context","r");
if($f){
    while(!feof($f))
	$out_context.=fread($f,1024);
    fclose($f);
}

$f = fopen("./tmp/ext.inbound","r");
if($f){
    while(!feof($f))
	$inbound.=fread($f,1024);
    fclose($f);
}

$q = "
select
 o.id as oid,
 o.name as org,
 u.intno,
 GROUP_CONCAT(DISTINCT u.intno ORDER BY u.intno SEPARATOR '|') as dial,
 p.id as pid,
 p.name as peer,
 p.tel as tel,
 p.id as id,
 p.username as username
from
 org as o,
 users as u,
 peers as p
where
 o.id=u.oid and
 o.id=p.oid and
 u.pid=p.id
group by
 p.id
order
 by u.intno
";
//echo $q;
$r = _mysql_query($q);
$n = mysql_num_rows($r);

$in = '';
$out = '';
$inb = '';

//добавить в цикл возможность звонков на несколько клиентов одного пира.
//по идее {dial} заменяем на {dial}$i и создаем массив $dial[$i], считаем пиры и дописываем.
for($i=0;$i<$n;$i++)
{
    $row = mysql_fetch_array($r);
    
    //in_context
    $ii = '';
    $ii = $in_context;
    $ii = str_replace("{oid}",$row['oid'],$ii);
    $ii = str_replace("{org}",$row['org'],$ii);
    $ii = str_replace("{pid}",$row['pid'],$ii);
    $ii = str_replace("{peer}",$row['peer'],$ii);
    $ii = str_replace("{tel}",$row['tel'],$ii);
    $ii = str_replace("{username}",$row['username'],$ii);
    //$ii = str_replace("{intno}",$row['intno'],$ii);	//dial to sip
    $ii = str_replace("{intno}",Dial2Sip($row['dial']),$ii);	//dial to sip
    $in[$row['peer']]= $ii;	//убрать дубляжи по полю peer
    
    $ib = '';
    $ib = $inbound;
    $ib = str_replace("{org}",$row['org'],$ib);
    $ib = str_replace("{peer}",$row['peer'],$ib);
    $ib = str_replace("{tel}",$row['tel'],$ib);
    $ib = str_replace("{username}",$row['username'],$ib);
    $ib = str_replace("{intno}",Dial2Sip($row['dial']),$ib);	//dial to sip
    $inb[$row['peer']]= $ib;
    
    //out_context
    $oo = '';
    $oo = $out_context;
    
    //repout
    $q1 = "select * from options where pid=".$row['id']." and type='repout'";
    $r1 = _mysql_query($q1);
    $n1 = mysql_num_rows($r1);
    if($n1)
    {
	$row1 = mysql_fetch_array($r1);
	$oo = $row1['text'];
	//$oo = str_replace("{tel}",$row['tel'],$oo);
    }
    //else
    {
	$oo = str_replace("{tel}",$row['tel'],$oo);
	$oo = str_replace("{oid}",$row['oid'],$oo);
	$oo = str_replace("{pid}",$row['pid'],$oo);
	$oo = str_replace("{org}",$row['org'],$oo);
	$oo = str_replace("{peer}",$row['peer'],$oo);
    }
    //$oo = str_replace("{tel}",$row['tel'],$oo);
    //$out.=$oo;
    $out[$row['peer']]= $oo;
}

$ii = '';
foreach($in as $val) $ii.=$val;
$ext_conf = str_replace("{in_context}",$ii,$ext_conf);
$oo = '';
foreach($out as $val) $oo.=$val;
$ext_conf = str_replace("{out_context}",$oo,$ext_conf);

$ib = '';
foreach($inb as $val) $ib.=$val;
$ext_conf = str_replace("{inbound}",$ib,$ext_conf);

$ext_econf = nl2br($ext_conf);
require("./mods/regex.inc.php");
$ext_econf = preg_replace($rx_head,$ht_head,$ext_econf);
$ext_econf = preg_replace($rx_comment,$ht_comment,$ext_econf);
$ext_econf = preg_replace($rx_macro,$ht_macro,$ext_econf);
$ext_econf = preg_replace($rx_sip,$ht_sip,$ext_econf);
$ext_econf = preg_replace($rx_esip,$ht_esip,$ext_econf);
$ext_econf = preg_replace($rx_peer,$ht_peer,$ext_econf);
$ext_econf = preg_replace($rx_user,$ht_user,$ext_econf);
$ext_econf = preg_replace($rx_ext,$ht_ext,$ext_econf);
$ext_econf = preg_replace($rx_exten,$ht_exten,$ext_econf);

$hret = '';
$hret.= '
    <div style="font-size: 10pt; border: #ACA899 solid 1px; padding-left: 18px; background: #ECE9D8">
	<div style="background: #FFFFFF; border-left: #808080 solid 1px; padding-left: 14px;">
';
$hret.=$ext_econf;

$hret.= '</div></div>';

$hret.= '<pre style="font-size: 8pt; background: #d0d0d0; padding: 3px; border: solid 1px #000000">';
$hret.=$ext_conf;
$file_ext = $ext_conf;
$hret.='</pre>';


function Dial2Sip($dial)
{
    $sip = '';
    //Dial(type1/identifier1[&type2/identifier2[&type3/identifier3... ] ], timeout, options, URL)
    //     type1/identifier1[&type2/identifier2]
    
    $aa = preg_split("/\|/",$dial);
    
    $sip = 'SIP/'.$aa[0];
    $n = count($aa);
    for($i=1;$i<$n;$i++)
	$sip.= '&'.'SIP/'.$aa[$i];
    //print_r($aa);
    
    
    return $sip;
}

?>









