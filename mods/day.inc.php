<?php

$ds = get_var('ds',date("Y-m-1"));
$de = get_var('de',date("Y-m-t"));
$opts = get_var('opts',array('out'=>'out'));
$body.='
    <form>
    '.tag_f_in('ds', $ds).'
    '.tag_f_in('de', $de).'
    '.tag_f_ch('opts','in',isset($opts['in']),'Показать входящие','in').'
    '.tag_f_ch('opts','out',isset($opts['out']),'Показать исходящие','out').'
    '.tag_f_ch('opts','1',isset($opts['gdir']),'Группировать по направлению','gdir').'
    '.tag_f_ch('opts','1',isset($opts['gdch']),'Группировать по каналу назначения','gdch').'
    '.tag_f_ch('opts','0',isset($opts['emcn']),'исключить mcn','emcn').'
    '.tag_f_sm(0,array("Показать")).'
    '.tag_f_hi('gr', $gr).'
    </form>';

$q = "  select
            DATE_FORMAT(cd,'%Y-%m-%d') as day,
            direction,
            ch,
            dstch,
            sum(billsec) as billsec,
            ROUND(sum(cost),2) as cost,
            count(*)
        from stat
        where (".(isset($opts['out']) ? "direction='out' or" : '')." ".(isset($opts['in']) ? "direction='in' or" : '')." direction='') and cause='ANSWERED' and
        cd>='$ds 00:00:00' and cd<='$de 23:59:59'".
        (isset($opts['emcn']) ? " and dstch not like '%1903%'" : '').
        "group by day".
        ((isset($opts['gdch']) ? ',dstch' : '')).
        ((isset($opts['gdir']) ? ',direction' : '')).
        " order by cd desc";
$r = _mysql_query($q);
$n = mysql_num_rows($r);

$date = 0;
$bss = 0;
$costs = 0;
$counts = 0;

$bssa = 0;
$costsa = 0;
$countsa = 0;
$k=0;

$tbl_a = array();
$lbl_a = array();

$body.= tag_t_o();
$body.= tag_tr();
$body.= tag_th('n');
$body.= tag_th('date');
if(isset($opts['gdir'])) $body.= tag_th('dir');
$body.= tag_th('ch');
$body.= tag_th('dstch');
$body.= tag_th('bs');
$body.= tag_th('cost');
$body.= tag_th('count');
$body.= tag_tr(1);
for($i=0;$i<$n;$i++,$k++){
    list($day,$direction,$ch,$dstch,$bs,$cost,$count) = mysql_fetch_array($r);
    if(!$date) $date = strtotime($day);
    if($date!=strtotime($day) && (isset($opts['gdch']) || isset($opts['gdir']) ) ){
        $date = strtotime($day);
        $body.= tag_tr();
        $body.= tag_td('',1,'right','#888888');
        $body.= tag_td('',1,'right','#888888');
        if(isset($opts['gdir'])) $body.= tag_td('',1,'right','#888888');
        $body.= tag_td('',1,'right','#888888');
        $body.= tag_td('',1,'right','#888888');
        $body.= tag_td(toTime($bss),1,'right','#888888');
        $body.= tag_td($costs,1,'right','#888888');
        $body.= tag_td($counts,1,'right','#888888');
        $body.= tag_tr(1);
        $bss=0;
        $counts=0;
        $costs=0;
        $k=0;
    }
    $bss+=$bs;
    $costs+=$cost;
    $counts+=$count;
    $bssa+=$bs;
    $costsa+=$cost;
    $countsa+=$count;
    $d = date("d",strtotime($day));
    $tbl_a[$d][$ch] = $count;
    $lbl_a[$ch] = 1;
    $body.= tag_tr();
    $body.= tag_td($k+1,$i,'right');
    $body.= tag_td($day,$i);
    if(isset($opts['gdir'])) $body.= tag_td($direction,$i);
    $body.= tag_td($ch,$i);
    $body.= tag_td($dstch,$i);
    $body.= tag_td(toTime($bs),$i,'right');
    $body.= tag_td($cost,$i,'right');
    $body.= tag_td($count,$i,'right');
    $body.= tag_tr(1);
}
$body.= tag_tr();
$body.= tag_td('',1,'right','#888888');
$body.= tag_td('',1,'right','#888888');
if(isset($opts['gdir'])) $body.= tag_td('',1,'right','#888888');
$body.= tag_td('',1,'right','#888888');
$body.= tag_td('',1,'right','#888888');
$body.= tag_td(toTime($bss),1,'right','#888888');
$body.= tag_td($costs,1,'right','#888888');
$body.= tag_td($counts,1,'right','#888888');
$body.= tag_tr(1);
$body.= tag_tr();
$body.= tag_td('',1,'right','#888888');
$body.= tag_td('',1,'right','#888888');
if(isset($opts['gdir'])) $body.= tag_td('',1,'right','#888888');
$body.= tag_td('',1,'right','#888888');
$body.= tag_td('',1,'right','#888888');
$body.= tag_td(toTime($bssa),1,'right','#888888');
$body.= tag_td($costsa,1,'right','#888888');
$body.= tag_td($countsa,1,'right','#888888');
$body.= tag_tr(1);
$body.= tag_t_c();


$jq = "
    //alert(123);
    //$('#graf').graphTable({series: 'columns', position: 'replace', width: 800, height: 300});
    
    $('#graf').graphTable({series: 'columns', position: 'replace', width: 1200, height: 500});
    //$('<div class=\"button\" style=\"right:20px;top:20px\">zoom out</div>').appendTo('#div-graph').click(function (e) {
    //    //e.preventDefault();
    //    //plot.zoomOut();
    //});
";
$body.= tag_br(3);
$body.= tag_t_o_("graf");
$body.= tag_tr();
$body.= tag_th(sp());
foreach($lbl_a as $ch=>$v){
    $body.= tag_th($ch);
}
$body.= tag_th("All");
$body.= tag_tr(1);
ksort($tbl_a);
foreach($tbl_a as $d=>$ar){
    $body.= tag_tr();
    $body.= tag_th($d);
    foreach($lbl_a as $ch=>$v)
        $body.= tag_td(isset($ar[$ch]) ? $ar[$ch] : 0);
    $body.= tag_td(array_sum($ar));
    $body.= tag_tr(1);
}

$body.= tag_t_c();

?>
