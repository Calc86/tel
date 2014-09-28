<?php

$id = 0;
$name = '';
$cost = '';

switch($act) {
    case 'edit':
        $id = get_var('id');
        $q = "select * from dtypes where id=$id";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        if($n) {
            $row = mysql_fetch_array($r);
            $name = $row['name'];
            $cost = $row['cost'];
        }
    case 'add':
        if($do) {
            $id = post_var('id');
            $action = post_var('action');
            $name = post_var('name');
            $cost = post_var('cost');

            if($action == 'add') {
                $body = 'Добавлено оборудование: '.$name;
                $q = "insert into dtypes values(0,'$name','$cost')";
                $r = _mysql_query($q);
            }
            if($action == 'edit') {
                $body = 'Изменено оборудование: '.$name;
                $q = "update dtypes set name='$name', cost='$cost' where id=$id";
                $r = _mysql_query($q);
            }
            $body.= tag_br().tag_a("?gr=$gr",'Назад');
        }else {
            $body = '';
            $body.= '
		<form action="?gr='.$gr.'&act=add&do=1" method="post">
		'.tag_t_o(0,'top').'
		    '.tag_tr(2,tag_td('Name').tag_td(tag_f_in('name',$name))).'
                    '.tag_tr(2,tag_td('Cost').tag_td(tag_f_in('cost',$cost))).'
                '.tag_t_c().'
                '.tag_f_hi('action',$act).'
                '.tag_f_hi('id',$id).'
                '.tag_f_sm($id, array("Добавить","Изменить")).'
		</form>';
        }
        break;
    case 'view':
    default:

        $body.= 'Типы оборудования';
        $body.= tag_br();

        $q = "select * from dtypes order by name";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= tag_a("?gr=$gr&act=add",'Добавить тип оборудования');
        if($n) {
            $body.= tag_t_o_('maintbl');
            $body.= tag_tr();
            $body.= tag_th('name');
            $body.= tag_th('cost');
            $body.= tag_tr(1);
            for($i=0;$i<$n;$i++) {
                $row = mysql_fetch_array($r);
                $id = $row['id'];
                $body .= tag_tr();
                $body .= tag_td(tag_a("?gr=$gr&act=edit&id=$id",$row['name']),$i);
                $body .= tag_td($row['cost'],$i);
                $body .= tag_tr(1);
            }
            $body.= tag_t_c();
        }else
            $body.= tag_br().'Оборудование отсутствует';
}

?>