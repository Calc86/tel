<?php
/* @var $this LogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Logs',
);

$this->menu=array(
	array('label'=>'Create Log', 'url'=>array('create')),
	array('label'=>'Manage Log', 'url'=>array('admin')),
);
?>

<h1>Logs</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        //'id',
        'controller',
        'action',
        array(
            'name'=>'dt',
            'value'=>'date("Y-m-d H:i:s",$data->dt)',
            'filter'=>'&nbsp;'
        ),
        'user',
        'name',
        'text',
    ),
    /*'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("org/view",array("id"=>$data->primaryKey)))',
            'filter'=>"&nbsp",
        ),
        //'name',
        array(
            'name'=>'name',
            'type'=>'raw',
            'value'=>'CHtml::link($data->name,Yii::app()->createUrl("users/index",array("id"=>$data->primaryKey)))',
        ),
        'login',
        //'money',
        'fullname'
    ),*/
)); ?>
