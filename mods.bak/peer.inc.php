<?php

$id = 0;
$name = '';
$tel = '';
$host = '';
$username = '';
$secret = '';
$oid = 0;

switch($act)
{
    case 'edit':
	$id = $_GET['id'];
	$q = "select * from peers where id=$id";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	if($n)
	{
	    $row = mysql_fetch_array($r);
	    $name = $row['name'];
	    $tel = $row['tel'];
	    $host = $row['host'];
	    $username = $row['username'];
	    $secret = $row['secret'];
	    $oid = $row['oid'];
	}
	else
	{
	}
    case 'add':
	if($do)
	{
	    $id = addslashes($_POST['id']);
	    $action =  addslashes($_POST['action']);
	    $oid =  addslashes($_POST['oid']);
	    $name =  addslashes($_POST['name']);
	    $tel =  addslashes($_POST['tel']);
	    $host =  addslashes($_POST['host']);
	    $username =  addslashes($_POST['username']);
	    $secret =  addslashes($_POST['secret']);
	    
	    if($action=='add')
	    {
		$body = 'Добавлен пир: '.$name;
		$q = "insert into peers values(0,$oid,'$name','$tel','$host','$username','$secret')";
		$r = _mysql_query($q);
	    }
	    if($action=='edit')
	    {
		$body = 'Изменен пир: '.$name;
		//$q = "insert into peers values(0,$oid,'$name','$tel','$host','$username','$secret')";
		$q = "update peers set oid=$oid, name='$name', tel='$tel', host='$host', username='$username', secret='$secret' where id=$id";
		$r = _mysql_query($q);
	    }
	    $body.= '<br><a href="?gr='.$gr.'">Назад</a>';
	}else
	{
	    $body = '';
	    $body.= '
		<form action="?gr='.$gr.'&act=add&do=1" method="post">
		<table>
		    <tr><td>name</td><td><input type="Text" name="name" value="'.$name.'"></td></tr>
		    <tr><td>tel</td><td><input type="Text" name="tel" value="'.$tel.'"></td></tr>
		    <tr><td>host</td><td><input type="Text" name="host" value="'.$host.'"></td></tr>
		    <tr><td>username</td><td><input type="Text" name="username" value="'.$username.'"></td></tr>
		    <tr><td>secret</td><td><input type="Text" name="secret" value="'.$secret.'"></td></tr>
		    <tr><td>org</td><td>'.OrgList($oid).'</td></tr>
		</table>
		<input type="Hidden" name="action" value='.$act.'>
		<input type="Hidden" name="id" value='.$id.'>
		<input type="Submit" value="'.$act.'"></form>
	    ';
	    if($act == 'edit') $body.= '<a href="?gr=opts&pid='.$row['id'].'">Опции</a>';
	}
	break;
    case 'view':
    default:
	$body = 'Ниже представлены точки монтирования к нашему(нашим) провайдеру(провайдерам) телефонии<br>';
	
	$q = "select p.id as id, p.name as name,p.tel as tel, p.host as host,o.name as org from peers as p, org as o where p.oid=o.id";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	
	$body.= '<a href="?gr='.$gr.'&act=add">Добавить peer</a>';
	
	if($n)
	{
	    $body.= '<table cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">';
	    $body.= '<tr><th bgcolor="#E0E0E0" width="150">peer</th><th bgcolor="#E0E0E0" width="80">tel</th>
	    <th bgcolor="#E0E0E0" width="150">host</th>
	    <th bgcolor="#E0E0E0" width="80">org</th>
	    </tr>';
	    for($i=0;$i<$n;$i++)
	    {
		$row = mysql_fetch_array($r);
		$id = $row['id'];
		$body .= '<tr><td bgcolor="#EEEEEE"><a href="?gr=peer&act=edit&id='.$row['id'].'">'.$row['name'].'</a></td>
			<td bgcolor="#EEEEEE" align="right">'.$row['tel'].'</td>
			<td bgcolor="#EEEEEE" align="right">'.$row['host'].'</td>
			<td bgcolor="#EEEEEE" align="right">'.$row['org'].'</td>
		    </tr>';
	    }
	    $body.= '</table>';
	}else
	{
	    $body.= '<br>пиров не зарегистрировано';
	}
}


function OrgList($sel)
{
    //echo $sel;
    $q = "select * from org order by id";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    
    $html = '';
    
    $html.='<select name="oid">';
    for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	if($sel == $row['id']) $selected = ' SELECTED';
	else $selected = '';
	$html .= '<option'.$selected.' value="'.$row['id'].'">'.$row['name'];
    }
    $html.= '</select>';
    return $html;
}




?>