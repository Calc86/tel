<?php

$this->breadcrumbs=array(
	'Config',
    'Ext'=>array('ext'),
    'Sip'=>array('sip'),
    'oExt'=>array('oext'),
    'oSip'=>array('osip')
);

$this->menu=array(
	array('label'=>'sip.conf', 'url'=>array('sip')),
	array('label'=>'extensions.conf', 'url'=>array('ext')),
);
?>

<h1>Save and backup config</h1>

<?php $this->renderPartial('_form'/*, array('model'=>$model)*/); ?>

<?php
$body = '';
$body.= '
Основные файлы: sip.conf и extensions.conf<br>
Обновление конфига на сервере происходит автоматически.
';
$fte = filectime("/etc/asterisk/extensions.conf");
$fts = filectime("/etc/asterisk/sip.conf");

$body.= '
<table>
    <tr><td>extensions.conf</td><td>'.date("Y-m-d H:i:s",$fte).'</td></tr>
    <tr><td>sip.conf</td><td>'.date("Y-m-d H:i:s",$fts).'</td></tr>
</table>
';

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
echo $body;
?>