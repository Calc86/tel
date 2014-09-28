<?php
/* @var $this NumPoolController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Номера',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage NumPool', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Номера</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("numPool/view",array("id"=>$data->primaryKey)))',
        ),
        array(
            'name'=>'prid',
            'type'=>'raw',
            'value'=>'$data->pt->name',
        ),
        'no',
        'lines',
        'dt',
        array(
            'name'=>'cost',
            'type'=>'raw',
            'value'=>'$data->cost." руб."',
        ),
        array(
            'name'=>'cost2',
            'type'=>'raw',
            'value'=>'$data->cost2." руб."',
        ),
    ),
)); ?>
