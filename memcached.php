<?php
$mem = New Memcache();
$mem->AddServer('localhost', 11211);

function memGet($key) {
    global $mem;
    if($key == "201106_nid0") print_r($mem->get('mxtel_'.$key));
    return $mem->get('mxtel_'.$key);
}

function memSet($key,$data,$time=20) {
    global $mem;
    $mem->set('mxtel_'.$key, $data,0, $time);
    //$ret = $mem->getResultCode();
}

function mem_get_mysql($key,$q,$nocache=0,$time=20) {
    $nocache=1;
    $data = memGet($key);   //получить данные из кеша
    if($nocache) $data = false;
    if($data === false){
        $data = array();
        $r = _mysql_query($q);
        while ($row = mysql_fetch_array($r))
            $data[] = $row;
        memSet($key, $data,$time);    //поместить данные в кеш
    }
    return $data;   //вернуть массив
}

/*
$data = memGet($key);
//$data = false;
if($data === false){
    $r = mysql_query($q);
    while ($row = mysql_fetch_assoc($r))
        $data[] = $row;
    memSet($key, $data);
}
 */

?>