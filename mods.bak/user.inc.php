<?php

$id = 0;
//$name = '';
//$tel = '';
//$host = '';
//$username = '';
$secret = '';
$oid = '';
$pid = '';
$intno = '';

switch($act)
{
    case 'edit':
	$id = $_GET['id'];
	$q = "select * from users where id=$id";
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	if($n)
	{
	    $row = mysql_fetch_array($r);
	    $oid = $row['oid'];
	    $intno = $row['intno'];
	    $secret = $row['secret'];
	    $pid = $row['pid'];
	}
    case 'add':
	if($do)
	{
	    $id = addslashes($_POST['id']);
	    $action = addslashes($_POST['action']);
	    $oid = $_GET['oid'];
	    $intno = addslashes($_POST['intno']);
	    $secret = addslashes($_POST['secret']);
	    $pid = addslashes($_POST['pid']);
	    
	    if($action == 'add')
	    {
		$body = 'Добавлена точка: '.$intno;
		$q = "insert into users values(0,$oid,'$intno','$secret',$pid)";
		$r = _mysql_query($q);
	    }
	    if($action == 'edit')
	    {
		$body = 'Изменена точка: '.$intno;
		//$q = "insert into users values(0,$oid,'$intno','$secret',$pid)";
		$q = "update users set oid=$oid, intno='$intno', secret='$secret', pid='$pid' where id=$id";
		//echo $q;
		$r = _mysql_query($q);
	    }
	    $body.= '<br><a href="?gr=user&oid='.$oid.'">Назад</a>';
	}else
	{
	    $oid = $_GET['oid'];
	    if($act=='add')
	    {
		$iter = 3-strlen($oid);
		$intno = "1";
		for($i=0;$i<$iter;$i++)
		    $intno.="0";
		$intno.=$oid."0";
		for($i=strlen($intno)-1;$i>=0;$i--)
		{
		    $secret.=$intno[$i];
		}
	    }
	    $body = '';
	    $body.= '
		<form action="?gr='.$gr.'&act=add&do=1&oid='.$oid.'" method="post">
		<table>
		    <tr><td>oid</td><td>'.$oid.'</td></tr>
		    <tr><td>intno</td><td><input type="Text" name="intno" value="'.$intno.'"></td></tr>
		    <tr><td>secret</td><td><input type="Text" name="secret" value="'.$secret.'"></td></tr>
		    <tr><td>peer</td><td>'.PeerList($oid,$pid).'</td></tr>
		</table>
		<input type="Hidden" name="action" value="'.$act.'">
		<input type="Hidden" name="id" value="'.$id.'">
		<input type="Submit" value="'.$act.'"></form>
	    ';
	}
	break;
    case 'view':
    default:
	$oid = $_GET['oid'];
	
	$q = "select * from org where id=$oid";
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	if($n)
	{
	    $row = mysql_fetch_array($r);
	    $body.='<h2>Организация: '.$row['name'].'</h2>';
	}
	
	$body.= 'Точки монтирования со стороны пользователей (сколько телефонных аппаратов стоит у данной организации)';
	$body.= '<br>';
	
	$q = "select * from users where oid=$oid";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	
	$body.= '<a href="?gr='.$gr.'&act=add&oid='.$oid.'">Добавить точку монтирования</a>';
	if($n)
	{
	    $body.= '<table>';
	    for($i=0;$i<$n;$i++)
	    {
		$row = mysql_fetch_array($r);
		$id = $row['id'];
		$body .= '<tr><td><a href="?gr=user&act=edit&id='.$id.'&oid='.$oid.'">'.$row['intno'].'</a></td></tr>';
	    }
	    $body.= '</table>';
	}else
	{
	    $body.= '<br>У данной организации нет установленных точек';
	}
	//список ее пиров:
	$body .= '<hr>';
	$body .= 'PEERы (внешние номера, закрепленные за данной организацией)';
	$q = "select * from peers where oid=$oid";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	
	if($n)
	{
	    $body.= '<table>';
	    for($i=0;$i<$n;$i++)
	    {
		$row = mysql_fetch_array($r);
		$id = $row['id'];
		$body .= '<tr><td><a href="?gr=peer&act=edit&id='.$row['id'].'">'.$row['name'].'</a></td></tr>';
	    }
	    $body.= '</table>';
	}else
	{
	    $body.= 'У данной организации нет установленных пиров';
	}
	
	$body.='<br>
		<hr width="120" align="left">
	    <div style="margin-left: 18px;"><font size="-2" face="Arial">Структура:<br>
	    1. Peer 1-------* номер; на 1 peer может вешаться несколько номеров, <br>
	    2. Если извне приходит звонок на peer, то звонят все номера, закрепленные за данным пиром<br>
	    3. На организации может быть закреплено несколько пиров,<br>
	    4. В зависимости от настроек провайдера на 2 peerа может приходить как 2 разных номера, так и один номер в две линии.
	    (Пример: номер 967-7581, пиры: 14001 14002, 6 линий; Пир 14001, номера 205,206,207, Пир 14002, номера 210)
	    </font></div>
	';
}

function PeerList($oid,$pid)
{
    $html = '';
    
    $q = "select * from peers where oid=$oid";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    
    $bsel = 0;
    $html = '<select name="pid">';
    
    if(!$pid)
	$html.= '<option SELECTED value="0">Не выбран';
    for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	
	if($pid == $row['id'])
	{
	    $selected = ' SELECTED';
	    $bsel = 1;
	}
	else $selected = '';
	
	$html.= '<option'.$selected.' value="'.$row['id'].'">'.$row['name'];
    }
    if(!$bsel && $pid)
	$html.= '<option SELECTED value="'.$pid.'">Старый PEER';
    $html.= '<option value="0">Не выбран';
    $html.= '</select>';
    
    return $html;
}





?>