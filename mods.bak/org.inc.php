<?php


switch($act)
{
    case 'edit':
    case 'add':
	if($do)
	{
	    $name = addslashes($_POST['name']);
	    $body = 'Добавлена: '.stripslashes($name);
	    $q = "insert into org values(0,'$name')";
	    $r = _mysql_query($q);
	    $body.= '<br><a href="./">Назад</a>';
	}else
	{
	    $body = '';
	    $body.= '
		<form action="?gr=org&act=add&do=1" method="post">
		    Название: <input type="Text" name="name" value=""><br>
		    Используется: <select name="on">
			<option value="0" SELECTED>No
			<option value="1">Yes
		    </select>
		    <input type="Submit" value="'.$act.'">
		</form>
	    ';
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
	$body.= '<br><br>';
	
	//$q = "select * from org";
	$q = "select o.id,o.name, GROUP_CONCAT(p.name SEPARATOR ',') as pname from org as o left join peers as p on o.id=p.oid group by o.id order by o.name";
	$q = "
	    select
		o.id,o.name, GROUP_CONCAT(DISTINCT p.name order by p.name SEPARATOR ',') as pname, GROUP_CONCAT(DISTINCT u.intno order by u.intno SEPARATOR ',') as uname
	    from org as o left join peers as p on o.id=p.oid left join users as u on o.id=u.oid
		group by o.id order by o.name";
	//$q = "select o.id,o.name, p.name as pname from org as o left join peers as p on o.id=p.oid left join users as u on o.id=u.oid group by u.id";
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	
	//echo $q;
	$body.= '<table border="0" cellpadding="1" cellspacing="1" bgcolor="#cccccc">';
	$body.= '<tr><th bgcolor="#e0e0e0">Организация</th><th bgcolor="#e0e0e0">peer</th><th bgcolor="#e0e0e0">intno</th></tr>';
	for($i=0;$i<$n;$i++)
	{
	    $row = mysql_fetch_array($r);
	    $id = $row['id'];
	    $body .= '<tr>
			<td bgcolor="#eeeeee"><a href="?gr=user&oid='.$id.'">'.$row['name'].'</a></td>
			<td bgcolor="#eeeeee">'.$row['pname'].'</td>
			<td bgcolor="#eeeeee">'.$row['uname'].'</td>
		    </tr>';
	}
	$body.= '</table>';
	$body.= '<a href="?gr=org&act=add">Добавить организацию</a>';
}

?>



