<?php
//Провайдеры телефонии
$id = 0;
$name = '';

switch($act) {
    case 'edit':
        $id = get_var('id');
        $q = "select name from pt where id=$id";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        list($name) = mysql_fetch_array($r);
    case 'add':
        if($do) {
            $id = post_var('id');
            $action = post_var('action');
            $name = post_var('name');

            if($action == 'add') {
                $body = 'Добавлен провайдер: '.$name;
                $q = "insert into pt values(0,'$name')";
                $r = _mysql_query($q);
            }
            if($action == 'edit') {
                $body = 'Изменен провайдер: '.$name;
                $q = "update pt set name='$name' where id=$id";
                $r = _mysql_query($q);
            }
            $body.= tag_br().tag_a("?gr=$gr",'Назад');
        }else{
            $body = '';
            $body.= '
		<form action="?gr='.$gr.'&act=add&do=1" method="post">
		<table>
		    <tr><td>Name</td><td><input type="Text" name="name" value="'.$name.'"></td></tr>
		</table>
		<input type="Hidden" name="action" value="'.$act.'">
		<input type="Hidden" name="id" value="'.$id.'">
		<input type="Submit" value="'.$act.'"></form>
	    ';
        }
        break;
    case 'view':
    default:

        $body.= 'Провайдеры телефонии';
        $body.= tag_br();

        $q = "select * from pt order by name";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= '<a href="?gr='.$gr.'&act=add">Добавить провайдера</a>';
        if($n) {
            $body.= tag_t_o_('maintbl');
            $body.= tag_tr();
            $body.= tag_th('name');
            $body.= tag_tr(1);
            for($i=0;$i<$n;$i++) {
                $row = mysql_fetch_array($r);
                $id = $row['id'];
                $body.= tag_tr();
                $body.= tag_td('<a href="?gr='.$gr.'&act=edit&id='.$id.'">'.$row['name'].'</a>',$i);
                $body.= tag_tr(1);
            }
            $body.= '</table>';
        }else
            $body.= '<br>Провайдеры отсутствуют';
}

?>