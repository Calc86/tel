<?php
/* * * * * * * * * *
 * Author: Calc, calc@list.ru, 8(916) 506-7002
 * mxtel, 2010, Calc (r)
 * * * * * * * * * */
defined("INDEX") or die('404 go to <a href="/">index.php</a>');

$do = get_var('do',0);
$id = get_var('id',0);
$pid = get_var('pid',0);
$type= get_var('type','port');  //get for ajah
$oid = get_var('oid',0);
$table = 'inbound_opts';
$tbl = new tbl($table);

// id oid pid type tid

list($oid, $pid, $type, $tid) =
            array(
                post_var('oid',$oid), post_var('pid',$pid),
                post_var('type',$type), post_var('tid'));
switch($do) {
    case 1: //добавляем элемент
        _mysql_query($tbl->insert(
                array(0, $oid, $pid, $type, $tid)));
        redir("gr=$gr&oid=$oid&pid=$pid",1);
        break;
    case 2:
        $hide = $_GET['hide'];
        if($hide!=1) $hide=0;
        _mysql_query($tbl->update_k(array('id'=>$id), array('hide'=>$hide)));
        redir("gr=$gr&oid=$oid&pid=$pid",1);
        break;
    case 10:
        _mysql_query($tbl->update(array('id'=>$id),
                array(0, $oid, $pid, $type, $tid)));
        redir("gr=$gr&oid=$oid&pid=$pid",1);
        break;
    case 11:
        do_del($table,$id,"gr=$gr&oid=$oid&pid=$pid",'','',1);
        break;
    case 'ajah_tid':
        $ret = '';
        $tbl = $type;
        if($type=='port'){ $tbl = 'users'; $n = 'intno';}
        $ret.= tag_f_se('tid', $tid, db_sel1($tbl, $n, '', "where oid=$oid"));
        echo $ret;
        exit();
        break;
    case 'ajah_main':
        $ret = maintbl();
        echo $ret;
        exit();
        break;
}

$tid = 0;

if($id) {
    $q = "select * from $table where id=$id";
    $r = mysql_query($q);
    list($id,$oid, $pid,$type,$tid) = mysql_fetch_array($r);
}

$int_a = db_sel('users','intno','',"oid=$oid and ");
$int_a[0] = 'Внешний';
$peer_a = db_sel('peers','','',"oid in($oid,24) and ");
//$peer_a1 = $peer_a2;
$peer_a[0] = 'Все';
$body.='
<form action="?gr='.$gr.'&oid='.$oid.'&pid='.$pid.'&do='.( ($id==0) ? 1 : 10 ).'&id='.$id.'" method="post">
    Пир:        '.tag_f_se('pid',$pid, $peer_a,'pid').'
    Тип:        '.tag_f_se('type',$type,$r_types,'type',1).'
    Значение:   <span id="tid">'.tag_f_se('tid',$tid,db_sel1('users', 'intno','',$w="where oid=$oid")).'</span>
                '.tag_f_sm($id).'
                '.tag_f_hi('oid',$oid).'
</form>';

// запрос для формы ajah
$jq.= "
    
    $(\"#pid\").change(function() {
        val = $(\"#pid\").val();
        url = './?gr=$gr&oid=$oid&pid=' + val + '&do=ajah_main';
        $(\"#main\").text(\"\").load(url);
    });
    $(\"#type\").change(function() {
        val = $(\"#type\").val();
        url = './?gr=$gr&oid=$oid&pid=$pid&do=ajah_tid&type=' + val;
        $(\"#tid\").text(\"\").load(url);
    });
";

$body.= tag_a("?gr=user&oid=$oid",'Назад');

$body.= '<div id="main">';
$body.= maintbl();
$body.= '</div>';

function maintbl() {
    global $gr,$oid,$pid,$table;

    $q = "  select
            io.id, io.oid, io.pid, io.type, io.tid,
            p.name, o.name
        from
            $table as io
            left join peers as p on p.id=io.pid
            left join org as o on o.id=io.oid
        where io.pid=$pid
        order by io.type";
    //echo $q;
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    $body = '';
    $body.= tag_t_o_('maintbl');
    $body.= tag_tr();
    $body.= tag_th('id');
    $body.= tag_th('oid');
    $body.= tag_th('pid');
    $body.= tag_th('type');
    $body.= tag_th('tid');
    $body.= tag_th('x');
    $body.= tag_tr(1);

    $exten = '';

    if($n) {
        for($i=0;$i<$n;$i++) {
            list($id, $oid, $pid, $type, $tid, $pname, $oname) = mysql_fetch_array($r);

            $body.= tag_tr();
            $body.= tag_td(tag_a("?gr=$gr&oid=$oid&pid=$pid&id=$id",$id),$i);
            $body.= tag_td($oname,$i);
            $body.= tag_td($pname,$i);
            $body.= tag_td($type,$i);
            $body.= tag_td(db_field('users', $tid, 'intno'),$i);
            $body.= tag_td(tag_a_x("?gr=$gr&oid=$oid&pid=$pid&id=$id&do=11"),$i);
            $acc = '';
            $body.= tag_tr(1);
        }
    }
    $body.= '<td colspan="9">'.$n.' записей</td>';
    $body.= tag_t_c();
    $exten = file_get_contents("./tmp/ext.inbound");
    $body.= '<pre>';
    $body.= $exten;
    $body.= '</pre>';
    $body.= 'Входящая маршрутизация устанавливает макрос, который будет обслуживать
        входящий вызов. Если макрос не указан, то вызов будет поступать на все
        локальные номера, закрепленные за пиром.';
    return $body;
}
