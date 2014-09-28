<?php
/* @var $this OrgController */
/* @var $model Org */

$this->breadcrumbs=array(
	'Организации'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
	//array('label'=>'Delete Org', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage Org', 'url'=>array('admin')),
);
?>

<h1>View Организации #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'login',
		'passwd',
		'hash',
		'money',
		'fullname',
	),
)); ?>
