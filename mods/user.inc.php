<?php

$id = 0;
$secret = '';
$oid = '';
$pid = '';
$intno = '';
$dtmf = '';
$call_limit = 10;

$dtmf2 = 0; //auto  45
$nat = 0;   //no    23
$crinv = 1; //yes   1
$t38 = 1;   //yes   0

switch($act) {
    case 'edit':
        $id = $_GET['id'];
        $q = "select oid,intno,secret,pid,dtmfmode,flags,call_limit from users where id=$id";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        if($n)
            list($oid,$intno,$secret,$pid,$dtmf,$flags,$call_limit) = mysql_fetch_array($r);
        //echo $flags;
        $t38    = unpack_byte($flags, 0, 1); //echo $t38;
        $crinv  = unpack_byte($flags, 1, 1); //echo $crinv;
        $nat    = unpack_byte($flags, 2, 2); //echo $nat;
        $dtmf2  = unpack_byte($flags, 4, 2); //echo $dtmf2;
    case 'add':
        if($do) {
            $id = addslashes($_POST['id']);
            $action = addslashes($_POST['action']);
            $oid = $_GET['oid'];
            $intno = addslashes($_POST['intno']);
            $secret = addslashes($_POST['secret']);
            $pid = addslashes($_POST['pid']);
            $dtmf = addslashes($_POST['dtmf']);
            $call_limit = addslashes($_POST['call_limit']);

            list($t38,$crinv,$nat,$dtmf2) = array(post_var('t38',1),post_var('crinv',1),
                                                post_var('nat',0), post_var('dtmf2',0));
            $flags = pack_byte($t38,0)+pack_byte($crinv,1)+pack_byte($nat,2)+pack_byte($dtmf2,4);

            if($action == 'add') {
                $body = 'Добавлена точка: '.$intno;
                $q = "insert into users values(0,$oid,'$intno','$secret',$pid,'$dtmf','$flags','$call_limit')";
                echo $q;
                $r = _mysql_query($q);
            }
            if($action == 'edit') {
                $body = 'Изменена точка: '.$intno;
                //$q = "insert into users values(0,$oid,'$intno','$secret',$pid)";
                $q = "update users set oid=$oid, intno='$intno', secret='$secret', pid='$pid', dtmfmode='$dtmf', flags='$flags', call_limit='$call_limit' where id=$id";
                //echo $q;
                $r = _mysql_query($q);
            }
            $body.= '<br><a href="?gr=user&oid='.$oid.'">Назад</a>';
        }else {
            $oid = $_GET['oid'];
            if($act=='add') {
                $iter = 3-strlen($oid);
                $intno = "1";
                for($i=0;$i<$iter;$i++)
                    $intno.="0";
                $intno.=$oid."0";
                for($i=strlen($intno)-1;$i>=0;$i--) {
                    $secret.=$intno[$i];
                }
            }
            $jq.= '
                //генератор пароля
                $("#pwd").click(
                    function(){
                        $.get("./?gr=passwd&ajax=1&min=4&max=7",
                            function(data) {
                                $("#secret").val(data);
                            }
                        );
                    }
                );
                ';
            $body = '';
            $body.= '
		<form action="?gr='.$gr.'&act=add&do=1&oid='.$oid.'" method="post">
		<table>
		    <tr><td>oid</td><td>'.$oid.'</td></tr>
		    <tr><td>intno</td><td><input type="Text" name="intno" value="'.$intno.'"></td></tr>
		    <tr><td>secret</td><td><input type="Text" name="secret" value="'.$secret.'" id="secret"><input type="Button" id="pwd" value="..."></td></tr>
		    <tr><td>peer</td><td>'.PeerList($oid,$pid).'</td></tr>
		    <tr><td>dtmfmode</td><td>'.DTMFList($dtmf).'</td></tr>
                    '.tag_tr().tag_td('t38')            .tag_td(tag_f_rb('t38', 0, $t38, 'no')      .tag_f_rb('t38', 1, $t38, 'yes')).tag_tr(1).'
                    '.tag_tr().tag_td('canreinvite')    .tag_td(tag_f_rb('crinv', 0, $crinv, 'no')  .tag_f_rb('crinv', 1, $crinv, 'yes')).tag_tr(1).'
                    '.tag_tr().tag_td('nat')            .tag_td(tag_f_rb('nat', 0, $nat, 'no')      .tag_f_rb('nat', 1, $nat, 'yes')).tag_tr(1).'
                    '.tag_tr().tag_td('dtmfmode')       .tag_td(tag_f_rb('dtmf2', 0, $dtmf2, 'auto').tag_f_rb('dtmf2', 1, $dtmf2, 'inband') .tag_f_rb('dtmf2', 2, $dtmf2, 'rfc2833')    .tag_f_rb('dtmf2', 3, $dtmf2, 'info')).tag_tr(1).'
                    <tr><td>call_limit</td><td><input type="Text" name="call_limit" value="'.$call_limit.'"></td></tr>
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
        if($n) {
            $row = mysql_fetch_array($r);
            $body.='<h2>Организация: '.$row['name'].'</h2>';
            $body.= tag_a("?gr=stat&oid=$row[id]", 'Статистика').tag_br();
        }

        $q = "select u.intno as intno, u.secret as secret, p.name as peer, p.tel as tel, p.username as pu, p.secret as ps, u.id as uid, p.id as pid from users as u left join peers as p on p.id=u.pid left join org as o on o.id=u.oid where u.oid=$oid";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= 'Точки монтирования со стороны пользователей (сколько телефонных аппаратов стоит у данной организации)';
        $body.= '<br>';
        $body.= '<a href="?gr='.$gr.'&act=add&oid='.$oid.'">Добавить точку монтирования</a>';

        //$body.='<table cellpadding="1" cellspacing="1" border="0" bgcolor="#cccccc">';
        $body.= tag_t_o_('maintbl');
        $body.='<tr>
		    <th  bgcolor="#e0e0e0" width="100">login</th>
		    <th  bgcolor="#e0e0e0" width="100">passwd</th>
		    <th  bgcolor="#e0e0e0" width="100">peer</th>
		    <th  bgcolor="#e0e0e0" width="100">puser</th>
		    <th  bgcolor="#e0e0e0" width="100">passwd</th>
		    <th  bgcolor="#e0e0e0" width="100">tel</th>
                    <th  bgcolor="#e0e0e0" width="100">ip</th>
		</tr>';
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            $body.= '<tr>';
            $body.= '<td bgcolor="#eeeeee">'.$row['intno'].'</td>';
            $body.= '<td bgcolor="#eeeeee"><font size="-2">'.$row['secret'].'</font></td>';
            $body.= '<td bgcolor="#eeeeee">'.$row['peer'].'</td>';
            $body.= '<td bgcolor="#eeeeee">'.$row['pu'].'</td>';
            $body.= '<td bgcolor="#eeeeee"><font size="-2">'.$row['ps'].'</font></td>';
            $body.= '<td bgcolor="#eeeeee">'.$row['tel'].'</td>';
            $ip =  user_map($row['intno']);
            $body.= tag_td($ip);
            $body.= '</tr>';
        }
        $body.='</table>';

        $q = "select * from users where oid=$oid";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);


        if($n) {
            $body.= '<table>';
            for($i=0;$i<$n;$i++) {
                $row = mysql_fetch_array($r);
                $id = $row['id'];
                $body .= '<tr><td><a href="?gr=user&act=edit&id='.$id.'&oid='.$oid.'">'.$row['intno'].'</a></td></tr>';
            }
            $body.= '</table>';
        }else {
            $body.= '<br>У данной организации нет установленных точек';
        }
        //список ее пиров:
        $body .= '<hr>';
        $body .= 'PEERы (внешние номера, закрепленные за данной организацией)';
        $q = "select * from peers where oid=$oid";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        if($n) {
            $body.= '<table>';
            for($i=0;$i<$n;$i++) {
                $row = mysql_fetch_array($r);
                $id = $row['id'];
                $oid = $row['oid'];
                $name = $row['name'];
                $body.= tag_tr();
                $body.= tag_td(tag_a("?gr=peer&act=edit&id=$id",$name));
                $body.= tag_td(tag_a("?gr=popt&oid=$oid&pid=$id",'Маршруты'));
                $body.= tag_td(tag_a("?gr=iopt&oid=$oid&pid=$id",'Входящие'));
                $body.= tag_tr(1);
            }
            $body.= '</table>';
        }else {
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

function PeerList($oid,$pid) {
    $html = '';

    $q = "select * from peers where oid=$oid";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);

    $bsel = 0;
    $html = '<select name="pid">';

    if(!$pid)
        $html.= '<option SELECTED value="0">Не выбран';
    for($i=0;$i<$n;$i++) {
        $row = mysql_fetch_array($r);

        if($pid == $row['id']) {
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


function DTMFList($dtmf) {
    $html = '';
    $html.= '';
    $html.= '<select name="dtmf">';
    ($dtmf == 'auto')	? $sel = 'SELECTED' : $sel='' ;
    $html.= '<option '.$sel.' value="auto">auto';
    $sel = '';
    ($dtmf == 'inband')	? $sel = 'SELECTED' : $sel='' ;
    $html.= '<option '.$sel.' value="inband">inband';
    $sel = '';

    $html.= '</select>';

    return $html;
}

/*function my_exec($cmd, $input='') {
    $proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes);
    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    sleep(1);
    $stdout=stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr=stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $rtn=proc_close($proc);
    return array('stdout'=>$stdout,
            'stderr'=>$stderr,
            'return'=>$rtn
    );
}*/

function user_map($intno) {
    $file = "/var/www/html/ext/cron/map";
    $map = file_get_contents($file);
    $aa = preg_split("/\n/",$map);
    $line = '';
    foreach($aa as $line){
        if(strpos($line,$intno) !== false) break;
    }
    //echo $line;
    $bb = preg_split("/ /",$line,-1, PREG_SPLIT_NO_EMPTY);
    //print_r($bb);
    if(!isset($bb[1])) return '';
    return $bb[1].' ('.date('Y-m-d H:i:s',filectime($file)).')';
}