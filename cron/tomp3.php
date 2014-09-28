<?php
$root = "/var/calls/";
$root_files = array();
$d = dir($root);
while (false !== ($entry = $d->read())) if(is_file($root.$entry)) if(substr($root.$entry,-3)=='wav') array_push($root_files,$entry);
$d->close();

//echo '<pre>';
echo date("Y-m-d H:i:s ").count($root_files)."\n";
foreach($root_files as $wav){
    $name = substr($wav,0,-4);
    echo $name."-";
    $aa = preg_split('/-/',$name);
    $src = $aa[0];
    //$dst = $aa[2];
    //$ch = $aa[1];
    $uid = $aa[3];
    $aa = preg_split('/\./',$uid);
    $time = $aa[0];
    if((mktime() - $time) < 12*60*60){
        echo '12ч еще нет...'."\n";
        continue;
    }
    $dir = date("Y-m-d/",$time);
    $md = '';
    if(!file_exists($root.$dir))
        $md = "mkdir $root$dir";
    $lame = "nice -n19 lame -s8 -mm --abr 64 --silent $root$name.wav $root$dir$name.mp3";
    $rm = "rm -f $root$name.wav";
    if($md!='') passthru($md);
    passthru($lame);
    passthru($rm);
    echo "done\n";
}


//print_r($root_files);
?>