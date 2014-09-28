<?php
$dir = array("''","'otr'","'itr'","'redir'","'out'");
$la = array("'Dial'","'Transferred Call'");

$date = '201206';

$show_prev = get_var('sp',0);
$oid = get_var('oid',0);
$sch = get_var('sch',0);

$schet = array(
    'pp' => 1,
    'name' => '',
    'year_mont' => $date,
    'no' => $oid,
    'date' => strftime('%d.%m.%Y'),
    'ooo' => '',
    'tbl' => '',
    'sum_bn' => 0,
    'sum_n' => 0,
    'sum' => 0,
    'sum_text' => 0,
    'title' => ''
);

$body.= tag_a("?gr=$gr&sp=1","показать превышение(займет длительное время)").sp();
if($oid) $body.= tag_a("?gr=$gr#oid$oid","Назад");
$body.= tag_t_o_('maintbl');
$body.= tag_tr();
$body.= tag_th('org_name');
$body.= tag_th('Номера (пров)');
$body.= tag_th('Номера (польз)');
//$body.= tag_th('peers');
//$body.= tag_th('users');
$body.= tag_th('превышение');
$body.= tag_th('счет');
$body.= tag_tr(1);

$sum_abon = 0;  //сумма абонентки по ценам провайдеров
$sum_abon2 = 0;  //сумма абонентки по нашим ценам
$sum_prev = 0;  //сумма превышения

$where = '';
if($oid)
    $where = "where id in($oid)";
$q = "select * from org $where order by name";
$data = mem_get_mysql($date."_org$oid", $q,0);
$n = count($data);

for($i=0;$i<$n;$i++){
    $row = $data[$i];


    $schet['name'] = $row['login'];
    //$schet['title'] = $row['login'];
    $schet['ooo'] = $row['fullname'];
    $body.= tag_tr();
    $body.= tag_td(
            '<a name="oid'.$row['id'].'">'.
            tag_a("?gr=$gr&oid=$row[id]",$row['name']).
            '</a>'
            );

    $nums = array();

    $sum = 0;
    $sum2 = 0;
    $tbl = tag_t_o(0,'','100%');
    $tbl2 = tag_t_o(0,'','100%');
    $qn = "select distinct(nid) as nid from peers where oid=$row[id]";
    $datan = mem_get_mysql($date."_nid$oid".'_'.$row['id'], $qn,0);
    $nn = count($datan);
    for($k=0;$k<$nn;$k++)
    {
        $rown = $datan[$k];
        $nums[] = $rown['nid'];

        $qnn = "select `no`,`lines`,`cost`,`cost2` from num_pool where id=$rown[nid]";
        $datann = mem_get_mysql($date."_num_pool$oid".'_'.$row['id'].'_'.$rown['nid'], $qnn,0);
        $nnn = count($datann);
        for($x=0;$x<$nnn;$x++){
            $rownn = $datann[$x];
            $tbl.= tag_tr();
            $tbl.= tag_td($rownn[0]."($rownn[1]) - ".money($rownn[1]*$rownn[2]),0,'right');
            $sum+= $rownn['cost']*$rownn[1];
            $tbl.= tag_tr(1);

            $tbl2.= tag_tr();
            $tbl2.= tag_td($rownn[0]."($rownn[1]) - ".money($rownn[1]*$rownn[3]),0,'right');
            $sum2+= $rownn['cost2']*$rownn[1];
            $tbl2.= tag_tr(1);

            $schet['tbl'].= tag_tr();
                $schet['tbl'].= 
                        tag_td_($schet['pp']++,array('class'=>'pp')).
                        tag_td('Абонентская плата за телефонный номер 8495'.$rownn[0]). //Предмет счета
                        tag_td_(money($rownn[1],0,' ','.',''),array('class'=>'num')).                      //Количество
                        tag_td_(money($rownn[3],2,' ','.',''),array('class'=>'cost')).                      //Стоимость, руб.
                        tag_td_(money($rownn[1]*$rownn[3],2,' ','.',''),array('class'=>'sum_bn')).            //Сумма, руб.
                        tag_td_(0,array('class'=>'sum_n')).                                                  //Сумма налога, руб.
                        tag_td_(money($rownn[1]*$rownn[3],2,' ','.',''),array('class'=>'sum'));            //Сумма с учётом налога, руб.
            $schet['tbl'].= tag_tr(1);
            
        }
    }
    //$tbl.= tag_tr();
    //$tbl.= tag_td('--------',0,'center');
    //$tbl.= tag_tr(1);
    $tbl.= tag_tr();
    $tbl.= tag_td('<b>Сумма:</b> '.money($sum),0,'right');
    $sum_abon+=$sum;
    $tbl.= tag_tr(1);
    $tbl.= tag_t_c();

    $tbl2.= tag_tr();
    $tbl2.= tag_td('<b>Сумма:</b> '.money($sum2),0,'right');
    $sum_abon2+=$sum2;
    $tbl2.= tag_tr(1);
    $tbl2.= tag_t_c();
    //$body.= tag_td(implode(',',$nums));
    $body.= tag_td($tbl);
    $body.= tag_td($tbl2);

    $peers = get_peers($row['id']);
    //$body.= tag_td(implode(tag_br(),$peers));
    $ps = array();
    foreach($peers as $peer)
        $ps[] = "'$peer'";

    $users = get_users($row['id']);
    //$body.= tag_td(implode(tag_br(),$users));
    $us = array();
    foreach($users as $user)
        $us[] = "'SIP/$user'";


    if($show_prev || $oid!=0){
        $prev = get_cost($row['id'],$ps, $us);
        $schet['tbl'].= tag_tr();
            $schet['tbl'].= 
                    tag_td_($schet['pp']++,array('class'=>'pp')).
                    tag_td_('Услуги связи вне абонентской платы',array('class'=>'name')).       //Предмет счета
                    tag_td_(money(1 ,0,' ','.',''),array('class'=>'num')).                             //Количество
                    tag_td_(money($prev,2,' ','.',''),array('class'=>'cost')).                          //Стоимость, руб.
                    tag_td_(money($prev,2,' ','.',''),array('class'=>'sum_bn')).                          //Сумма, руб.
                    tag_td_(0,array('class'=>'sum_n')).                                                  //Сумма налога, руб.
                    tag_td_(money($prev,2,' ','.',''),array('class'=>'sum'));                          //Сумма с учётом налога, руб.
        $schet['tbl'].= tag_tr(1);
        $sum_prev+=$prev;
        $body.= tag_td(money($prev),0,'right');

        $body.= tag_td(money($prev+$sum2),0,'right');
        $schet['sum'] = money($prev+$sum2,2,' ','.','');
        $schet['sum_text'] = num2str(money($prev+$sum2,2,''));
    }

    $body.= tag_tr(1);
    //print_r($row);
}
$body.= tag_tr();
$body.= tag_td('');
$body.= tag_td(money($sum_abon),0,'right');
$body.= tag_td(money($sum_abon2),0,'right');
$schet['sum_bn'] = money($sum_abon2+$sum_prev,2,' ',',','');
$body.= tag_td(money($sum_prev),0,'right');
$body.= tag_tr(1);
$body.= tag_t_c();

