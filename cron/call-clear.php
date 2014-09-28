<?php
 
$days = 90;
$path = "/var/calls/";
//$cmd = '/usr/bin/find {path} -mtime +{days} -and -type f -and -name "*{cam}*avi"';
$cmd = '/usr/bin/find {path} -mtime +{days} -and -type f';
$exec = str_replace(array('{path}','{days}'), array($path,$days), $cmd);

//echo $exec;

$ret = `$exec`;
//echo $exec;
$lines = explode("\n",$ret);
echo 'files:'.(count($lines)-1)."\n";  // -1 is "\n" where is empty find
//print_r($lines);
foreach($lines as $file){
    if($file != ''){
	exec("rm -f $file");
	echo '+';
    }
}

//удалить пустые папки и файлы
$ret = `find /var/calls/ -empty -mtime +1 -delete`;
   
