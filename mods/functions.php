<?

$r_types = array(
            "time",
            "port",
            "queue",
            "notice",
            "ivr",
            "menu",
            "vmail",
            "conf",
            "context",
            "hangup"
            );

function _mysql_query($q)
{
    global $_err, $_q, $_n;
    
    $_q.= $q."\n";
    
    $r = mysql_query($q);
    $_n++;
    $err = mysql_error();
    if($err != '')
	$_q.= '&nbsp;&nbsp;'.$err."\n";
    return $r;
}

function debug()
{
    global $_err, $_q, $_n;
    
    echo $_n.' запрос(ов)<br>';
    echo '---';
    echo '<font size="-1"><pre>';
    //echo nl2br($_q);
    echo ($_q);
    echo '</pre></font>';
}

function op_month($m,$y,$op) {
    $a = array('m'=>$m,'y'=>$y);
    $a['m'] = $a['m']+1*$op;
    if($a['m']==0){
        $a['m'] = 12;
        $a['y']-= 1;
    }
    if($a['m']==13){
        $a['m'] = 1;
        $a['y']+= 1;
    }
    return $a;
}

/**
 *
 * @param <type> $tel   Телефон
 * @param <type> $pno   номер из пула
 * @param <type> $id    id пира
 * @param <type> $nid   id номера из пула
 * @return <type>
 */
function ast_get_tel($tel,$pno,$id,$nid) {
    if($nid) $tel = $pno.'_'.$id;
    return $tel;
}

/**
 *
 * @param <type> $tel   Телефон
 * @param <type> $pno   номер из пула
 * @param <type> $id    id пира
 * @param <type> $nid   id номера из пула
 * @return <type>
 */
function ast_get_name($name,$pno,$id,$nid) {
    if($nid) $name = 'p'.$pno.'_'.$id;
    return $name;
}

function toTime($sec) {
    $ret = '';

    $s = $sec % 60;
    (strlen($s) == 1) ? $s = "0".$s : $s = $s;
    $m = (int)($sec / 60 % 60);
    (strlen($m) == 1) ? $m = "0".$m : $m = $m;
    $h = (int)($sec / 3600);
    (strlen($h) == 1) ? $h = "0".$h : $h = $h;

    $ret = $h.':'.$m.':'.$s;


    return $ret;
}

/**
 *
 * @param int  $v Значение
 * @param int  $p Положение
 */
function pack_byte($v,$p) {
    return $v << $p;
}

/**
 *
 * @param <type> $p Положение
 * @param <type> $l Длина
 * @return <type>
 */
function unpack_byte($v,$p,$l) {
    $mask = 0;
    for($i=0;$i<$l;$i++)
        $mask+=pack_byte(1, $p+$i);
    $val = $v & $mask;
    $ret = $val >> $p;
    return $ret;
}

?>