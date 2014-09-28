<?php

$id = 0;
$oid = 0;
$name = '';
$str = '';
$comment = '';

switch($act)
{
    case 'edit':
	$id = $_GET['id'];
	$q = "select * from filter where id=$id";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	if($n)
	{
	    $row = mysql_fetch_array($r);
	    $name = $row['name'];
	    $oid = $row['oid'];
	    $str = $row['str'];
	    $comment = $row['comment'];
	}
	else
	{
	}
    case 'add':
	if($do)
	{
	    $id = $_POST['id'];
	    $action = $_POST['action'];
	    $oid = $_POST['oid'];
	    $name = $_POST['name'];
	    $str = $_POST['str'];
	    $comment = $_POST['comment'];
	    
	    if($action=='add')
	    {
		$body = 'Добавлен пир: '.$name;
		$q = "insert into peers values(0,$oid,'$name','$str','$comment')";
		$r = _mysql_query($q);
	    }
	    if($action=='edit')
	    {
		$body = 'Изменен пир: '.$name;
		//$q = "insert into peers values(0,$oid,'$name','$tel','$host','$username','$secret')";
		$q = "update peers set oid=$oid, name='$name', str='$str', comment='$comment' where id=$id";
		$r = _mysql_query($q);
	    }
	    $body.= '<br><a href="?gr='.$gr.'&oid='.$oid.'">Назад</a>';
	}else
	{
	    $body = '';
	    $body.= '
		<form action="?gr='.$gr.'&act=add&do=1" method="post">
		<table>
		    <tr><td>name</td><td><input type="Text" name="name" value="'.$name.'"></td></tr>
		    <tr><td>str</td><td><input type="Text" name="tel" value="'.$str.'"></td></tr>
		    <tr><td>comment</td><td><input type="Text" name="host" value="'.$comment.'"></td></tr>
		</table>
		<input type="Hidden" name="action" value='.$act.'>
		<input type="Hidden" name="id" value='.$id.'>
		<input type="Hidden" name="oid" value='.$oid.'>
		<input type="Submit" value="'.$act.'"></form>
	    ';
	}
	break;
    case 'view':
    default:
	$body = '';
	
	$oid = $_GET['oid'];
	$q = "select * from filter where oid=$oid ";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	
	$body.= '<a href="?gr='.$gr.'&act=add">Добавить filter</a>';
	
	if($n)
	{
	    $body.= '<table cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">';
	    $body.= '<tr><th bgcolor="#E0E0E0" width="150">name</th><th bgcolor="#E0E0E0" width="80">str</th>
		<th bgcolor="#E0E0E0" width="150">comment</th>
	    </tr>';
	    for($i=0;$i<$n;$i++)
	    {
		$row = mysql_fetch_array($r);
		$id = $row['id'];
		$body .= '<tr><td bgcolor="#EEEEEE"><a href="?gr=peer&act=edit&id='.$row['id'].'">'.$row['name'].'</a></td>
			<td bgcolor="#EEEEEE" align="right">'.$row['str'].'</td>
			<td bgcolor="#EEEEEE" align="right">'.$row['comment'].'</td>
		    </tr>';
	    }
	    $body.= '</table>';
	}else
	{
	    $body.= '<br>фильтров не зарегистрировано';
	}
}



?>