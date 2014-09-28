<?php

$id = 0;
$prid = 0;
$name = '';
$kod = '';
$cost = '';

switch($act)
{
    case 'csv_add':
	if($do)
	{
	    $csv = $_POST['csv'];
	    $pt = $_POST['pt'];
	    $f = fopen("tmp.csv","w");
	    if($f){
		fwrite($f,$csv);
		fclose($f);
	    }
	    
	    //$row = 1;
	    $qn = 0;
	    $f = fopen("tmp.csv", "r");
	    while (($data = fgetcsv($f, 1000, ":")) !== FALSE) {
		if($data[0]==0) continue;
		//$q = "====insert into price values(0,$pt,$data[0],$data[1],$data[2],$data[3])";
		//echo $q.'<br>';
		$aa = preg_split("/, /", $data[2], -1, PREG_SPLIT_NO_EMPTY);
		foreach($aa as $kod)
		{
		    $cost = str_replace(",",".",$data[3]);
		    $q = "insert into price values(0,$pt,'$data[0]','$data[1]','$kod','$cost')";
		    $r = mysql_query($q);
		    //echo $q.'<br>';
		    $qn++;
		}
	    }
	    $body.= 'Добавлено '.$qn.' записей в '.$pt;
	    fclose($f);
	    //$csv_a = str_getcsv($csv,':');
	    //echo $csv;
	}
	else
	{
	    $body = 'Вставьте содержимое CSV файла';
	    $body.= '<form action="?gr='.$gr.'&act='.$act.'&do=1" method="POST">';
	    $body.= PrTelList().'<br>';
	    $body.= '<textarea name="csv" rows="60" cols="120"></textarea>';
	    $body.= '<br><input type="submit">';
	    $body.= '</form>';
	}
	break;
    /*case 'edit':
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
	}*/
    case 'add':
	//if($do)
	{
	    //$id = $_POST['id'];
	    //$action = $_POST['action'];
	    //$oid = $_GET['oid'];
	    $name = addslashes($_POST['name']);
	    $kod = addslashes($_POST['kod']);
	    $cost = addslashes($_POST['cost']);
	    //$pid = $_POST['pid'];
	    
	    $cost = str_replace(",",".",$cost);
	    
	    $aa = preg_split("/, /", $kod, -1, PREG_SPLIT_NO_EMPTY);
	    //echo count($aa);
	    //print_r($aa);
	    
	    //if($action == 'add')
	    foreach($aa as $k)
	    {
		//$body = 'Добавлена точка: '.$intno;
		$q = "insert into price values(0,0,0,'$name','$k','$cost')";
		//echo $q."<br>";
		$r = _mysql_query($q);
	    }
	    /*if($action == 'edit')
	    {
		$body = 'Изменена точка: '.$intno;
		//$q = "insert into users values(0,$oid,'$intno','$secret',$pid)";
		$q = "update users set oid=$oid, intno='$intno', secret='$secret', pid='$pid' where id=$id";
		//echo $q;
		$r = _mysql_query($q);
	    }*/
	    //$body.= '<br><a href="?gr=user&oid='.$oid.'">Назад</a>';
	}/*else
	{
	    $oid = $_GET['oid'];
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
	}*/
	//break;
    case 'view':
    default:
	$body = 'Цены на телефонию: ';
	$body.= '<a href="?gr='.$gr.'&act=csv_add">Добавить как csv файл</a>';
	$body.= '<br>';
	
	$body.= '
		<form action="?gr='.$gr.'&act=add" method="post">
		<table>
		    <tr><td>направление</td><td>Код</td><td>цена</td></tr>
		    <tr>
		    <td><input type="Text" name="name" value="'.$name.'"></td>
		    <td><input type="Text" name="kod" value="'.$kod.'"></td>
		    <td><input type="Text" name="cost" value="'.$cost.'"></td>
		    <td><input type="Submit" value="Добавить"></td>
		    </tr>
		</table>
		</form>
	    ';
	
	$q = "select id,prid,name,GROUP_CONCAT(kod order by kod separator ', ') as kod, cost from price group by name order by prid,name";
	//echo $q;
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	
	//$body.= '<a href="?gr='.$gr.'&act=add">Добавить цену</a>';
	if($n)
	{
	    $zone = 0;
	    $body.= '<table cellpadding="1" cellspacing="1" bgcolor="#CCCCCC" width="600">
		<tr><th bgcolor="#E0E0E0" width="30">pr</th><th bgcolor="#E0E0E0" width="180">Направление</th><th bgcolor="#E0E0E0">код</th><th bgcolor="#E0E0E0">цена</th></tr>
	    ';
	    for($i=0;$i<$n;$i++)
	    {
		$row = mysql_fetch_array($r);
		if($zone!=$row['prid'])
		{
		    $body.='<tr><td colspan="3">'.ZoneName($row['prid']).'</td></tr>';
		    $zone = $row['prid'];
		}
		$bg = ' bgcolor="#EEEEEE"';
		
		$id = $row['id'];
		$body .= '<tr><td'.$bg.'>'.($row['prid']).'</td><td'.$bg.'>'.$row['name'].'</td><td'.$bg.'>'.$row['kod'].'</td><td'.$bg.' align="right">'.$row['cost'].'</td></tr>';
	    }
	    $body.= '</table>';
	}else
	{
	    $body.= '<br>мы работаем бесплатно :)';
	}
	
	/*require("./mods/tree.inc.php");
	$tree = Tree();
	echo '<pre>';
	print_r($tree);
	echo '</pre>';*/
}


function ZoneName($n)
{
    $zn = array(
	1 => "1, Специальное предложение Россия",
	2 => "2, Россия сотовые",
	3 => "",
	4 => "4, Россия зона 1 М,О, до 100 км,",
	5 => "5, Россия зона 1 М,О, свыше 100 км,",
	6 => "6, Россия зона 2",
	7 => "7, Россия зона 3",
	8 => "8, Россия зона 4",
	9 => "9, Россия зона 5",
	10 => "10, Россия зона 6",
	11 => "11, СНГ и страны Балтии",
	12 => "12, Европа",
	13 => "13, Азия",
	14 => "14, Африка",
	15 => "15, Австралия и Океания",
	16 => "16, Северная Америка",
	17 => "17, Южная Америка"
    );
    return $zn[$n];
}

function PrTelList($sel=0)
{
    $html = '';
    //лист провайдеров телефонии
    $q = "select * from pt where id>0";
    $r = mysql_query($q);
    $n = mysql_num_rows($r);
    
    $html.= '<select name="pt">';
        $html.= '<option value="0">test';
    for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	$html.= '<option value="'.$row['id'].'">'.$row['name'];
    }
    $html.= '</select>';
    return $html;
}

?>
















