<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 * http://test.amobile.ru/info/code-number.htm
 * 
 * во всем мире принята единая структура международного номера абонента: Кс-ABC-abx1-x5
 * Кс – код страны с максимальной длиной до 3-х цифр
 * коды стран – это коды зон всемирной нумерации, определенные рекомендацией Е.163
 *
 * Структура и длина национального номера абонента определяются администрацией связи страны. В соответствии
 * с действующими нормативами, национальный номер абонентов РФ имеет десятизначный формат и структуру:
 * АВС-авх1х2х3х4х5 или DEF-авх1х2х3х4х5, где авх1х2х3х4х5 – зоновый номер абонента.
 *
 * Первые три цифры – код зоны АВС (DEF) – обеспечивают зоновый принцип плана нумерации страны. Таким образом,
 * трехзначный код ставится в соответствие определенной зоне нумерации. Хитрость заключается в том, что предусмотрены так
 * называемые географические зоны нумерации (код АВС) и негеографические зоны нумерации (код DEF). 
 *
 * Коды DEF получили название федеральных.
 * По принятой в 1999 году «Системе и плану нумерации в 7 географической зоне» (РФ и Казахстан) коды 90F были выделены
 * операторам сотовой связи следующим образом: 901 – NMT-450 / DAMPS; 902 – GSM 900; 903 – GSM 1800.
 *2
 * приказом Министерства связи были выделены дополнительные коды: МТС получил код 91F, а «МегаФон» – 92F.
 *
 * Регион                                      | Индекс F
 * --------------------------------------------+---------
 * Центральный и Центрально-Черноземный район  | 6,0
 * Северный и Северо-Западный                  | 1
 * Северо-Кавказский                           | 8
 * Поволжский                                  | 7
 * Сибирский                                   | 3
 * Уральский                                   | 2
 * Дальневосточный                             | 4
 *
 * В конце 2002 года Минсвязи выпустило приказ «О введении кодов DEF “904” и “905” для сетей сотовой связи стандарта GSM». 
 * По этому документу обладателем нового федерального кода «905» стала компания «ВымпелКом». В свое распоряжение она
 * получила 10 млн. номеров. Кроме того, «ВымпелКом» сможет использовать свой федеральный код «903»
 *
 * Согласно тому же приказу код «904» получили группы операторов, работающих под торговыми марками «Индиго» и Tele2.
 * Для них выделены номерные ресурсы емкостью 1 млн. Для «Индиго» в коде «DEF-a» – «904-2», для Tele2 – «904-3».
 * Формальными владельцами этих кодов являются компании «Новгородские телекоммуникации» и «Ростовская сотовая связь»,
 * которые были определены акционерами в качестве координирующих операторов по созданию сетей сотовой связи
 * стандарта GSM 1800.
 *
 *
 *
 *
 *
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

isset($_GET['debug']) ? $d = $_GET['debug'] : $d=0;

$db = mysql_connect("localhost","root","");
mysql_select_db("asterisk");

mysql_query("SET character_set_client='utf8'");
mysql_query("SET character_set_connection='utf8'");
mysql_query("SET character_set_results='utf8'");


$cdate = date("Y-m-01 00:00:00",mktime()-60*60*24); //за сутки до первого дня месяца
$q = "
        select
            *
        from
            stat
        where
            kod IN (-1,-3) and
            cd>='$cdate'
";
/* and direction='out'
";*/

//echo $q."<br>";
$r = mysql_query($q);
$m = mysql_num_rows($r);
echo date("Y-m-d H:i:s: ").$m."\n";
//$m = min(30,$m);
require("/var/www/html/ext/mods/tree.inc.php");

$tree = Tree();

if($d)
{
    mysql_query("SET character_set_client='utf8'");
    mysql_query("SET character_set_connection='utf8'");
    mysql_query("SET character_set_results='utf8'");
    
    echo '<pre>';
    print_r($tree[7]);
    echo '</pre>';
}

