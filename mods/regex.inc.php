<?php

//[name]
$rx_head	= "/(?:^|\n)\[([-\w]+)\]/";
$ht_head	= "<a name=\"$1\"><b><font color=\"blue\">[$1]</font></b></a>";

//;comment
$rx_comment	= "/(;.*)/";
$ht_comment	= "<b><font color=\"green\">$1</font></b>";

//peer:id:name
$rx_peer	= "/\[(peer):(\d+):([\w\s\p{L}\p{Zs}\"]+)\]/";
$ht_peer	= "<i><a href=\"?gr=$1&act=edit&id=$2\">$3</a></i>";

//user:id:name
$rx_user	= "/\[(user):(\d+):([\w\s\p{L}\p{Zs}\"]+)\]/";
$ht_user	= "<i><a href=\"?gr=$1&oid=$2\">$3</a></i>";

//context:name
$rx_context	= "/\[(context):(ip\w+)\]/";
$ht_context	= "<i><a href=\"#$2\">$2</a></i>";

//Macro()
$rx_macro	= "/Macro\((\w+),(.*)\)/";
$ht_macro	= "Macro(<a href=\"#macro-$1\">$1</a>,$2)";

//SIP/xxxx  SIP/xxxx&SIP/xxxx
$rx_sip		= "/(SIP\/(\d+))/";
$ht_sip		= "<a href=\"?gr=sip#$2\">$1</a>";

//SIP/ipxxxxa/
$rx_esip	= "/(SIP\/(ip\w+))/";
$ht_esip	= "<a href=\"?gr=sip#$2\">$1</a>";

//context=context
$rx_csip	= "/context=(\w+)/";
$ht_csip	= "context=<b><a href=\"?gr=ext#$1\">$1</a></b>";

$rx_ext		= "/\[csip:(inbound)\]/";
$ht_ext		= "<b><a href=\"?gr=ext#$1\">$1</a></b>";

//exten => ext,pr,text
$rx_exten	= "/(exten => )([-\[\]_.\w\(\)]+),([\d+|n]),(.*)/";
$ht_exten	= "$1$2,$3,$4";

$rx_comment_skip= "/;;.*\n/";
$ht_comment_skip= "";

?>









