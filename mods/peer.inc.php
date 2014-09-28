<?php

$id = 0;
$name = '';
$tel = '';
$host = '';
$username = '';
$secret = '';
$oid = 0;
$nid = 0;
$ban = 0;
$call_limit=10;

$table = "peers";
$tbl = new tbl($table);

switch($act) {
    case 'edit':
        $id = get_var('id');
        $q = "select id,name,tel,host,username,secret,oid,nid,ban,call_limit from peers where id=$id";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        list($id,$name,$tel,$host,$username,$secret,$oid,$nid,$ban,$call_limit) = mysql_fetch_array($r);
    case 'add':
        if($do) {
            list($id,$name,$tel,$host,$username,$secret,$oid,$nid,$ban,$call_limit) =
                    array(post_var('id',0), post_var('name'), post_var('tel'),
                        post_var('host'), post_var('username'),post_var('secret'),
                        post_var('oid',0), post_var('nid',0), post_var('ban',0),post_var('call_limit',10));

            $action =   post_var('action');

            if($action=='add') {
                $body = 'Добавлен пир: '.$name;
                //$q = "insert into peers values(0,$oid,'$name','$tel','$host','$username','$secret')";
                $q = $tbl->insert(array(0,$oid,$name,$tel,$host,$username,$secret,$nid,$ban,$call_limit));
                $r = _mysql_query($q);
            }
            if($action=='edit') {
                $body = 'Изменен пир: '.$name;
                //$q = "insert into peers values(0,$oid,'$name','$tel','$host','$username','$secret')";
                //$q = "update peers set oid=$oid, name='$name', tel='$tel', host='$host', username='$username', secret='$secret' where id=$id";
                $q = $tbl->update(array('id'=>$id),
                    array(0,$oid,$name,$tel,$host,$username,$secret,$nid,$ban,$call_limit));
                $r = _mysql_query($q);
            }
            $body.= tag_br().tag_a("?gr=$gr",'Назад');
        }else {
            $body = '';
            $body.= '<form action="?gr='.$gr.'&act=add&do=1" method="post">';
            $body.= tag_t_o();
            $body.= tag_tr(2,tag_td('name')     .tag_td(tag_f_in('name', $name)));
            $body.= tag_tr(2,tag_td('tel')      .tag_td(tag_f_in('tel', $tel)));
            $body.= tag_tr(2,tag_td('host')     .tag_td(tag_f_in('host', $host)));
            $body.= tag_tr(2,tag_td('username') .tag_td(tag_f_in('username', $username)));
            $body.= tag_tr(2,tag_td('secret')   .tag_td(tag_f_in('secret', $secret)));
            $body.= tag_tr(2,tag_td('org')      .tag_td(tag_f_se('oid', $oid, db_sel('org', 'name', 'id', 'id=0 or'))));
            $body.= tag_tr(2,tag_td('Номер')    .tag_td(tag_f_se('nid', $nid, db_sel('num_pool', 'no', 'id', 'id=0 or'))));
            $body.= tag_tr(2,tag_td('Забанен?') .tag_td(tag_f_ch('ban', 1, $ban, '')));
            $body.= tag_tr(2,tag_td('call_limit') .tag_td(tag_f_in('call_limit', $call_limit)));
            $body.= tag_f_hi('action', $act);
            $body.= tag_f_hi('id', $id);
            $body.= tag_t_c();
            $body.= tag_f_sm($id, array("Добавить","Изменить"));
            $body.= '</form>';
            if($act == 'edit') $body.= tag_a("?gr=opts&pid=$id",'Опции');
        }
        break;
    case 'view':
    default:
        $body = 'Ниже представлены точки монтирования к нашему(нашим) провайдеру(провайдерам) телефонии<br>';

        /*$q = "select
                p.id as id, p.name as name, p.username as username,p.tel as tel, p.host as host,o.name as org
              from peers as p, org as o
              where p.oid=o.id
              order by p.name";*/
        $q = "select
                p.id as id, p.name as name, p.username as username,p.tel as tel, p.host as host,o.name as org,
                pn.no as pno, p.nid as nid, p.ban, p.call_limit, p.oid as oid
              from peers as p, org as o, num_pool as pn
              where p.oid=o.id and pn.id=p.nid
              order by p.name";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= tag_a("?gr=$gr&act=add",'Добавить peer');

        if($n) {
            $body.= tag_t_o_('maintbl');
            $body.= tag_tr();
            $body.= tag_th('name'       ,'',"#E0E0E0",'150');
            $body.= tag_th('peer'       ,'',"#E0E0E0",'150');
            $body.= tag_th('tel'        ,'',"#E0E0E0",'110');
            $body.= tag_th('username'   ,'',"#E0E0E0",'150');
            $body.= tag_th('host'       ,'',"#E0E0E0",'150');
            $body.= tag_th('org'        ,'',"#E0E0E0",'110');
            $body.= tag_tr(1);
            $pids = '';
            for($i=0;$i<$n;$i++) {
                //$row = mysql_fetch_array($r);
                list($id,$name,$un,$tel,$host,$org,$pno,$nid,$ban,$call_limit,$oid) = mysql_fetch_array($r);
                $pids.= $id.'|';
                $pname = $name;
                $tel = ast_get_tel($tel, $pno, $id, $nid);
                $tel = tag_b($tel, !$nid);
                $name = ast_get_tel($name, $pno, $id, $nid);
                
                $body.= tag_tr();
                $body.= tag_td(tag_s($pname,$ban), 1, '', "#EEEEEE");
                $body.= tag_td(tag_s(tag_a("?gr=peer&act=edit&id=".$id, $name),$ban), 1, '', "#EEEEEE");
                $body.= tag_td($tel      , 1, 'right', "#EEEEEE");
                $body.= tag_td($un       , 1, 'right', "#EEEEEE");
                $body.= tag_td($host     , 1, 'right', "#EEEEEE");
                $body.= tag_td(tag_b($org,!$oid)      , 1, 'right', "#EEEEEE");
                $body.= tag_tr(1);
            }
            $body.= tag_t_c();
            $pids = str_strip_last($pids);
            $body.= tag_a("?gr=stat&pid=$pids",'Статистика по всем пирам');
        }else
            $body.= tag_br().'пиров не зарегистрировано';
        
}


function OrgList($sel) {
    //echo $sel;
    $q = "select * from org order by id";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);

    $html = '';

    $html.='<select name="oid">';
    for($i=0;$i<$n;$i++) {
        $row = mysql_fetch_array($r);
        if($sel == $row['id']) $selected = ' SELECTED';
        else $selected = '';
        $html .= '<option'.$selected.' value="'.$row['id'].'">'.$row['name'];
    }
    $html.= '</select>';
    return $html;
}




?>