if($oid){
    $body.= tag_a("?gr=$gr&oid=$oid&sch=1","Создать счет").tag_br();
    $url = '/schet/'.$date.'/'.$schet['name'].'.html';
    $body.= tag_a($url,'счет:','_blank');
    $body.= '<iframe src="'.$url.'?r='.rand().'" width="100%" height="400"></iframe>';

    
    if($sch){
        $file = "../schet/$date/".$schet['name'].'.html';
        if($sch==2){
            unlink($file);
        }  else {
            $buf = file_get_contents('./schet.tmp.php');
            $s = array();
            foreach($schet as $k=>$v)
                $s[]="{{$k}}";
            $buf = str_replace($s, $schet, $buf);
            $f = fopen($file,'w');
            fwrite($f, $buf);
            fclose($f);
        }
    }
    //print_r($schet);
}

//implode($glue, $piecesarray)
function get_peers($oid) {
    global $date;
    $q = "select id,username from peers where oid=$oid";
    $data = mem_get_mysql($date."_peers$oid", $q);
    $n = count($data);

    $peers = array();
    for($i=0;$i<$n;$i++){
        $row = $data[$i];
        $peers[$row['id']] = $row['username'];
    }
    return $peers;
}

function get_users($oid) {
    global $date;
    $q = "select id,intno from users where oid=$oid";
    $data = mem_get_mysql($date."_users$oid", $q);
    $n = count($data);

    $peers = array();
    for($i=0;$i<$n;$i++){
        $row = $data[$i];
        $peers[$row[0]] = $row[1];
    }
    return $peers;
}

function get_cost($oid,$ps,$us) {
    global $la,$dir,$date;
    if(count($ps) && count($us)){
        $q = "
            select
                sum(s.cost) as cost
            from
                cdr as c left join stat as s on c.id=s.cdrid
            where
                (
                    (s.ch in (".implode(',',$us)."))
                    or
                    (s.dstch in (".implode(',',$us)."))
                    or
                    (s.dstch like 'Local%' and s.dst in(".implode(',',$ps)."))
                )
                and
                (c.lastapp in (".implode(',',$la)."))
                and
                s.cd >= '2012-06-01 00:00:00' and s.cd <= '2012-06-30 23:59:59'
                and
                (s.cause='ANSWERED')
                and
                (
                    s.direction in (".implode(',',$dir).")
                )
            ";
        $data = mem_get_mysql($date."_prev$oid", $q,0,60*60*10);
        $n = count($data);
        //$rc = _mysql_query($qc);
        //$nc = mysql_num_rows($rc);
        //if(!$rc) echo $qc;
        $cost = 0;
        //list($cost) = mysql_fetch_row($rc);
        list($cost) = $data[0];
        if(is_null($cost)) $cost=0;

        return $cost;
    }else
    {
        return '';
    }
}

?>
