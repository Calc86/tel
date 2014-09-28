<?php

isset($_GET['act']) ? $act=$_GET['act'] : $act = '';

if($act=='cb') {
    $body = '<a href="?gr='.$gr.'">Назад</a>';
    $date = date("Y_m_d_H_i_s");

    //тут создаем бэкап
    mkdir("/etc/asterisk/bak/$date");
    //копируем 2 файла
    $sip = "sip.conf";
    $ext = "extensions.conf";
    copy("/etc/asterisk/$sip","/etc/asterisk/bak/$date/$sip");
    copy("/etc/asterisk/$ext","/etc/asterisk/bak/$date/$ext");
    //обновить
    $file_sip = "";
    require("./mods/sip.inc.php");
    $file_ext = "";
    require("./mods/ext.inc.php");

    //записать
    $f = fopen("/etc/asterisk/sip.conf","w");
    if($f) {
        fwrite($f,$file_sip);
        fclose($f);
    }
    $f = fopen("/etc/asterisk/extensions.conf","w");
    if($f) {
        fwrite($f,$file_ext);
        fclose($f);
    }

    //обновить
    passthru('sudo asterisk -rx "sip reload"');
    passthru('sudo asterisk -rx "dialplan reload"');
}
else {

    $body.= '1';

    /*
    вывести текущую информацию о файлах
    */

    $body.= '
	Основные файлы: sip.conf и extensions.conf<br>
    ';
    $fte = filectime("/etc/asterisk/extensions.conf");
    $fts = filectime("/etc/asterisk/sip.conf");

    $body.= '
	<table>
	    <tr><td>extensions.conf</td><td>'.date("Y-m-d H:i:s",$fte).'</td></tr>
	    <tr><td>sip.conf</td><td>'.date("Y-m-d H:i:s",$fts).'</td></tr>
	</table>
    ';

    $body.= '<a href="?gr='.$gr.'&act=cb">Сделать Backup и обновить конфигурацию</a>';

    //список бэкапов
    $body.='<br><br>Существующие Backup';

    $path = '/etc/asterisk/bak/';
    $d = dir($path);
    //echo "Handle: " . $d->handle . "\n";
    //echo "Path: " . $d->path . "\n";
    $baks = "";
    $i=0;

    while (false !== ($entry = $d->read()))
        if(is_dir($path.$entry) && $entry != '.' && $entry != '..') {
            $ft = filectime($path.$entry);
            $baks[$entry] = $ft;
        }
    arsort($baks);
    $d->close();
    //print_r($baks);

    $body.= '<table cellpadding="1" cellspacing="1" style="font-size: 9pt;">';
    foreach($baks as $name=>$time)
        $body.='<tr><td>'.$name.'</td><td>'.date("Y-m-d H:i:s", $time).'</td></tr>';
    $body.= '</table>';
}


?>