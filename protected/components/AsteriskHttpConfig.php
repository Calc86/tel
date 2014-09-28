<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 28.09.14
 * Time: 21:54
 */

class AsteriskHttpConfig extends CComponent {
    private $text = "";

    /**
     * @param $text string
     */
    function __construct($text)
    {
        $this->text = $text;
    }

    function __toString()
    {
        //[name]
        $rx_head	= "/(?:^|\n)\[([-\w]+)\]/";
        $ht_head	= "<a name=\"$1\"><b><font color=\"blue\">[$1]</font></b></a>";

        //;comment
        $rx_comment	= "/(;.*)/";
        $ht_comment	= "<b><font color=\"green\">$1</font></b>";

        //peer:id:name
        $rx_peer	= "/\[(peer):(\d+):([\w\s\p{L}\p{Zs}\"]+)\]/";
        $ht_peer	= "<i><a href=\"?r=peers/view&id=$2\">$3</a></i>";

        //user:id:name
        $rx_user	= "/\[(user):(\d+):([\w\s\p{L}\p{Zs}\"]+)\]/";
        $ht_user	= "<i><a href=\"r=users/index&id=2&id=$2\">$3</a></i>";

        //context:name
        $rx_context	= "/\[(context):(ip\w+)\]/";
        $ht_context	= "<i><a href=\"#$2\">$2</a></i>";

        //Macro()
        $rx_macro	= "/Macro\((\w+),(.*)\)/";
        $ht_macro	= "Macro(<a href=\"#macro-$1\">$1</a>,$2)";

        //SIP/xxxx  SIP/xxxx&SIP/xxxx
        $rx_sip		= "/(SIP\/(\d+))/";
        $ht_sip		= "<a href=\"?r=config/sip#$2\">$1</a>";

        //SIP/ipxxxxa/
        $rx_esip	= "/(SIP\/(ip\w+))/";
        $ht_esip	= "<a href=\"?r=config/sip#$2\">$1</a>";

        //context=context
        $rx_csip	= "/context=(\w+)/";
        $ht_csip	= "context=<b><a href=\"?r=config/ext#$1\">$1</a></b>";

        $rx_ext		= "/\[csip:(inbound)\]/";
        $ht_ext		= "<b><a href=\"?r=config/ext#$1\">$1</a></b>";

        //exten => ext,pr,text
        $rx_exten	= "/(exten => )([-\[\]_.\w\(\)]+),([\d+|n]),(.*)/";
        $ht_exten	= "$1$2,$3,$4";

        $rx_comment_skip= "/;;.*\n/";
        $ht_comment_skip= "";

        $ext = nl2br($this->text);
        $ext = preg_replace($rx_head,$ht_head,$ext);
        $ext = preg_replace($rx_comment_skip,$ht_comment_skip,$ext);
        $ext = preg_replace($rx_comment,$ht_comment,$ext);
        $ext = preg_replace($rx_macro,$ht_macro,$ext);
        $ext = preg_replace($rx_sip,$ht_sip,$ext);
        $ext = preg_replace($rx_esip,$ht_esip,$ext);
        $ext = preg_replace($rx_peer,$ht_peer,$ext);
        $ext = preg_replace($rx_user,$ht_user,$ext);
        $ext = preg_replace($rx_ext,$ht_ext,$ext);
        $ext = preg_replace($rx_exten,$ht_exten,$ext);

        return $ext;
    }
}