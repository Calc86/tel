<?php
/* @var $this DevicesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Оборудование',
);

$this->menu=array(
    Devices::$MENU_CREATE,
    array('label'=>'Типы оборудования', 'url'=>array('dtypes/index')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
	//array('label'=>'Manage Devices', 'url'=>array('admin')),
);
?>

<h1>Оборудование</h1>

<?php  $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
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
