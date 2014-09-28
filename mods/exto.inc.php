<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Просмотр оригинального файла конфига
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$f = fopen("/etc/asterisk/extensions.conf","r");
$ext_conf = '';
while(!feof($f))
    $ext_conf .= fread($f,1024);
fclose($f);

//$ext_conf = str_replace("{inbound}",$ib,$ext_conf);

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


/*function Dial2Sip($dial)
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
}*/

?>









