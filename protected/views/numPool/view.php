<?php
/* @var $this NumPoolController */
/* @var $model NumPool */

$this->breadcrumbs=array(
	'Номера'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Обновить', 'url'=>array('update', 'id'=>$model->id)),
	//array('label'=>'Delete NumPool', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage NumPool', 'url'=>array('admin')),
);
?>

<h1>View Номер #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'prid',
		'no',
		'lines',
		'dt',
		'descr',
		'cost',
		'cost2',
	),
)); ?>
