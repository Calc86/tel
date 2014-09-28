<?php



switch($act) {
    case 'edit':
    case 'add':
        if($do) {
            $name = addslashes($_POST['name']);
            $body = 'Добавлена: '.stripslashes($name);
            $q = "insert into org values(0,'$name','$name','$name','',0,'$name')";
            $r = _mysql_query($q);
            $body.= tag_br().tag_a("./","Назад");
        }else {
            $body = '';
            $body.= '
		<form action="?gr=org&act=add&do=1" method="post">
		    Название: '.tag_f_in("name").tag_br().'
                    Логин: '.tag_f_in("login").tag_br().'
                    Пароль: '.tag_f_in("passwd").tag_br().'
                    Используется: '.tag_f_se('on', 0, array("No","Yes")).tag_br().'
		    '.tag_f_sm(0, array($act)).'
		</form>';
        }
        break;
    case 'view':
    default:
        $body = '';
        $body.= 'Ниже приведены организации, которые добавлены в систему. "Свободные пиры" это отстойник для незанятых внешних номеров.
	    <br>Полный цикл добавления в систему:
	    <br>1) Добавляем организацию (ООО МХ Телеком) (Для нашего учета)
	    <br>2) Добавляем пир, при добавлении выбираем добавленную организацию (name: dinet1 или номер телефона) (данные, от провадера телефонии)
	    <br>3) Добавляем точки монтирования телефонов для организации (XXXXX, пример 10001,10205...) (Данные, которые выдаем нашим клиентам)
	    ';
        $body.= tag_br(2);

        $q = "
	    select
		o.id,o.name, o.money,
                GROUP_CONCAT(DISTINCT p.name order by p.name SEPARATOR ',') as pname,
                GROUP_CONCAT(DISTINCT u.intno order by u.intno SEPARATOR ',') as uname,
                o.login as login
	    from org as o
            left join peers as p on o.id=p.oid
            left join users as u on o.id=u.oid
		group by o.id order by o.login";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= tag_a("?gr=org&act=add",'Добавить организацию');
        $body.= tag_t_o_('maintbl');
        $body.= tag_tr();
        $body.= tag_th('Организация');
        $body.= tag_th('Логин');
        $body.= tag_th('peer');
        $body.= tag_th('intno');
        $body.= tag_th('Деньги');
        $body.= tag_tr(1);
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            $id = $row['id'];
            if($id==53) continue;
            $body .= tag_tr();
            $body .= tag_td(tag_a("?gr=user&oid=$id",$row['name']));
            $body .= tag_td(tag_a("?gr=user&oid=$id",$row['login']));
            $body .= tag_td($row['pname']);
            $body .= tag_td($row['uname']);
            $body .= tag_td(tag_a("?gr=money&oid=$id",money($row['money'])), 1, 'right', "#eeeeee");
            $body .= tag_tr(1);
        }
        $body.= tag_t_c();
}

?>