for($i=0;$i<$m;$i++)
{
    //$kod = -2;		//неизвестно
    //$kod = -1;		//debug
    $kod = -3;		//номера вне логики просчета
    $row = mysql_fetch_array($r);
    //print_r($row);
    $id = $row['id'];
    $tel = $row['dst'];
    $io = $row['direction'];

    if($io=='redir'){
        $dstch = $row['dstch'];
        $aa = preg_split("/\//",$dstch);
        $aa = preg_split("/@/",$aa[1]);
        $tel = $aa[0];

        /*
         * Косяк весь в том, что у нас создается две записи
         *  877648 | 420103 | redir     | 2010-11-05 04:54:20 | 2010-11-05 04:55:05 | 9250054066 | 1001400     | 0    | SIP/ip7218720_8              | Local/89165067002@c9677581_5 |       12 |       5 | ANSWERED  | 1288922060.81 |      0 |    0 |
| 877647 | 420102 | out       | 2010-11-05 04:54:20 | 2010-11-05 04:55:05 | 9250054066 | 89165067002 | 9165 | Local/89165067002@c9677581_5 | SIP/ip1903_1                 |        7 |       0 | ANSWERED  | 1288922060.83 |  3.135 |    0 |
Одна нулевая, но номер есть, так что номер нам нужно "вытягивать"
         */
    }
    
    if($d) echo $tel.'('.$row['ch'].')=('.$row['direction'].')=('.$row['cause'].')'.': ';
    
    //Москва
    //11:   8 999 999 99 99
    //12:  98 999 999 99 99
    //13: 810 999 999 99 99
    //10:     916 999 99 99
    
    if(strlen($tel)>=10)
    {
	if($d) echo ' 1';
	//вычисляем код
	if(substr($tel,0,1)=='8' || substr($tel,0,2)=='98' || substr($tel,0,3)=='810')	//810 - международная связь
	{
	    if($d) echo '.1';
	    //1, межгород. 2, Наша межгород
	    $ext = '';
	    /*if(substr($tel,0,3)=='810'){
		if($d) echo '.1: ';
		$ext = substr($tel,3);
	    }
	    else*/
	    if(substr($tel,0,1)=='8'){
		if($d) echo '.2: ';
		$ext = substr($tel,1);
	    }
	    else
	    if(substr($tel,0,2)=='98'){
		if($d) echo '.3: ';
		$ext = substr($tel,2);
	    }
	    else{
		if($d) echo '.0('.substr($tel,0,1).'): ';
		$ext = $tel;
	    }
	    
	    if($d) echo ' '.$ext;
	    
	    $n = preg_split('//', $ext, -1, PREG_SPLIT_NO_EMPTY);
	    $klen = 0;
	    if(isset($tree[$n[0]][$n[1]][$n[2]][$n[3]][$n[4]][$n[5]][$n[6]]['name']))		//7
		$klen = 7;
	    else if(isset($tree[$n[0]][$n[1]][$n[2]][$n[3]][$n[4]][$n[5]]['name']))	//6
		$klen = 6;
	    else if(isset($tree[$n[0]][$n[1]][$n[2]][$n[3]][$n[4]]['name']))		//5
		$klen = 5;
	    else if(isset($tree[$n[0]][$n[1]][$n[2]][$n[3]]['name']))		//4
		$klen = 4;
	    else if(isset($tree[$n[0]][$n[1]][$n[2]]['name']))		//3
		$klen = 3;
	    if($klen)
	    $kod = substr($ext,0,$klen);
	}
    }
    else if(strlen($tel)<=7 || (strlen($tel)==8 && substr($tel,0,1)=='9'))
	$kod = '0';	//местные
    else
	$kod = '-2';	//неизвестно
	//$kod = '-1';	//debug
    
    $qu = "update stat set kod='$kod' where id=$id";
    $ru = mysql_query($qu);
    //echo "<br>".$qu;
    if($d)
	echo $qu."<br>";
    else
	echo $qu."\n";
    //echo '<br><br>';
}



mysql_close($db);

?>
