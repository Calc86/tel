<?

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

?>