<?php
/* @var $this OrgGroupController */
/* @var $model OrgGroup */

$this->breadcrumbs=array(
	'Org Groups'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List OrgGroup', 'url'=>array('index')),
	array('label'=>'Create OrgGroup', 'url'=>array('create')),
	array('label'=>'Update OrgGroup', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OrgGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OrgGroup', 'url'=>array('admin')),
);
?>

<h1>View OrgGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'comment',
	),
)); ?>
