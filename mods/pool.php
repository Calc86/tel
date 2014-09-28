<?php
/* * * * * * * * * *
 * Author: Calc, calc@list.ru, 8(916) 506-7002
 * ServersX, 2010, Calc (r)
 * * * * * * * * * */
defined("INDEX") or die('404 go to <a href="/">index.php</a>');

$do = get_var('do',0);
$id = get_var('id',0);
$prov = get_var('prov',0);

$table = 'num_pool';
$tbl = new tbl($table);
// id prid no lines dt descr
switch($do) {
    case 1: //добавляем элемент
    //$name = isset($_POST['name']) ? $_POST['name'] : '';
    //$hide = isset($_POST['hide']) ? $_POST['hide'] : 0;
        list($prid, $no, $lines, $dt, $descr,$cost,$cost2) =
                array(
                post_var('prid',0), post_var('no') , post_var('lines',1),
                post_var('dt') , post_var('descr'), post_var('cost',0), post_var('cost2',0));
        mysql_query($tbl->insert(
                array(0,$prid, $no, $lines, $dt, $descr,$cost,$cost2)));
        redir("gr=$gr",1);
        break;
    case 2:
        $hide = $_GET['hide'];
        if($hide!=1) $hide=0;
        $id = get_var('id');
        mysql_query($tbl->update_k(array('id'=>$id), array('hide'=>$hide)));
        redir("gr=$gr",1);
        break;
    case 10:
        list($prid, $no, $lines, $dt, $descr,$cost,$cost2) =
                array(
                post_var('prid',0), post_var('no') , post_var('lines',1),
                post_var('dt') , post_var('descr'),post_var('cost'), post_var('cost2',0));
        $id = get_var('id');
        mysql_query($tbl->update(array('id'=>$id),
                array(0,$prid, $no, $lines, $dt, $descr, $cost,$cost2)));
        redir("gr=$gr&prov=$prov",1);
        break;
    case 11:
        $id = get_var('id');
        do_del($table,$id,"gr=$gr",'','',1);
        break;
}

//$id = 0;
$prid = 0;
$no = '';
$lines = 1;
$dt = date("Y-m-d H:i:s");
$descr = '';
$cost = 0;
$cost2 = 0;

if($id) {
    $q = "select * from $table where id=$id";
    $r = mysql_query($q);
    list($id,$prid, $no, $lines, $dt, $descr,$cost,$cost2) = mysql_fetch_array($r);
}


// id hid rid atid user pass folder url descr hide
$body.='
<form action="?gr='.$gr.'&do='.( ($id==0) ? 1 : 10 ).'&id='.$id.'&prov='.$prov.'" method="post">
    Провайдер: '.tag_f_se('prid',$prid,db_sel('pt','','','')).'
    Номер:     '.tag_f_in('no',$no).'
    Линий:     '.tag_f_se('lines',$lines,array(1=>"1","2","3","4","5","6","7","8","9","10",)).'
    Дата:      '.tag_f_in('dt',$dt).'
              '.tag_f_sm($id).'<br>
    Descr:    '.tag_f_ta('descr',$descr,70,3).'<br>
    Cost:     '.  tag_f_in('cost',$cost).'
    Cost2:     '.  tag_f_in('cost2',$cost2).'
</form>';

$provs = db_sel_('pt', array('h'=>'','w'=>''));
foreach($provs as $pid=>$pname)
    $body.= tag_a("?gr=$gr&prov=$pid", $pname).',&nbsp;';

$body.= tag_t_o_('maintbl');
$body.= tag_tr();
$body.= tag_th('id');
$body.= tag_th('Провайдер');
$body.= tag_th('Номер');
$body.= tag_th('Линий');
$body.= tag_th('Дата регистрации в системе');
//$body.= tag_th('descr');
$body.= tag_th('цена <br> 1-й линии');
$body.= tag_th('Сумма');
$body.= tag_th('Продаем<br>за');
$body.= tag_th('Сумма<br>продажи');
$body.= tag_th('x');
$body.= tag_tr(1);


$where = "where 1";
if($prov) $where.= " and pt.id=$prov ";

//$q = "select n.id,pt.name ,n.no,n.lines,n.dt,n.descr from $table as n left join pt on pt.id=n.prid order by n.no";
$q = "
    select
        n.id,
        pt.name,
        n.no,
        n.lines,
        n.dt,
        n.descr,
        n.cost,
        n.cost2
    from $table as n
    left join pt on pt.id=n.prid
    $where
    order by n.no
";

//echo $q;
$r = _mysql_query($q);
$n = mysql_num_rows($r);
$sum = 0;
$sum2 = 0;
for($i=0;$i<$n;$i++) {
    list($id,$prid, $no, $lines, $dt, $descr,$cost,$cost2) = mysql_fetch_array($r);
    $body.= tag_tr();
    $body.= tag_td(tag_a("?gr=$gr&id=$id&prov=$prov",$id),$i);
    $body.= tag_td($prid,$i);
    $body.= tag_td(tel_num($no,1),$i);
    $body.= tag_td(1,$i,'right');
    $body.= tag_td($dt,$i,'center');
    //$body.= tag_td($descr,$i);

    $body.= tag_td($cost,$i,'right');
    $lc = $cost*1;
    $sum+=$lc;
    $body.= tag_td($lc,$i,'right');

    $body.= tag_td(tag_b($cost2,!$cost2),$i,'right');
    $lc2 = $cost2*1;
    $sum2+=$lc2;
    $body.= tag_td(tag_b($lc2,!$lc2),$i,'right');


    $body.= tag_td(tag_a_x("?gr=$gr&id=$id&do=11"),$i);
    $acc = '';
    $body.= tag_tr(1);

    if($lines>1){
        $body.= tag_tr();
        $body.= tag_td_('', array("colspan"=>3));
        $body.= tag_td($lines-1,$i,'right');
        $body.= tag_td_('', array("colspan"=>2));
        //$body.= tag_td($cost);
        $lc = $cost*($lines-1);
        $sum+=$lc;
        $body.= tag_td($lc,$i,'right');

        $lc2 = $cost2*($lines-1);
        $sum2+=$lc2;
        $body.= tag_td('');
        $body.= tag_td($lc2,$i,'right');

        $body.= tag_tr(1);
    }
    $body.=tag_tr();
    $body.=tag_tr(1);
}
$body.= '<tr><td colspan="6"></td><td align="right">'.$sum.'</td><td></td><td align="right">'.$sum2.'</td></tr>';
//$body.= '<td colspan="7">'.$n.' записей</td>';
$body.= tag_t_c();
$body.= $n.' номеров';

?>
