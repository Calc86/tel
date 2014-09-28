<?php

$ext_conf = '';
$out_context = '';
$in_context = '';
$inbound = '';

//основной файл extensions.conf
$ext_conf = file_get_contents("./tmp/ext.tmp.conf");
//уже не используется
$in_context = file_get_contents("./tmp/ext.in_context");
//контекст для исходящих звонков
$out_context = file_get_contents("./tmp/ext.out_context");
//общий контекст для входящих
$inbound = file_get_contents("./tmp/ext.inbound");

$q = "  select
            o.id as oid, o.name as org, u.intno,
            GROUP_CONCAT(DISTINCT u.intno ORDER BY u.intno SEPARATOR '|') as dial,
            p.id as pid, p.name as peer, p.tel as tel, p.id as id,
            p.username as username, p.nid as nid, pn.no as pno
        from
            org as o,
            users as u,
            peers as p,
            num_pool as pn
        where
            o.id=u.oid and o.id=p.oid and u.pid=p.id and p.nid=pn.id
        group by p.id
        order by u.intno
        ";
//echo $q;
$r = _mysql_query($q);
$n = mysql_num_rows($r);

$in = '';
$out = '';
$inb = '';

//добавить в цикл возможность звонков на несколько клиентов одного пира.
//по идее {dial} заменяем на {dial}$i и создаем массив $dial[$i], считаем пиры и дописываем.
for($i=0;$i<$n;$i++) {
    //$row = mysql_fetch_array($r);
    list($oid,$org,$intno,$dial,$pid,$peer,$tel,$id,$un,$nid,$pno) = mysql_fetch_array($r);

    //in_context
    $ii = '';
    //$ii = $in_context;    //обработка входящие переехала на inbound
    //echo $in_context;

    if($nid) $tel = $pno.'_'.$pid;
    if($nid) $peer = 'p'.$pno.'_'.$pid;

    // $ii = str_replace("{oid}",$oid,$ii);  //уже не используется
    // $ii = str_replace("{org}",$org,$ii);  //уже не используется
    // $ii = str_replace("{pid}",$pid,$ii);  //уже не используется
    // $ii = str_replace("{peer}",$peer,$ii);//уже не используется
    // $ii = str_replace("{tel}",$tel,$ii);  //уже не используется
    //$ii = str_replace("{username}",$un,$ii);//уже не используется
    //$ii = str_replace("{intno}",$row['intno'],$ii);	//dial to sip
    //$ii = str_replace("{intno}",Dial2Sip($dial),$ii);	//dial to sip//уже не используется
    $in[$peer]= $ii;	//убрать дубляжи по полю peer

    $ib = '';
    $ib = $inbound;
    $ib = str_replace("{org}",$org,$ib);     //Noop({org}-p6639958)
    $ib = str_replace("{peer}",$peer,$ib);   //Noop(phone_autoterm-{peer})
    //$ib = str_replace("{tel}",$tel,$ib);   //уже не используется
    $ib = str_replace("{tel}",$tel,$ib);   //используется для определения контекста под исходящую связь
    $ib = str_replace("{username}",$un,$ib);  //exten => {username},1,Noop(phone_autoterm-p6639958)
    $ib = str_replace("{intno}",Dial2Sip($dial),$ib);	//dial to sip   //exten => 100004,n,Macro(dialip,{intno},t)
    $ib = str_replace("{ibr}","ip",$ib);        //временная заглушка для входящей маршрутизации
    $inb[$peer]= $ib;

    //out_context
    $oo = '';
    $oo = $out_context;

    //repout
    /*$q1 = "select * from options where pid='$id' and type='repout'";
    $r1 = _mysql_query($q1);
    $n1 = mysql_num_rows($r1);
    if($n1) {
        $row1 = mysql_fetch_array($r1);
        $oo = $row1['text'];
        //$oo = str_replace("{tel}",$row['tel'],$oo);
    }*/
    //else

    //dial_opts_tbl;
    $dial_opts = '';
    $qo = " select
                do.pid1, do.pid2, do.rule, do.internal,
                p.tel, p.nid, pn.no as pno, u.intno as intno, do.context
            from
                dial_opts as do
                left join users as u on u.id=do.internal
                left join peers as p on p.id=do.pid2
                left join num_pool as pn on pn.id=p.nid
            where do.pid1=$pid or (do.oid=$oid and do.pid1=0)";     //ларисины правила пошли к зебре
    //echo $qo.'<br>';
    $ro = _mysql_query($qo);
    $no = mysql_num_rows($ro);
    if($no) $dial_opts = nl();
    for($j=0;$j<$no;$j++) {
        list($pid1, $pid2, $rule, $int, $tel2, $nid2, $pno2, $intno2, $cx) = mysql_fetch_array($ro);
        $exten_var = '${EXTEN}';
        $tel2 = ast_get_tel($tel2, $pno2, $pid2, $nid2);
        $rr = split("\|", $rule);
        if(count($rr)>1){   //имеем | в правиле _9|8
            $rule = str_replace("|", "", $rule);
            $e_o = strlen($rr[0])-1;   //смещение в EXTEN
            $exten_var = '${EXTEN:'.$e_o.'}';
        }
        
        $sip = '[Need sip!!!]';
        if($pid2==$pid) $sip = 'ip'.$tel;
        else if($pid2==0 && $int==0) $sip = 'ip'.$tel;
        else if($int) $sip = $intno2;
        else $sip = 'ip'.$tel2;

        if($int!=0) $dial_opts.= "exten => $rule,1,Noop($org-internal-$sip)".nl();
        else $dial_opts.= "exten => $rule,1,Noop($org-external-$sip)".nl();

        if($int==0) $dial_opts.= "exten => $rule,n,Macro($cx,$exten_var,$sip,\${TRUNK_OPTS})".nl();
        else $dial_opts.= "exten => $rule,n(startdial),Dial(SIP/$sip,100,tT)".nl();
        //$dial_opts.= "exten => $rule,n(startdial),Dial(SIP/$sip,100,tT)".nl();

        $dial_opts.= "exten => $rule,n,Busy".nl();
    }
    //$exten.= "exten => $rule,1,Macro(dialc,\${EXTEN},$sip,\${TRUNK_OPTS})".tag_br();
    {
        $oo = str_replace("{tel}",$tel,$oo);    //контекст [c{tel}], канал ip{tel}
        $oo = str_replace("{oid}",$oid,$oo);    //коммент  [user:{oid}:phone_autoterm]
        $oo = str_replace("{pid}",$pid,$oo);    //коммент  [peer:{pid}:p6639975]
        $oo = str_replace("{org}",$org,$oo);    //коммент  [user:24:{org}]
        $oo = str_replace("{peer}",$peer,$oo);  //коммент  [peer:12:{peer}]
        $oo = str_replace("{dial_opts}",$dial_opts,$oo);  // {dial_opts}
    }
    //$oo = str_replace("{tel}",$row['tel'],$oo);
    //$out.=$oo;
    $out[$peer]= $oo;
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
$ext_econf = preg_replace($rx_comment_skip,$ht_comment_skip,$ext_econf);
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


function Dial2Sip($dial) {
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









