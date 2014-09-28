<?php

/*
Calc
Статистика по деньгам.
аля пин код в деговской базе
*/

$oid = get_var('oid',0);
$mon = get_var('mon',date("m"));
$year = get_var('year',date("Y"));

if($oid) {
    //$body.= '123';
    $q = "select * from org where id=$oid";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);
    $row = mysql_fetch_array($r);
    $name = $row['name'];

    $maxd =  date('t',strtotime("$year-$mon-01 12:00:00"));	//количество дней в месяце

    $q = "select * from money
            where oid=$oid and dt>='$year-$mon-01 00:00:00'
            and dt<='$year-$mon-$maxd 23:59:59' order by day";
    //echo $q;
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);

    $start_m = 0;	//на начало месяца
    $sum = 0;	//на конец рассчета
    $sum_m = 0; //сумма за месяц
    $tbl='';
    if($n) {
        //echo 1;
        //$row = mysql_fetch_array($r);
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            if($row['day']==0) {
                $start_m = $row['sum'];
                $sum = $start_m;
                continue;
            }
            $sum+=$row['sum'];
            $sum_m+=$row['sum'];

            $tbl.= tag_tr();
            $tbl.= tag_td($row['day'],$i);
            $tbl.= tag_td('',$i);
            //$tbl.= tag_td($row['sum'],$i,'right');
            //$tbl.= tag_td('руб.',$i);
            $tbl.= tag_td(money($row['sum']),$i,'right');
            $tbl.= tag_td($row['name'],$i);
            $tbl.= tag_td($row['comment'],$i);
            $tbl.= tag_tr(1);
        }
    }
    else {
        //нет записей на месяц, т.е всё по нулям
        $start_m = 0;
        $sum = 0;
    }

    $cur_tbl = '';
    if($mon==date('m') && $year==date('Y')) {

        $q = "select * from users where oid=$oid";
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        $in = '';
        for($i=0;$i<$n;$i++) {
            $row = mysql_fetch_array($r);
            $in.="'SIP/".$row['intno']."'";
            if($i!=$n-1) $in.=',';
        }
        $cyear = date('Y');
        $cmon = date('m');
        /*$q = "select ROUND(sum(cost),2) from stat
                where (ch IN($in) or dstch IN($in)) and cd>='$cyear-$cmon-01 00:00:00'"*/
        //по закону о связи считаем только исходящие
        $q = "select ROUND(sum(cost),2) from stat
                where ch IN($in) and cd>='$cyear-$cmon-01 00:00:00'";
        $r = _mysql_query($q);
        $row = mysql_fetch_array($r);

        $cur_tbl.= '<h3 align="center">Текущие расходы: '.(-$row[0]).' руб.</h3>';
        $cur_tbl.= '<h2 align="center" style="color: red; font-weight: normal;">
            Текущие состояние счета: '.($sum-$row[0]).' руб.</h2>';
    }

    $body.= '<h2 align="center" style="padding: 0px; margin: 0px;">Организация::Статистика</h2>';
    $body.= '<p align="center" style="padding: 0px; margin: 0px;">';
        $body.= tag_a("?gr=$gr&oid=$oid&year=".($year-1)."&mon=$mon",'[<<<] ');
        $a = op_month($mon, $year, -1);
        $body.= tag_a("?gr=$gr&oid=$oid&year=$a[y]&mon=$a[m]",'[<<]');
        $body.= ' | '.month($mon).' '.$year.' года | ';
        $a = op_month($mon, $year, +1);
        $body.= tag_a("?gr=$gr&oid=$oid&year=$a[y]&mon=$a[m]",'[>>]');
        $body.= tag_a("?gr=$gr&oid=$oid&year=".($year+1)."&mon=$mon",' [>>>]');
    $body.= '</p>';
    $body.= '<p align="center" style="padding: 0px; margin: 0px;">Организация: '.$name;
        $body.= tag_a("?gr=stat&oid=$oid&ds=$year-$mon-01&de=$year-$mon-$maxd",'Статистика');
    $body.= '</p>';
    $body.= tag_t_o_(array('id'=>'maintbl', 'align'=>'center'));
    $body.= tag_tr();
    $body.= '<td colspan="7" align="center"><hr noshade>
		Остаток на начало месяца: '.$start_m.' руб.
		<hr noshade></td>';
    $body.= tag_tr(1);
    $body.= tag_tr();
        $body.= tag_th('Число');
        $body.= tag_th('_');
        //$body.= tag_th('Сумма');
        //$body.= tag_th('Валюта');
        $body.= tag_th('Сумма');
        $body.= tag_th('Наименование операции');
        $body.= tag_th('Комментарий');
    $body.= tag_tr(1);
    $body.= tag_tr(2,$tbl);
    $body.= tag_tr();
    $body.= '<td colspan="7" align="center">
		Итог за месяц: '.$sum_m.' руб.</td>';
    $body.= tag_tr(1);
    $body.= tag_tr();
    $body.= '<td colspan="7" align="center"><hr noshade>
		Итог без учёта незавершённых периодов: '.$sum.' руб.
		<hr noshade></td>';
    $body.= tag_tr(1);
    $body.= tag_t_c();
    $body.= $cur_tbl;
}
else
    $body.= 'Организация не выбрана';

function month($m) {
    switch($m) {
        case 1: return 'Январь';
        case 2: return 'Февраль';
        case 3: return 'Март';
        case 4: return 'Апрель';
        case 5: return 'Май';
        case 6: return 'Июнь';
        case 7: return 'Июль';
        case 8: return 'Август';
        case 9: return 'Сентябрь';
        case 10: return 'Октябрь';
        case 11: return 'Ноябрь';
        case 12: return 'Декабрь';
        default: return 'unknown';
    }
}


?>