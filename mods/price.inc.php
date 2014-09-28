<?php
//2579 msm
//require("./mods/zones.inc.php");

$id = 0;
$zone = '';
$prid = 0;
$name = '';
$kod = '';
$cost = '';
$pt = get_var('pt',0);
//
//echo get_var('pt',0);

//print_r($_GET);
//echo $pt;
switch($act) {
    case 'csv_add':
        if($do) {
            $csv = post_var('csv');
            $pt = post_var('pt');
            $a10 = post_var('a10',0);
            $s7 = post_var('s7',0);
            $f = fopen("tmp.csv","w");
            if($f) {
                fwrite($f,$csv);
                fclose($f);
            }
            $qn = 0;

            $aa = preg_split("/\r\n/", $csv);   //разбиваем на строки
            $lines = array();
            foreach($aa as $line)
                array_push($lines,preg_split("/;/", $line));    //разбиваем по полям
            foreach($lines as $data) {
                if($data[0]=="0" || $data[0]=="") continue;
                $aa = preg_split("/, /", $data[2], -1, PREG_SPLIT_NO_EMPTY);
                $name = addslashes(str_replace('"','',stripslashes($data[1])));
                foreach($aa as $kod) {
                    if($s7) $kod = Strip7($kod);
                    if($a10) $kod= Add10($kod);
                    $cost = str_replace(",",".",$data[3]);
                    $q = "insert into price values(0,$pt,'$data[0]','$name','$kod','$cost')";
                    $r = mysql_query($q);
                    echo $q.'<br>';
                    $qn++;
                }
            }
            $body.= 'Добавлено '.$qn.' записей в '.$pt;
            fclose($f);
        }
        else {
            $body = 'Вставьте содержимое CSV файла (Фотмар: разделитель ";", ст1-номер, ст2-имя, ст3-код[коды, разделитель ", "], ст4-цена)';
            $body.= '<form action="?gr='.$gr.'&act='.$act.'&do=1" method="POST">';
            $body.= PrTelList();
            $body.= tag_f_ch('s7', '1', 0, 'Strip 7');
            $body.= tag_f_ch('a10', '1', 0, 'Add 10');
            $body.= tag_br();
            $body.= '<textarea name="csv" rows="60" cols="120"></textarea>';
            $body.= '<br><input type="submit">';
            $body.= '</form>';
        }
        break;
    case 'add':
    //if($do)
        {
            //$id = $_POST['id'];
            //$action = $_POST['action'];
            //$oid = $_GET['oid'];
            $zone = addslashes($_POST['zone']);
            $name = addslashes($_POST['name']);
            $kod = addslashes($_POST['kod']);
            $cost = addslashes($_POST['cost']);
            $pt = post_var('pt',0);
            //$pid = $_POST['pid'];

            $cost = str_replace(",",".",$cost);

            $aa = preg_split("/, /", $kod, -1, PREG_SPLIT_NO_EMPTY);
            //echo count($aa);
            //print_r($aa);

            //if($action == 'add')
            foreach($aa as $k) {
                //$body = 'Добавлена точка: '.$intno;
                $q = "insert into price values(0,'$pt','$zone','$name','$k','$cost')";
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
    }
    //break;
    case 'view':
    default:
        $body = 'Цены на телефонию: ';
        $body.= tag_a("?gr=$gr&act=csv_add",'Добавить как csv файл');
        $body.= tag_br();

        $body.= 'Провайдеры: ';
        $pts = db_sel('pt', 'name', 'id', 'id=0 or');
        foreach($pts as $k=>$v)
            $body.= tag_a("?gr=$gr&pt=$k",$v).' ';
        $body.= tag_br();

        $cur_month = date("Y-m-1 00:00:00");
        $q = "select kod,count(*) as cnt from stat where kod<0 and cd>='$cur_month' and direction='out' and cause='ANSWERED' group by kod";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            $body.= $row[0].' '.$row[1].'<br>';
        }
        
        $body.= '<a href="http://www.kody.su/mobile/989">Коды</a><br>';
        
        $q = "select dst from stat where kod=-3 and cd>='$cur_month' and direction='out' and cause='ANSWERED'";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            $body.= $row[0].'<br>';
        }
        

        //$q = "select max(id) from price";
        //$r = mysql_query($q);
        //$row = mysql_fetch_array($r);
        //echo $row[0];

        //$body.= '123';
        $body.= '<br>';

        $body.= '
		<form action="?gr='.$gr.'&act=add" method="post">
		<table>
		    <tr>'.tag_td('prov').'<td>pr</td><td>направление</td><td>Код</td><td>цена</td></tr>
		    <tr>
                    '.tag_td(PrTelList($pt)).'
		    <td><input type="Text" name="zone" value="'.$zone.'" size="2"></td>
		    <td><input type="Text" name="name" value="'.$name.'"></td>
		    <td><input type="Text" name="kod" value="'.$kod.'" size="10"></td>
		    <td><input type="Text" name="cost" value="'.$cost.'" size="10"></td>
		    <td><input type="Submit" value="Добавить"></td>
		    </tr>
		</table>
		</form>
	    ';

        $q = "  select
                    id,prid,name,
                    GROUP_CONCAT(kod separator ', ') as kod,
                    cost
                from price
                where ptid=$pt
                group by name,cost
                order by prid,name";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        //$body.= '<a href="?gr='.$gr.'&act=add">Добавить цену</a>';
        if($n) {
            $zone = 0;
            //$body.= '<table cellpadding="1" cellspacing="1" bgcolor="#CCCCCC" width="800">
            $body.= tag_t_o_(array('id'=>'maintbl','width'=>'800'));
            $body.= '<tr><th bgcolor="#E0E0E0" width="30">pr</th><th bgcolor="#E0E0E0" width="300">Направление</th><th bgcolor="#E0E0E0">код</th><th bgcolor="#E0E0E0">цена</th></tr>
	    ';
            for($i=0;$i<$n;$i++) {
                $row = mysql_fetch_array($r);
                if($zone!=$row['prid']) {
                    $body.='<tr><td colspan="4">'.ZoneName($row['prid'],$pt).'</td></tr>';
                    $zone = $row['prid'];
                }
                $bg = ' bgcolor="#EEEEEE"';

                $id = $row['id'];
                $body .= '<tr><td'.$bg.'>'.($row['prid']).'</td>
		    <td'.$bg.'>'.$row['name'].'</td>
		    <td'.$bg.'>'.$row['kod'].'</td>
		    <td'.$bg.' align="right">'.$row['cost'].'</td>
		</tr>';
            }
            $body.= '</table>';
        }else {
            $body.= '<br>мы работаем бесплатно :)';
        }

    /*require("./mods/tree.inc.php");
	$tree = Tree();
	echo '<pre>';
	print_r($tree);
	echo '</pre>';*/
}

/*
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
*/
function PrTelList($sel=0) {
    $html = '';
    //лист провайдеров телефонии
    $q = "select * from pt where id>0";
    $r = mysql_query($q);
    $n = mysql_num_rows($r);

    $html.= '<select name="pt" onchange="this.form.submit()">';
    $html.= '<option value="0">test';
    for($i=0;$i<$n;$i++) {
        $row = mysql_fetch_array($r);
        if($sel == $row['id']) $s = ' SELECTED';
        else $s='';
        $html.= '<option value="'.$row['id'].'"'.$s.'>'.$row['name'];
    }
    $html.= '</select>';
    return $html;
}

function Strip7($str) {
    $ret = '';
    if(substr($str, 0, 1) == '7') $ret = substr($str, 1);
    return $ret;
}

function Add10($str) {
    $ret = $str;
    return '10'.$ret;
}

?>
