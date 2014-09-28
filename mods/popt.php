<?php
/* * * * * * * * * *
 * Author: Calc, calc@list.ru, 8(916) 506-7002
 * mxtel, 2010, Calc (r)
 * * * * * * * * * */
defined("INDEX") or die('404 go to <a href="/">index.php</a>');

$do = get_var('do',0);
$id = get_var('id',0);
$pid = get_var('pid',0);
$oid = get_var('oid',0);

$table = 'dial_opts';
$tbl = new tbl($table);
// id oid pid rule prior internal
switch($do) {
    case 1: //добавляем элемент
        list($oid, $pid1, $pid2, $rule, $prior, $int, $cx) =
                array(
                post_var('oid',0), post_var('pid1'), post_var('pid2'), post_var('rule',1),
                post_var('prior') , post_var('int'), post_var('cx'));
        _mysql_query($tbl->insert(
                array(0,$oid, $pid1, $pid2, $rule, $prior, $int, $cx)));
        redir("gr=$gr&oid=$oid&pid=$pid",1);
        break;
    case 2:
        $hide = $_GET['hide'];
        if($hide!=1) $hide=0;
        $id = get_var('id');
        _mysql_query($tbl->update_k(array('id'=>$id), array('hide'=>$hide)));
        redir("gr=$gr&oid=$oid&pid=$pid",1);
        break;
    case 10:
        list($oid, $pid1, $pid2, $rule, $prior, $int, $cx) =
                array(
                post_var('oid',0), post_var('pid1'), post_var('pid2'), post_var('rule',1),
                post_var('prior') , post_var('int'), post_var('cx'));
        $id = get_var('id');
        _mysql_query($tbl->update(array('id'=>$id),
                array(0,$oid, $pid1, $pid2, $rule, $prior, $int, $cx)));
        redir("gr=$gr&oid=$oid&pid=$pid",1);
        break;
    case 11:
        $id = get_var('id');
        do_del($table,$id,"gr=$gr&oid=$oid&pid=$pid",'','',1);
        break;
}

//$id = 0;
//$oid = 0;
//$pid = 0;
$rule = '';
$prior = 0;
$int = 0;
$pid1 = $pid;
$pid2 = $pid;
$cx = 'dialc';

if($id) {
    $q = "select * from $table where id=$id";
    $r = mysql_query($q);
    list($id, $oid, $pid1, $pid2, $rule, $prior, $int, $cx) = mysql_fetch_array($r);
}

$int_a = db_sel('users','intno','',"oid=$oid and ");
$int_a[0] = 'Внешний';
$peer_a = db_sel('peers','','',"oid in($oid,24) and ");
//$peer_a1 = $peer_a2;
$peer_a[0] = 'Все';
$body.='
<form action="?gr='.$gr.'&oid='.$oid.'&pid='.$pid.'&do='.( ($id==0) ? 1 : 10 ).'&id='.$id.'" method="post">
    Пир1:        '.tag_f_se('pid1',$pid1, $peer_a ).'
    Пир2:        '.tag_f_se('pid2',$pid2, $peer_a ).'
    Правило:    '.tag_f_in('rule',$rule).'
    Приоритет:  '.tag_f_in('prior',$prior).'
    Внутренний: '.tag_f_se('int',$int, $int_a).'
    Макрос      '.tag_f_se('cx',$cx,array('dialc'=>'dialc', 'out1'=>'out1', 'deny'=>'deny')).'
                '.tag_f_sm($id).'
                '.tag_f_hi('oid',$oid).'
</form>';

$body.= tag_a("?gr=user&oid=$oid",'Назад');
$body.= tag_t_o_('maintbl');
$body.= tag_tr();
$body.= tag_th('id');
$body.= tag_th('oid');
$body.= tag_th('pid1');
$body.= tag_th('pid2');
$body.= tag_th('rule');
$body.= tag_th('prior');
$body.= tag_th('int');
$body.= tag_th('cx');
$body.= tag_th('x');
$body.= tag_tr(1);

$q = "  select
            do.id, o.id, o.name, p1.name as name1, p2.name as name2, do.rule, do.prior, do.internal, u.intno, do.context,
            p2.id as pid2, pn2.no as pno2, p2.nid as nid2,
            p1.id as pid1, pn1.no as pno1, p1.nid as nid1, p1.tel as tel1
        from $table as do
        left join peers as p1 on p1.id=do.pid1
        left join peers as p2 on p2.id=do.pid2
        left join users as u on u.id=do.internal
        left join org as o on o.id=do.oid
        left join num_pool as pn1 on pn1.id=p1.nid
        left join num_pool as pn2 on pn2.id=p2.nid
        where do.oid=$oid and (do.pid1=$pid or do.pid1=0)
        order by do.rule";
//echo $q;
$r = _mysql_query($q);
$n = mysql_num_rows($r);

$exten = '';

if($n) {
    for($i=0;$i<$n;$i++) {
        list($id, $oid, $oname, $pname1, $pname2, $rule, $prior, $int, $int1, $cx, $pid2, $pno2, $nid2, $pid1, $pno1, $nid1, $tel1) = mysql_fetch_array($r);
        $pname1 = ast_get_name($pname1, $pno1, $pid1, $nid1);
        $pname2 = ast_get_name($pname2, $pno2, $pid2, $nid2);

        $body.= tag_tr();
        $body.= tag_td(tag_a("?gr=$gr&oid=$oid&pid=$pid&id=$id",$id),$i);
        $body.= tag_td($oname,$i);
        $body.= tag_td($pname1,$i);
        $body.= tag_td($pname2,$i);
        $body.= tag_td($rule,$i);
        $body.= tag_td($prior,$i);
        //$body.= tag_td($int,$i);
        $body.= tag_td($int1,$i);
        $body.= tag_td($cx,$i);
        $body.= tag_td(tag_a_x("?gr=$gr&oid=$oid&pid=$pid&id=$id&do=11"),$i);
        $acc = '';
        $body.= tag_tr(1);

        $sip = '';
        if($int!=0) $sip = $int1;
        else $sip = 'ip'.$tel1;
        $exten.= "exten => $rule,1,Macro($cx,\${EXTEN},$sip,\${TRUNK_OPTS})".tag_br();
        $exten.= "exten => $rule,n,Busy".tag_br();
    }
}
$body.= '<td colspan="9">'.$n.' записей</td>';
$body.= tag_t_c();
$body.= '<pre>';
$body.= $exten;
$body.= 'exten => _X.,1,Macro(dialc,${EXTEN},ip{tel},${TRUNK_OPTS})'.tag_br();
$body.= 'exten => _X.,n,Busy'.tag_br();
$body.= '</pre>';
?>
