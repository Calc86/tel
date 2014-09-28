<?php

$body = '';

isset($_GET['pid']) ? $pid = $_GET['pid'] : $pid=0;
isset($_GET['oid']) ? $oid = $_GET['oid'] : $oid=0;


if($pid || $oid)
{
    isset($_GET['ds']) ? $ds = $_GET['ds'] : $ds = date("Y-m-01");
    isset($_GET['de']) ? $de = $_GET['de'] : $de = date("Y-m-t");
    
    $body.= '
	<form>
	    <input type="Text" name="ds" value="'.$ds.'">
	    <input type="Text" name="de" value="'.$de.'">
	    <input type="Submit" value="Показать">
	    <input type="Hidden" name="pid" value="'.$pid.'">
	    <input type="Hidden" name="oid" value="'.$oid.'">
	    <input type="Hidden" name="gr" value="'.$gr.'">
	</form>';
    ////получить наши номера, по которым идет обсчет
    ////$q = "select * from users where pid=$pid";
    //SIP/ip9677581a-08211640...
    //SIP/206-b7d355c0	SIP/ip9677581a-08211640 - исходящий звонок
    //SIP/100001-08214230	SIP/205-b77045e8 - входящий звонок
    //
    
    // ПО pid
    $body.= 'Статистика по ';
    if($pid)
    {
	
	$q = "select * from peers where id=$pid";
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	$row = mysql_fetch_array($r);
	$body .= 'PEER: '.$row['name'].'<br>';
	$o_channel = 'ip'.$row['tel'];	//для исходящей связи
	$i_channel = $row['username'];	//для входящей связи
	//lastapp Dial
	//duration	billsec
	//calldate
	//src	dst	dcontext
	//channel	dstchannel
	
        $q = "
select * from
 cdr
where
 (channel like 'SIP/$i_channel-%' or dstchannel like 'SIP/$o_channel-%') and
 lastapp='Dial' and
 calldate >= '$ds 00:00:00' and calldate <= '$de 23:59:59'
 ";
    }
    else
    {
	$q = "select *,o.name as org from peers as p, org as o where oid=$oid and p.oid=o.id";
	$r = _mysql_query($q);
	$n = mysql_num_rows($r);
	for($i=0;$i<$n;$i++)
	{
	    $row = mysql_fetch_array($r);
	    $org = $row['org'];
	    $o_channel[$i] = 'ip'.$row['tel'];	//для исходящей связи
	    $i_channel[$i] = $row['username'];	//для входящей связи
	}
	$body .= 'ORG: '.$org.'<br>';
/*        $q = "
select
 *
from
 cdr as c left join stat as s on c.id=s.cdrid
where";
    $i_c = "((channel like 'SIP/".$i_channel[0]."-%'";
    for($i=1;$i<count($i_channel);$i++)
	$i_c.= "or channel like 'SIP/".$i_channel[$i]."-%'";
    $q.=$i_c.")";
    $q.= " or ";
    $o_c = "(dstchannel like 'SIP/".$o_channel[0]."-%'";
    for($i=1;$i<count($o_channel);$i++)
	$o_c.= "or dstchannel like 'SIP/".$o_channel[$i]."-%'";
    $q.=$o_c."))";
    $q.= " and
 lastapp='Dial' and
 calldate >= '$ds 00:00:00' and calldate <= '$de 23:59:59'
 ";*/
 
    $q = "
select
 *,
 s.cost as ccost
from
 cdr as c left join stat as s on c.id=s.cdrid left join price as p on p.kod=s.kod
where";
    $i_c = "((s.ch='SIP/".$i_channel[0]."'";
    for($i=1;$i<count($i_channel);$i++)
	$i_c.= "or s.ch='SIP/".$i_channel[$i]."'";
    $q.=$i_c.")";
    $q.= " or ";
    $o_c = "(s.dstch='SIP/".$o_channel[0]."'";
    for($i=1;$i<count($o_channel);$i++)
	$o_c.= "or s.dstch='SIP/".$o_channel[$i]."'";
    $q.=$o_c."))";
    $q.= " and
 (lastapp='Dial') and
 s.cd >= '$ds 00:00:00' and s.cd <= '$de 23:59:59'
order by c.calldate desc
 ";
 // or lastapp='Busy'
echo $q;
	
	
    }	//!!!

    //echo $q;
    //$q = "select * from cdr where channel like 'SIP/$i_channel%'";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    //echo $n;
    $body .=$n.' записей';
    $body .='<table cellpadding="2" cellspacing="1" bgcolor="#B0B0B0" style="font-size: 11pt;">';
    $bg = ' bgcolor="#C0C0C0"';
    $body .='<tr>
		<th'.$bg.' class="sth">i/o</th>
		<th'.$bg.' class="sth" width="150">Дара звонка</th>
		<th'.$bg.' class="sth" width="110">От кого(номер)</th>
		<th'.$bg.' class="sth" width="110">Кому(номер)</th>
		<th'.$bg.' class="sth" width="200">Код, направление</th>
		<!--<th bgcolor="#E0E0E0" width="90">dcontext</th>-->
		<!--<th bgcolor="#E0E0E0" width="200">channel</th>-->
		<!--<th bgcolor="#E0E0E0" width="200">dstchannel</th>-->
		<!--<th bgcolor="#E0E0E0" width="50">lastapp</th>-->
		<!--<th>lastdata</th>-->
		<th'.$bg.' class="sth">Общее<br>время</th>
		<th'.$bg.' class="sth">Время<br>разговора</th>
		<th'.$bg.' class="sth" width="40">time</th>
		<th'.$bg.' class="sth" width="100">Статус</th>
		<th'.$bg.' class="sth">минута руб.</th>
		<th'.$bg.' class="sth">Стоимость звонка</th>
		</tr>';
    $bsec = 0;
    $bg = '';
    $stat = array();
    $sum = 0;
    for($i=0;$i<$n;$i++){
	$row = mysql_fetch_array($r);
	($i % 2) ? $bg = ' bgcolor="#EEEEEE"' : $bg = ' bgcolor="#E0E0E0"';
	if($row['direction']=='in')
	    ($i % 2) ? $bg=' bgcolor="#82CA9C"' : $bg=' bgcolor="#3BB878"';
	if($row['disposition']=='NO ANSWER' || $row['disposition']=='BUSY' || $row['disposition']=='FAILED')
	    ($i % 2) ? $bg=' bgcolor="#FF9999"' : $bg=' bgcolor="#FF6666"';
	
	$body.= '<tr>';
	$body.= '<td'.$bg.' class="std" align="center">'.$row['direction'].'</td>';
	$body.= '<td'.$bg.' class="std" align="center">'.$row['calldate'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.$row['src'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.$row['dst'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.(($row['direction']=='in') ? 'Входящий(0)' : ($row['name'].'('.$row['kod'].')')).'</td>';
	//$body.= '<td'.$bg.' align="right">'.$row['dcontext'].'</td>';
	//$body.= '<td'.$bg.'>'.$row['channel'].'</td>';
	//$body.= '<td'.$bg.'>'.$row['dstchannel'].'</td>';
	//$body.= '<td'.$bg.'>'.$row['lastapp'].'</td>';
	//$body.= '<td>'.$row['lastdata'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.$row['duration'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.$row['billsec'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.toTime($row['billsec']).'</td>';
	$bsec += $row['billsec'];
	$body.= '<td'.$bg.' class="std" align="right">'.$row['disposition'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.$row['minute'].'</td>';
	$body.= '<td'.$bg.' class="std" align="right">'.$row['ccost'].'</td>';
	$sum += $row['ccost'];
	
	$body.= '<td>'.$row['ch'].'</td>';
	$body.= '<td>'.$row['dstch'].'</td>';
	$body.= '<td>'.$row['channel'].'</td>';
	$body.= '<td>'.$row['dstchannel'].'</td>';
	$body.= '<td>'.$row['dcontext'].'</td>';
	$body.= '</tr>';
	//$stat[$name] = 
    }
    $bg = ' bgcolor="#C0C0C0"';
    $body .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td align="right"'.$bg.'><b>'.$bsec.'</b></td><td align="right"'.$bg.'><b>'.toTime($bsec).'</b></td><td></td><td></td><td align="right"'.$bg.'><b>'.$sum.'</b></td></tr>';
    $body .= '</table>';
}
else
{
    $body.= 'PEERs:';
    $q = "select * from peers order by name";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    
    for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	$body.= '<a href="?gr='.$gr.'&pid='.$row['id'].'">'.$row['name'].'</a> ';
    }
    
    $body.= '<br><br>';
    $body.= 'ORGs:';
    $q = "select * from org order by name";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    
    for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	$body.= '<a href="?gr='.$gr.'&oid='.$row['id'].'">'.$row['name'].'</a> ';
    }
}

function toTime($sec)
{
    $ret = '';
    
    $s = $sec % 60;
    (strlen($s) == 1) ? $s = "0".$s : $s = $s;
    $m = (int)($sec / 60);
    (strlen($m) == 1) ? $m = "0".$m : $m = $m;
    
    $ret = $m.':'.$s;
    
    
    return $ret;
}

?>









