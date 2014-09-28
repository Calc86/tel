<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    'Линии'=>array('index','id'=>$model->oid),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>View Линии #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'oid',
		'intno',
		'secret',
		'pid',
		'dtmfmode',
		'flags',
		'call_limit',
	),
)); ?>
