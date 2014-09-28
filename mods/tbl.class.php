<?php
/* * * * * * * * * *
 * Author: Calc, calc@list.ru, 8(916) 506-7002
 * ServersX, 2010, Calc (r)
 * * * * * * * * * */
defined("INDEX") or die('404 go to <a href="/">index.php</a>');

class tbl {
    var $t;
    var $c;
    var $d;


    /**
     * Конструктор, получает количество и имена полей и заносит в класс.
     * @param <type> $t имя таблицы
     * @param <type> $d имя базы данных с точкой на конце 'dbase.'
     */
    function tbl($t,$d='') {
        $this->t = $t;
        $this->c = array();
        $this->d = $d;
        $r = mysql_query("SHOW COLUMNS FROM $d$t");
        while ($row = mysql_fetch_assoc($r))
            $this->c[$row['Field']] = $row['Default'];
    }

    /**
     * Получить данные из запроса Select LAST_INSERT_ID()
     * @return int Последний индекс.
     */
    function lid() {
        $q = "select LAST_INSERT_ID()";
        $r = mysql_query($q);
        $row = mysql_fetch_array($r);
        return $row[0];
    }

    /**
     * Установить внутренний массив.
     * @param array $data Данные с ключами. Ключ - имя поля.
     */
    function set($data) {
        $i=0;
        foreach($this->c as $n=>$v)
            $this->c[$n] = $data[$i++];
    }

    /**
     * Сформировать запрос insert, данные в виде массива без ключей
     * @param array $data Данные для вставки
     * @return str запрос
     */
    function insert($data = array()) {
        if(count($data)) $this->set($data);
        $q = "insert into $this->d$this->t values(";
        $qp = '';   //query part
        foreach($this->c as $n=>$v)
            $qp.= "'$v',";
        $qp = substr($qp,0,strlen($qp)-1);
        $q.=$qp.')';
        return $q;
    }

    /**
     * Обновить все поля в таблице
     * @param array $w where, массив с ключами
     * @param array $data where, массив с ключами
     * @return str запрос
     */
    function update($w,$data = array()) {
        if(count($data)) $this->set($data);
        return $this->update_k($w);
    }

    /**
     * Сформировать запрос на обновление записи из массивов. Обновляются те поля, которые указаны в массиве $f
     * @param array $w where key-имя поля, val - значение
     * @param array $f поля-значения. Если не указать, обновляются все поля из внутреннего состояния (см. член set())
     * @return str запрос
     */
    function update_k($w,$f = array()) {
        $c = array();
        if(count($f)==0)
            foreach($this->c as $n=>$v)
                if(isset($w[$n])) continue;
                else $c[$n] = $v;
        else
            foreach($f as $n=>$v)
                if(isset($w[$n])) continue;
                else $c[$n] = $v;
        $qp = '';   //query part
        foreach($c as $n=>$v)
            $qp.= "`$n`='$v', ";
        $qp = substr($qp,0,strlen($qp)-2);
        $qw = '';   //where
        foreach($w as $n=>$v)
            $qw.= "$n='$v' and ";
        $qw = substr($qw,0,strlen($qw)-5);
        $q = "update $this->d$this->t set $qp where $qw limit 1";
        return $q;
    }

    function create($n,$f = array()) {
        //array ->
    }
}
/*
$tbl = new tbl("users");
echo $tbl->insert();
echo tag_br();
$tbl->set(array('0','2','3','4','5','6'));
echo $tbl->update(array('id'=>1));
echo tag_br();
echo $tbl->update(array('id'=>1, 'nick'=>'calc'));
echo tag_br();
echo $tbl->update(array('id'=>1),array('pass' => 123));
*/
?>