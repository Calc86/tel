<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 26.08.15
 * Time: 18:11
 */
/* @var $org Org */

//карточка организации

?>

<table style="border: 1px solid #000000; width: 500px;">
    <tr>
        <td width="200">ID:</td><td><?= $org->id ?></td>
    </tr>
    <tr>
        <td width="200">Название:</td><td><?= $org->fullname ?></td>
    </tr>
    <tr>
        <td width="200">Группа:</td><td><?php $group=OrgGroup::model()->findByPk($org->group); echo $group===null ? "null" : $group->name; ?></td>
    </tr>
    <tr>
        <td width="200">Баланс:</td><td><?= $org->money ?></td>
    </tr>
    <tr>
        <td width="200">name:</td><td><?= $org->name ?></td>
    </tr>
    <tr>
        <td width="200">login|pass:</td><td><?= $org->login ?>|<?= $org->passwd ?></td>
    </tr>
    <tr>
        <td width="200">Текущий трафик:</td><td><?= Stat::getTotal(Stat::generateCriteria($org->id,0,0,1,1,1))->data[0]->cost ?> (0 - есть входящие)</td>
    </tr>
    <tr>
        <td width="200">Трафик за прошлый месяц:</td><td><?= Stat::getTotal(Stat::generateCriteria($org->id, -1, -1))->data[0]->cost ?></td>
    </tr>
    <tr>
        <td width="200">Трафик за позапрошлый месяц:</td><td><?= Stat::getTotal(Stat::generateCriteria($org->id, -2, -2))->data[0]->cost ?></td>
    </tr>
    <tr>
        <td width="200">Трафик по москве за прошлый месяц:</td>
        <td><?= Stat::getMoscow(Stat::generateCriteria($org->id, -1, -1))->data[0]->cost ?>
            (<?= Stat::getMoscow(Stat::generateCriteria($org->id, -2, -2))->data[0]->billsec ?>)</td>
    </tr>
    <tr>
        <td width="200">Трафик сотовый трафик за прошлый месяц:</td>
        <td><?= Stat::getSot(Stat::generateCriteria($org->id, -1, -1))->data[0]->cost ?>
            (<?= Stat::getSot(Stat::generateCriteria($org->id, -2, -2))->data[0]->billsec ?>)</td>
    </tr>
    <tr>
        <td width="200">Другой трафик за прошлый месяц:</td>
        <td><?= Stat::getOther(Stat::generateCriteria($org->id, -1, -1))->data[0]->cost ?>
            (<?= Stat::getOther(Stat::generateCriteria($org->id, -2, -2))->data[0]->billsec ?>)</td>
    </tr>
    <tr>
        <td colspan="2"><a href="<?=Yii::app()->getController()->createUrl('org/update',array('id'=>$org->id)) ?>">Редкатировать</a></td>
    </tr>
</table>