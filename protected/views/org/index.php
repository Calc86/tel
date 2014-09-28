<?php
/* @var $this OrgController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Организации',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage Org', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Организации</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    //'dataProvider'=>$dataProvider,
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
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
        'fullname',
        array(
            //'title'=>'intno',
            'type'=>'raw',
            'value'=>'$data->user_list',
        ),
    ),
)); ?>
