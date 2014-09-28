<?php

$fs = array();
$fs['id'] = 0;
$fs['dtype'] = 3;
$fs['oid'] = 0;
$fs['ip'] = '';
$fs['mac'] = '';
$fs['serial'] = '';
$fs['soft_ver'] = '';
$fs['hard_ver'] = '';
$fs['user'] = '';
$fs['admin'] = '';

$title = 'Список оборудования';
$table = 'devices';
$sort = get_var('sort','serial');
if(!isset($fs[$sort])) $sort = 'serial';
$act = get_var('act');
$id = get_var('id',post_var('id',0));
$oid = get_var('oid',get_var('oid',0));

$dtypes = db_sel1('dtypes');

switch($act) {
    case 'edit':
        $q = "select * from devices where id=$id";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        if(!$do) if($n) $fs = mysql_fetch_array($r);
        $oid = $fs['oid'];
    case 'add':
        if($do) {
            foreach($fs as $k => $v)
                $fs[$k] = post_var($k,$v);
            
            if($act == 'add') {
                $qp = '';
                foreach ($fs as $k=>$v) $qp.= "'$v',";
                $qp = substr($qp, 0, -1);
                $q = "insert into $table values($qp)";
            }
            if($act == 'edit') {
                $qp = '';
                foreach ($fs as $k=>$v)
                    if($k=='id') continue;
                    else $qp.= "$k='$v',";

                $qp = substr($qp, 0, -1);
                $q = "update $table set $qp where id=$id";
            }
            //echo $q;
            $r = _mysql_query($q);
            $body.= tag_br().tag_a("?gr=$gr",'Назад');
        }else {
            $body = '<a href="./upg-pap2t-5-1-6.exe">upd</a><br>';
            $body.= '
		<form action="?gr='.$gr.'&act='.$act.'&do=1" method="post">
		'.tag_t_o(0,'top').'
                    '.tag_tr(2,tag_td('dtype').
                            tag_td(tag_f_se('dtype',$fs['dtype'],$dtypes))).'
                    '.tag_tr(2,tag_td('oid').
                            tag_td(tag_f_se('oid',$oid,db_sel1('org')))).'
                    '.tag_tr(2,tag_td('ip').
                            tag_td(tag_f_in('ip',$fs['ip']))).'
                    '.tag_tr(2,tag_td('mac').
                            tag_td(tag_f_in('mac',$fs['mac']))).'
                    '.tag_tr(2,tag_td('serial').
                            tag_td(tag_f_in('serial',$fs['serial']))).'
                    '.tag_tr(2,tag_td('soft_ver').
                            tag_td(tag_f_in('soft_ver',$fs['soft_ver']))).'
                    '.tag_tr(2,tag_td('hard_ver').
                            tag_td(tag_f_in('hard_ver',$fs['hard_ver']))).'
                    '.tag_tr(2,tag_td('user').
                            tag_td(tag_f_in('user',$fs['user']))).'
                    '.tag_tr(2,tag_td('admin').
                            tag_td(tag_f_in('admin',$fs['admin']))).'
                '.tag_t_c().'
                '.tag_f_hi('action',$act).'
                '.tag_f_hi('id',$id).'
                '.tag_f_sm($id, array("Добавить","Изменить")).'
		</form>';
        }
        break;
    case 'view':
    default:

        $body.= $title;
        $body.= tag_br();

        /*$q = "
            select
                d.id, dt.name as dtype, o.name, d.ip, d.mac, d.serial,
                d.soft_ver, d.hard_ver, d.user, d.admin
            from
                $table as d
                left join dtypes as dt on dt.id=d.dtype 
                left join org as o on o.id=d.oid
            order by d.$sort";*/
        $q = "
            select
                d.id, dt.name as dtype, o.name, d.ip, d.mac, d.serial,
                d.soft_ver, d.hard_ver, d.user, d.admin, o.id as oid
            from
                org as o
                left join $table as d on o.id=d.oid
                left join dtypes as dt on dt.id=d.dtype
            order by d.$sort";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= tag_a("?gr=$gr&act=add",'Добавить оборудование');
        $body.= tag_a("?gr=dtypes",'Типы оборудования');
        if($n) {
            $body.= tag_t_o_('maintbl');
            $body.= tag_tr();
            $body.= tag_th(sp());
            foreach($fs as $k=>$v)
                $body.= tag_th(tag_a("?gr=$gr&sort=$k",$k));
            $body.= tag_th("reg");
            $body.= tag_th("online");
            $body.= tag_tr(1);
            for($i=0;$i<$n;$i++) {
                $fs = mysql_fetch_array($r, MYSQL_ASSOC);
                $id = $fs['id'];
                $oid = $fs['oid'];
                $body .= tag_tr();
                //$body .= tag_td(tag_a("?gr=$gr&act=edit&id=$id",$fs['name']),$i);
                //$body .= tag_td($row['cost'],$i);
                $body .= tag_td(
                            text_show($id,
                                tag_a("?gr=$gr&act=edit&id=$id",'edit'),
                                tag_a("?gr=$gr&act=add&oid=$oid",'add')
                            )
                        );
                foreach($fs as $k=>$v)
                    $body .= tag_td($v);
                $body .= tag_td(y_n(ip_register($fs['ip'])));
                $body .= tag_td(y_n(ip_online($fs['ip'])));
                $body .= tag_tr(1);
            }
            $body.= tag_t_c();
            $body.= $n.' записей';
        }else
            $body.= tag_br().'Оборудование отсутствует';
}

function ip_register($ip) {
    $file = "/var/www/html/ext/cron/map";
    $map = file_get_contents($file);
    $aa = preg_split("/\n/",$map);
    foreach($aa as $line){
        $bb = preg_split("/ /",$line,-1, PREG_SPLIT_NO_EMPTY);
        //print_r($bb);
        if(isset($bb[1]))
            if($bb[1]==$ip) return 1;
    }
    return 0;
}

function ip_online($ip) {
    $file = "/var/www/html/ext/cron/out";
    $map = file_get_contents($file);
    $aa = preg_split("/\n/",$map);
    foreach($aa as $line){
        $bb = preg_split("/ /",$line,-1, PREG_SPLIT_NO_EMPTY);
        //print_r($bb);
        if(isset($bb[0]))
            if($bb[0]==$ip)
                if($bb[2]=='alive')
                    return 1;
    }
    return 0;
}

?>