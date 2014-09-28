<?php
$body = '';

isset($_GET['pid']) ? $pid = $_GET['pid'] : $pid=0;
defined('ADMIN_STAT') ? $oid = get_var('oid',0) : $_SESSION['uid'];

$jq.= '
	$(function() {
		$("#ds").datepicker({ dateFormat: \'yy-mm-dd\', changeMonth: true, showWeek: true });
                //$("#ds").datepicker();
	});
        $(function() {
                $("#de").datepicker({ dateFormat: \'yy-mm-dd\', changeMonth: true, showWeek: true });
		//$("#de").datepicker();
	});
';

$pay_only   = get_var('pay_only', 0);
$no_mcn     = get_var('no_mcn', 0);
$direction  = get_var('direction',array('out'=>'out','otr'=>'otr'));
$status     = get_var('status',array());
$f_off      = get_var('f_off',0);
$kod        = get_var('kod','');
$friend     = get_var('fr',1);
$sys        = get_var('sys',1);
$s_from     = get_var('s_from');
$s_to       = get_var('s_to');
$s_kod      = get_var('s_kod');

if($pid || $oid) {
    isset($_GET['ds']) ? $ds = $_GET['ds'] : $ds = date("Y-m-01");
    isset($_GET['de']) ? $de = $_GET['de'] : $de = date("Y-m-t");

    $body.= '
	<form>
         <div>
	    <input type="Text" name="ds" id="ds" value="'.$ds.'">
	    <input type="Text" name="de" id="de" value="'.$de.'">
	    <input type="Submit" value="Показать">
            '.tag_f_ch('direction', 'in' , isset($direction['in']) , 'Показать входящие' , 'in').'
            '.tag_f_ch('direction', 'out', isset($direction['out']), 'Показать исходящие', 'out').'
            '.text_show(defined('ADMIN_STAT'),tag_f_ch('status', 'NO ANSWER',isset($status['NO ANSWER']), 'NO ANSWER', 'NO ANSWER')).'
            '.text_show(defined('ADMIN_STAT'),tag_f_ch('f_off', 1, $f_off, 'Отключить фильтр')).'
            '.text_show(defined('ADMIN_STAT'),tag_f_ch('no_mcn', 1, $no_mcn, 'Без mcn')).'
            '.(tag_f_ch('pay_only', 1, $pay_only, 'Только платные')).'
            '.text_show(defined('ADMIN_STAT'),'||').'
            '.text_show(defined('ADMIN_STAT'),tag_f_ch('fr', 0, !$friend, 'Скрыть имена звонков')).'
            '.text_show(defined('ADMIN_STAT'),tag_f_ch('sys', 0, !$sys, 'Скрыть служебную информацию')).'
            '.text_show(!defined('ADMIN_STAT'),'<a href="/?login=10">Выход</a>').'
	    <input type="Hidden" name="pid" value="'.$pid.'">
	    <input type="Hidden" name="oid" value="'.$oid.'">
	    <input type="Hidden" name="gr" value="'.$gr.'">
         </div>
	<!--</form> форма закрывается после таблицы... %) -->';
    /*
     * //получить наши номера, по которым идет обсчет
    //$q = "select * from users where pid=$pid";
    SIP/ip9677581a-08211640...
    SIP/206-b7d355c0	SIP/ip9677581a-08211640 - исходящий звонок
    SIP/100001-08214230	SIP/205-b77045e8 - входящий звонок
    */

    // ПО pid
    $body.= 'Статистика по ';
    if($pid) {
        $in = "";
        $in_a = split("\|",$pid);
        //print_r($in_a);
        //$q = "select * from peers where id=$pid";
        foreach($in_a as $id) $in.="$id,";
        $in = substr($in, 0, strlen($in)-1);
        $q = "select * from peers where id in ($in) order by name";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        $body.= 'PEERs: ';
        for($i=0;$i<$n;$i++){
            $row = mysql_fetch_array($r);
            $body .= $row['name'].' ';
            $o_channel[$i] = 'ip'.$row['tel'];
        }
        $body.= tag_br();
        //$o_channel = 'ip'.$row['tel'];	//для исходящей связи
        //$i_channel = $row['username'];	//для входящей связи
        //lastapp Dial
        //duration	billsec
        //calldate
        //src	dst	dcontext
        //channel	dstchannel
        $o_c = '';
        foreach($o_channel as $ch) $o_c.= "'SIP/$ch',";
        $o_c = str_strip_last($o_c);
        //echo $o_c;

        $q = "
                select
                    *, s.kod as kod, s.cost as ccost, p.prid as zone, s.uniqueid as uid
                from
                    cdr as c left join stat as s on c.id=s.cdrid left join price as p on p.kod=s.kod
                where
                    p.ptid = 0 and
                    (s.dstch in($o_c)) and 
                    lastapp='Dial' and 
                    s.cd >= '$ds 00:00:00' and s.cd <= '$de 23:59:59'";
                    $qw = " and (s.cause='ANSWERED'";
                    foreach($status as $st) $qw.= " or s.cause='$st'";
                    $qw.= ')';
                    if(!$f_off) $q.= $qw;
                    $qw = " and (s.direction=''";
                    foreach($direction as $d) $qw.= " or s.direction='$d'";
                    $qw.= ')';
                    if($s_from != '') $qw.= " and s.src like '%$s_from%' ";
                    if($s_to != '') $qw.= " and s.dst like '%$s_to%' ";
                    if($s_kod != '') $qw.= " and s.kod like '%$s_kod%' ";
                    if(!$f_off) $q.= $qw;
        $q.= " order by s.cd desc;
                ";
        //echo $q;

        /*$q = "
                select
                    *, s.kod as kod, s.cost as ccost, p.prid as zone
                from
                    cdr as c left join stat as s on c.id=s.cdrid left join price as p on p.kod=s.kod
                where
                    (channel like 'SIP/$i_channel-%' or dstchannel like 'SIP/$o_channel-%') and
                    lastapp='Dial' and
                    calldate >= '$ds 00:00:00' and calldate <= '$de 23:59:59'
                ";*/
    }
    else {
        //получаем номера пиров для вывода по ним статистики
        $q = "select * from peers where oid=$oid";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        $pall = '';
        $un_in = '';
        for($i=0;$i<$n;$i++){
            $row = mysql_fetch_array($r);
            $un_in.= $row['username'].',';
            $body.= text_show(defined('ADMIN_STAT'),tag_a("?&gr=$gr&ds=$ds&de=$de&pid=".$row['id'],$row['name']).' ');
            $pall.= $row['id'].'|';
        }
        //echo substr($un_in,0,-1);
        $un_in = substr($un_in,0,-1);
        $pall = substr($pall, 0, -1);
        $body.= text_show(defined('ADMIN_STAT'),tag_a("?&gr=$gr&ds=$ds&de=$de&pid=".$pall,'Все').' ');
        
        $row = mysql_fetch_array($r);
        
        //получаем списко внутренних номеров для вывода по ним статистики.
        $q = "
                SELECT
                    o.name as org, u.intno as intno,o.login
                FROM
                    users AS u
                LEFT JOIN org AS o ON u.oid = o.id
                WHERE u.oid =$oid
                ";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        $i_channel = array();       //внутренние номера
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            $org = $row['org'];
            $org_login = $row['login'];
            if(!is_null($row['intno']))
                array_push($i_channel,$row['intno']);
        }
        $body .= 'ORG: '.$org_login;

        $q = "
            select
                *, s.kod as kod, s.cost as ccost, p.prid as zone, s.uniqueid as uid
            from
                cdr as c left join stat as s on c.id=s.cdrid left join price as p on p.kod=s.kod
            where (";
        $i_c = "(s.ch='SIP/".$i_channel[0]."'";
        for($i=1;$i<count($i_channel);$i++)
            $i_c.= " or s.ch='SIP/".$i_channel[$i]."'";
        $q.=$i_c.")";
        $q.= " or ";
        $o_c = " (s.dstch='SIP/".$i_channel[0]."'";
        for($i=1;$i<count($i_channel);$i++)
            $o_c.= " or s.dstch='SIP/".$i_channel[$i]."'";
        $q.=$o_c.") or (s.dstch like 'Local%' and s.dst in($un_in)))";
        $q.= " and
                (lastapp='Dial' or lastapp='Transferred Call') and
                p.ptid = 0 and
                s.cd >= '$ds 00:00:00' and s.cd <= '$de 23:59:59'";
        ($kod!='') ? $q.=" and s.kod=$kod and s.cause='ANSWERED' " : $q.='';
        $qw = " and (s.cause='ANSWERED'";
        foreach($status as $st) $qw.= " or s.cause='$st'";
        $qw.= ')';
        if(!$f_off) $q.= $qw;
        $qw = " and (s.direction='' or s.direction='otr' or s.direction='itr' or s.direction='redir'";
        foreach($direction as $d) $qw.= " or s.direction='$d'";
        $qw.= ") ";
        if($s_from != '') $qw.= " and s.src like '%$s_from%' ";
        if($s_to != '') $qw.= " and s.dst like '%$s_to%' ";
        if($s_kod != '') $qw.= " and s.kod like '%$s_kod%' ";
        if($no_mcn) $qw.= " and s.dstch not like '%1903%' ";
        if($pay_only) $qw.= " and s.cost>0 ";
        if(!$f_off) $q.= $qw;
        $q.= "order by c.calldate desc";
        // or lastapp='Busy'
        $body.= '<br>';
    }	//!!!

    //ВЫВОДИМ СТАТИСТИКУ, ЗАПРОС СФОРМИРОВАН ВЫШЕ
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    //echo $n;

    $st_t = array();

    $body .=$n.' записей';
    $body .='<table cellpadding="2" cellspacing="1" bgcolor="#B0B0B0" style="font-size: 11pt;">';
    $bg = ' bgcolor="#C0C0C0"';
    $bgc = '#C0C0C0';
    $body .='<tr>
		'.tag_th_('i/o',array('c'=>'sth','bgcolor'=>$bgc)).'
                '.tag_th_('Дата звонка',array('c'=>'sth','bgcolor'=>$bgc, 'w'=>150)).'
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
		'.text_show(defined('ADMIN_STAT'),'<th'.$bg.' class="sth">минута<br>руб.</th>').'
		<th'.$bg.' class="sth">Стоимость<br>звонка</th>
                '.text_show(defined('ADMIN_STAT'),'<th'.$bg.' class="sth">файл</th>').'
		'.text_show(defined('ADMIN_STAT') && $sys,'<th>ch</th><th>dstch</th><th>channel</th><th>dstchannel</th><th>dcontext</th><th>uid</th>').'
		</tr>';
    //$body.= text_show(defined('ADMIN_STAT'),'');
    $body.= text_show(defined('ADMIN_STAT'),tag_tr());
    $body.= text_show(defined('ADMIN_STAT'),tag_td(sp()));
    $body.= text_show(defined('ADMIN_STAT'),tag_td(sp()));
    $body.= text_show(defined('ADMIN_STAT'),tag_td(tag_f_in('s_from',$s_from,10),0,'right'));
    $body.= text_show(defined('ADMIN_STAT'),tag_td(tag_f_in('s_to',$s_to,10),0,'right'));
    $body.= text_show(defined('ADMIN_STAT'),tag_td(tag_f_in('s_kod',$s_kod,5),0,'right'));
    
    $body.= text_show(defined('ADMIN_STAT'),tag_td(sp()));
    $body.= text_show(defined('ADMIN_STAT'),tag_tr(1));
    $bsec = 0;
    $bg = '';
    $stat = array();
    $sum = 0;

    $st_t = array();	//общая статистика

    for($i=0;$i<$n;$i++) {
        $row = mysql_fetch_array($r);
        //заполняем общую
        //if($row['direction']=='out')
        isset($st_t[$row['zone']]) ? $st_t[$row['zone']]++ : $st_t[$row['zone']]=1;
        //общая таблица

        ($i % 2) ? $bg = ' bgcolor="#EEEEEE"' : $bg = ' bgcolor="#E0E0E0"';
        if($row['direction']=='in')
            ($i % 2) ? $bg=' bgcolor="#82CA9C"' : $bg=' bgcolor="#3BB878"';
        if($row['disposition']=='NO ANSWER' || $row['disposition']=='BUSY' || $row['disposition']=='FAILED')
            ($i % 2) ? $bg=' bgcolor="#FF9999"' : $bg=' bgcolor="#FF6666"';
        if($row['direction']=='otr' || $row['direction']=='itr')
            ($i % 2) ? $bg=' bgcolor="#007fff"' : $bg=' bgcolor="#00a9ff"';

        $body.= '<tr>';
        $body.= '<td'.$bg.' class="std" align="center">'.$row['direction'].'</td>';
        $body.= '<td'.$bg.' class="std" align="center">'.$row['calldate'].'</td>';
        $body.= '<td'.$bg.' class="std" align="right">'.$row['src'].'</td>';
        $dst = '';
        $body.= '<td'.$bg.' class="std" align="right">'.( isset($fr[GetNo($row['dst'])]) ? text_show(defined('ADMIN_STAT') && $friend,'('.$fr[GetNo($row['dst'])].') ') : '' ).$row['dst'].'</td>';
        $body.= '<td'.$bg.' class="std" align="right">'.(($row['direction']=='in') ? 'Входящий(0)' : ($row['name'].'('.$row['kod'].')')).'</td>';
        $body.= '<td'.$bg.' class="std" align="right">'.$row['duration'].'</td>';
        $body.= '<td'.$bg.' class="std" align="right">'.$row['billsec'].'</td>';
        $body.= '<td'.$bg.' class="std" align="right">'.toTime($row['billsec']).'</td>';
        $bsec += $row['billsec'];
        $body.= '<td'.$bg.' class="std" align="right">'.$row['disposition'].'</td>';
        $body.= text_show(defined('ADMIN_STAT'),'<td'.$bg.' class="std" align="right">'.$row['minute'].'</td>');
        $body.= '<td'.$bg.' class="std" align="right">'.money($row['ccost'],2).'</td>';
        $sum += $row['ccost'];

        //$aa = preg_split("/\./",$row['uid']);
        //$tt = $aa[0];
        //$dir = date("Y-m-d",$tt);
        //$aa = preg_split("/\//",$row['dstch']);
        //$ch = $aa[1];
        //$dst = GetNo($row['dst']);
        //$g = '-g';    //с 14 числа 04 месяца 2010 года
        //if(substr($ch,0,6)=='ip1903') $g='-g';
        //$fname = "$row[src]-$ch-$dst-$row[uid]$g.mp3";
        //$link = text_show((($row['direction']=='out') && ($tt>=strtotime('2010-04-14 01:11:00'))),tag_a("/calls/$dir/$fname",'Скачать','_blank'));
        //$link = text_show($tt>=strtotime('2010-04-14 00:00:00'),tag_a("/calls/$dir/$fname",'Скачать','_blank'));
        $link = call_link($row['uid'],$row['dstch'],$row['src'],$row['dst'],$row['direction']);
        $body.= text_show(defined('ADMIN_STAT'),'<td'.$bg.' class="std" align="right">'.($link).'</td>');

        $body.= text_show(defined('ADMIN_STAT') && $sys,tag_td(text_size(8,$row['ch']),$i));
        $body.= text_show(defined('ADMIN_STAT') && $sys,tag_td(text_size(8,$row['dstch']),$i));
        $body.= text_show(defined('ADMIN_STAT') && $sys,tag_td(text_size(8,$row['channel']),$i));
        $body.= text_show(defined('ADMIN_STAT') && $sys,tag_td(text_size(8,$row['dstchannel']),$i));
        $body.= text_show(defined('ADMIN_STAT') && $sys,tag_td(text_size(8,$row['dcontext']),$i));
        $body.= text_show(defined('ADMIN_STAT') && $sys,tag_td(text_size(8,$row['uid']),$i));
        $body.= '</tr>';
        //$stat[$name] =
    }
    $bg = ' bgcolor="#C0C0C0"';
    $body .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td align="right"'.$bg.'><b>'.$bsec.'</b></td><td align="right"'.$bg.'><b>'.toTime($bsec).'</b></td><td></td>'.text_show(defined('ADMIN_STAT'),'<td></td>').'<td align="right"'.$bg.'><b>'.$sum.'</b></td></tr>';
    $body .= '</table>';


    //print_r($st_t);
    $body .='<table cellpadding="2" cellspacing="1" bgcolor="#B0B0B0" style="font-size: 11pt;">';
    $bg = ' bgcolor="#C0C0C0"';
    $body .='
	    <tr>
		<th'.$bg.' class="sth">Направление</th>
		<th'.$bg.' class="sth">Количество</th>
	    </tr>';
    ksort($st_t);
    $i=0;
    foreach($st_t as $key=>$val) {
        ($i % 2) ? $bg = ' bgcolor="#EEEEEE"' : $bg = ' bgcolor="#E0E0E0"';
        $body.= '<tr>';
        $body.= '<td'.$bg.' class="std" align="left">'.ZoneName($key).'</td>';
        $body.= '<td'.$bg.' class="std" align="right">'.$val.'</td>';
        $body.= '</tr>';
        $i++;
    }
    $body.= '<tr>';
    $body.= '<td'.$bg.' class="std" align="left"> </td>';
    $body.= '<td'.$bg.' class="std" align="right">'.array_sum($st_t).'</td>';
    $body.= '</tr>';

    $body .= '</table></form>';

}
else {
    $like = get_var('like');

    $body.= '
	<form>
	    Организация: <input type="text" name="like" value="'.$like.'" id="id5">
	    <input type="Submit" value="Поиск">
	    <input type="hidden" name="gr" value="'.$gr.'">
	</form>
    ';

    $body.= 'ORGs:';
    $q = "select * from org";
    if($like!='') $q.= " where name like '%$like%'";
    $q.= " order by name";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);

    $body.= tag_t_o_('maintbl');
    $body.= tag_tr();
    $body.= tag_th('-3');
    $body.= tag_th('Org');
    $body.= tag_th('Peers');
    $body.= tag_tr(1);
    for($i=0;$i<$n;$i++) {
        $row = mysql_fetch_array($r);
        $body.= tag_tr();
        $body.= tag_td('<a href="?gr='.$gr.'&oid='.$row['id'].'&kod=-3">-3</a>',$i);
        $body.= tag_td('<a href="?gr='.$gr.'&oid='.$row['id'].'">'.$row['name'].'</a>',$i);
        $body.= tag_tr(1);
    }
    $body.= tag_t_c();
}

function GetNo($no) {
    if(strlen($no)>7)
        if($no[0]==9)
            return substr($no,1);
    return $no;
}

/**
 *
 * @param <type> $uid
 * @param <type> $dstch
 * @param <type> $dst
 * @param <type> $d direction
 * @return <type> 
 */
function call_link($uid,$dstch,$src,$dst,$d) {
    $srt = strtotime('2010-04-14 01:11:00');    //start record time
    $s12 = strtotime('2010-04-21 02:10:00');    //ввели запись по 12 часов
    $set = (mktime()-12*60*60)>$s12 ? mktime()-12*60*60 : $s12;    //start encoding time
    

    $aa = preg_split("/\./",$uid);
    $sct = $aa[0];  //start call time
    $dir = date("Y-m-d",$sct);  //папка для mp3
    $aa = preg_split("/\//",$dstch);
    $ch = $aa[1];
    $dst = GetNo($dst);
    $g = '-g';    //с 14 числа 04 месяца 2010 года
    //if(substr($ch,0,6)=='ip1903') $g='-g';
    $ext = $sct < $set ? 'mp3' : 'wav';
    $fname = "$src-$ch-$dst-$uid$g.$ext";
    $path = $sct < $set ? "/calls/$dir/$fname" : "/calls/$fname";
    $link = text_show((($d=='out' || ($d=='redir')) && ($sct>=$srt)), tag_a($path,'Скачать','_blank'));
    return $link;
}
?>









