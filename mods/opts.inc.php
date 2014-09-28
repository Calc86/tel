<?php

$id = 0;
$type = '';
$pid = 0;
$text = '';

switch($act) {
    case 'edit':
        $id = get_var('id');
        $q = "select type,pid,text from options where id=$id";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);
        list($type,$pid,$text) = mysql_fetch_array($r);
    case 'add':
        if($do) {
            $id = post_var('id');
            $type = post_var('type');
            $pid = post_var('pid');
            $text = post_var('text');
            $action = post_var('action');

            if($action=='add') {
                $body = 'Добавлена опция: '.$type;
                $q = "insert into options values(0,'$type',$pid,'$text')";
                $r = _mysql_query($q);
            }
            if($action=='edit') {
                $body = 'Изменена опция: '.$type;
                $q = "update options set type='$type', pid=$pid, text='$text' where id=$id";
                $r = _mysql_query($q);
            }
            $body.= tag_br().tag_a("?gr=$gr&pid=$pid",'Назад');
        }else{
            if(!$pid) $pid = get_var('pid');
            $body = '';
            $body.= '
		<form action="?gr='.$gr.'&act=add&do=1" method="post">
		<table>
		    <!--<tr><td width="35">type</td><td><input type="Text" name="type" value="'.$type.'"></td></tr>-->
		    <tr><td width="35">type</td><td>'.TList($type).'</td></tr>
		    <tr><td>pid</td><td>'.$pid.'</td></tr>
		    <tr><td colspan="2">text</td></tr>
		    <tr><td colspan="2"><textarea cols="120" rows="30" name="text" style="font-size: 8pt;">'.$text.'</textarea></td></tr>
		</table>
		<input type="Hidden" name="action" value='.$act.'>
		<input type="Hidden" name="id" value='.$id.'>
		<input type="Hidden" name="pid" value='.$pid.'>
		<input type="Submit" value="'.$act.'"></form>
	    ';
            $body.= Ex();
        }
        break;
    case 'view':
    default:
        $body = 'Ниже представлены опции для точки монтирования<br>';

        $pid = $_GET['pid'];

        $q = "select * from options where pid=$pid";
        //echo $q;
        $r = _mysql_query($q);
        $n = mysql_num_rows($r);

        $body.= tag_a("?gr=$gr&pid=$pid&act=add",'Добавить опцию');
        if($n) {
            $body.= tag_t_o();
            for($i=0;$i<$n;$i++) {
                $row = mysql_fetch_array($r);
                $id = $row['id'];
                $body.= tag_tr(2,tag_td(tag_a("?gr=$gr&act=edit&id=".$row['id'],$row['type'])));
            }
            $body.= tag_t_c();
        }else 
            $body.= tag_br().'опций нет';
}


function OrgList($sel) {
    //echo $sel;
    $q = "select * from org order by id";
    $r = _mysql_query($q);
    $n = mysql_num_rows($r);

    $html = '';

    $html.='<select name="oid">';
    for($i=0;$i<$n;$i++) {
        $row = mysql_fetch_array($r);
        if($sel == $row['id']) $selected = ' SELECTED';
        else $selected = '';
        $html .= '<option'.$selected.' value="'.$row['id'].'">'.$row['name'];
    }
    $html.= '</select>';
    return $html;
}

function TList($sel) {
    $html = '';

    $html.='<select name="type">';

    ($sel == 'comment') ? $selected=' selected' : $selected='';
    $html.='<option'.$selected.' value="comment">comment';
    ($sel == 'addout')  ? $selected=' selected' : $selected='';
    $html.='<option'.$selected.' value="addout">add_out';
    ($sel == 'repout')  ? $selected=' selected' : $selected='';
    $html.='<option'.$selected.' value="repout">rep_out';

    /*for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	if($sel == $row['id']) $selected = ' SELECTED';
	else $selected = '';
	$html .= '<option'.$selected.' value="'.$row['id'].'">'.$row['name'];
    }*/
    $html.= '</select>';
    return $html;
}

function Ex() {
    $html = '';

    $buf = '';
    $f = fopen("./tmp/ext.in_context","r");
    if($f) {
        while(!feof($f))
            $buf.= fread($f,1024);
        fclose($f);
    }
    $in = $buf;

    $buf = '';
    $f = fopen("./tmp/ext.out_context","r");
    if($f) {
        while(!feof($f))
            $buf.= fread($f,1024);
        fclose($f);
    }
    $out = $buf;


    $html.= '<pre style="font-size: 8pt; background: #d0d0d0; padding: 3px; border: solid 1px #000000;">';
    $html.= $in;
    $html.= '</pre>';
    $html.= '<pre style="font-size: 8pt; background: #d0d0d0; padding: 3px; border: solid 1px #000000;">';
    $html.= $out;
    $html.= '</pre>';
    return $html;
}


?>