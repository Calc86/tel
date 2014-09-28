<?php
/* @var $this UsersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Организация',
);

$this->menu=array(
    array('label'=>'Статистика звонков', 'url'=>array('stat/index', 'oid'=>$oid)),
    array('label'=>'Управление'),
    array('label'=>'Добавить линию', 'url'=>array('create', 'oid'=>$oid)),
    array('label'=>'Добавить маршрут', 'url'=>array('dialOpts/create', 'oid'=>$oid)),
    array('label'=>'Редактировать', 'url'=>array('org/view', 'id'=>$oid)),
    array('label'=>'Привязка по IP', 'url'=>array('orgIp/index', 'oid'=>$oid)),
    //array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id)),
    array('label'=>'История', 'url'=>array('log/index')),
    array('label'=>'Входящий лог', 'url'=>array('log/inbound','oid'=>$oid)),
	//array('label'=>'Manage Users', 'url'=>array('admin')),
    //array('label'=>'Маршруты набора номера', 'url'=>array('dialOpts/index','oid'=>$oid)),
);
?>

<h1>Линии</h1>

<h6>Конечные точки</h6>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("users/view",array("id"=>$data->primaryKey)))',
        ),
        array(
            'name'=>'oid',
            'type'=>'raw',
            'value'=>'$data->org->name',
        ),
        array(
            'name'=>'intno',
            'type'=>'raw',
            'value'=>'CHtml::link($data->intno,Yii::app()->createUrl("users/update",array("id"=>$data->primaryKey)))',
        ),
        'intno',
        'secret',
         array(
             'name'=>'pid',
             'type'=>'raw',
             'value'=>'$data->peer->tel_id',
         ),
        'dtmfmode',
        'flags',
        'call_limit',
        't38',
        'criv',
        'nat',
        'dtmf',
        array(
            'name'=>'ip',
            'type'=> 'raw',
            'value' => array($ast,'render_user_ip'),
        ),
    ),
));

?>

<i>* - имеется привязка по IP</i><br><br><br>


    <h6>Номера</h6>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProviderPeers,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("Peers/view",array("id"=>$data->primaryKey)))',
        ),
        array(
            'name'=>'no',
            'type'=>'raw',
            'value'=>'$data->num->no',
        ),
        array(
            'name'=>'peer',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "<s>".$data->tel_id."</s>" : $data->tel_id;',
        ),
        array(
            'name'=>'ban',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "Yes" : "No";',
        ),
        array(
            'name'=>'route',
            /*'type'=> 'raw',*/
            'value' => array($ast,'render_peer_route_num'),
            'cssClassExpression'=>'"route"',
        ),
        array(
            'name'=>'registry',
            /*'type'=> 'raw',*/
            'value' => array($ast,'render_peer_registry'),
            'cssClassExpression'=>'"registry"',
        ),
    ),
)); ?>


<h6>Оборудование</h6>
<?php  $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProviderDev,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("devices/view",array("id"=>$data->primaryKey)))',
        ),
        array(
            'name'=>'id',
            'type'=>'raw',
            'value'=>'$data->type->name',
        ),
        array(
            'name'=>'oid',
            'type'=>'raw',
            'value'=>'$data->org->name',
        ),
        'ip',
        'mac',
        'serial',
        'soft_ver',
        'hard_ver',
        'user',
        'admin',
    ),
)); ?>

    <h6>Правила набора</h6>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProviderOpts,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("dialOpts/view",array("id"=>$data->primaryKey)))',
        ),
        array(
            'name'=>'oid',
            'type'=>'raw',
            'value'=>'$data->org->name',
        ),
        /*array(
            'name'=>'pid1',
            'type'=>'raw',
            'value'=>'$data->peer1->peer',
        ),
        array(
            'name'=>'pid2',
            'type'=>'raw',
            'value'=>'$data->peer2->peer',
        ),*/
        'rule',
        //'prior',
        'internal',
        array(
            'name'=>'internal',
            'type'=>'raw',
            'value'=>'$data->user->intno',
        ),
        'context',
        array(
            'name'=>'raw',
            'type'=>'raw',
            'value'=>'CHtml::tag("pre",array(),$data->options)',
        ),
    ),
)); ?>