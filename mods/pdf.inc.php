<?php

$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
//$mpdf->charset_in = 'cp1251'; /*не забываем про русский*/
$stylesheet = file_get_contents('../css/schet_print.css'); /*подключаем css*/
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->list_indent_first_level = 0;

$path = "../schet/201105/";
$d = dir($path);
$schs = array();
while (false !== ($entry = $d->read())) {
    if(strpos($entry,'.html'))
        $schs[] = $entry;
}
sort($schs);
$d->close();

foreach($schs as $v){
    $html = file_get_contents("../schet/201105/$v");
    $mpdf->WriteHTML($html,2); /*формируем pdf*/
    $mpdf->AddPage();
}

$mpdf->Output('../schet/201105/mpdf.pdf', 'I');



?>
