<?php
/* @var $this OrgListController */
/* @var $model OrgList */

$this->breadcrumbs=array(
	'Org Lists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List OrgList', 'url'=>array('index')),
	array('label'=>'Create OrgList', 'url'=>array('create')),
	array('label'=>'Update OrgList', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OrgList', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OrgList', 'url'=>array('admin')),
);
?>

<h1>View OrgList #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'address',
		'email',
		'send',
	),
)); ?>
