<?php
/* @var $this DialOptsController */
/* @var $model DialOpts */

$this->breadcrumbs=array(
	'Dial Opts'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List DialOpts', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage DialOpts', 'url'=>array('admin')),
);
?>

<h1>View DialOpts #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'oid',
		'pid1',
		'pid2',
		'rule',
		'prior',
		'internal',
		'context',
	),
)); ?>
