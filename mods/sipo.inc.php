<?php

$sip_conf = '';
$f = fopen("/etc/asterisk/sip.conf","r");
while(!feof($f))
    $sip_conf.=fread($f,1024);
fclose($f);

$sip_econf = nl2br($sip_conf);
$sip_econf = str_replace("<br />","(new_line)",$sip_econf);
require("./mods/regex.inc.php");
$sip_econf = preg_replace($rx_head,$ht_head,$sip_econf);
$sip_econf = preg_replace($rx_comment,$ht_comment,$sip_econf);
$sip_econf = preg_replace($rx_peer,$ht_peer,$sip_econf);
$sip_econf = preg_replace($rx_user,$ht_user,$sip_econf);
$sip_econf = preg_replace($rx_context,$ht_context,$sip_econf);
$sip_econf = preg_replace($rx_csip,$ht_csip,$sip_econf);

$hret = '';
$hret.= '
    <div style="font-size: 10pt; border: #ACA899 solid 1px; padding-left: 18px; background: #ECE9D8;">
	<div style="background: #FFFFFF; border-left: #808080 solid 1px; padding-left: 14px;">
';
$lines = preg_split("/\(new_line\)/",$sip_econf);
//print_r($lines);

$hret.= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size: 8pt;">';
$last = '';
$topen = 0;
//echo 'preg_match("/\[*\]/",$line)';
//echo '<br>';
//echo '<br>';
//echo preg_match("/\[*\]/",'<a name="general"><b><font color="blue">[general]</font></b></a>');
foreach($lines as $line)
{
    $bg = '';
    if(preg_match("/\[*\]/",$line) || preg_match("/;register/",$line)){
	if($topen) $hret.='</td></tr></table></td></tr>';
	$topen = 0;
	$bg = ' bgcolor="#dddddd"';
    }
    if($last != ''){
	if(preg_match("/\[*\]/",$last)){
	    if($topen) $hret.='</td></tr></table></td></tr>';
	    $hret.= '<tr><td><table cellpadding="0" cellspacing="0" border="1" style="font-size: 8pt;">';
	    $topen = 1;
	}
    }
    $hret.= '<tr height="20"><td'.$bg.'>';
    $hret.=$line;
    $hret.= '</td></tr>';
    $last = $line;
    
    
}
if($topen) $hret.='</td></tr></table></td></tr>';
$hret.= '</table>';

//$hret.=$sip_econf;

$hret.= '</div>
    </div>';

//вывод
$hret.= '<pre style="font-size: 8pt; background: #d0d0d0; padding: 3px; border: solid 1px #000000;">';
$hret.=$sip_conf;
$file_sip = $sip_conf;
$hret.='</pre>';

?>









