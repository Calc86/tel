<?php
/* @var $this OrgIpController */
/* @var $model OrgIp */

$this->breadcrumbs=array(
    'Org'=>array('users/index','id'=>$oid),
    'Org Ips'=>array('index','oid'=>$oid),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserIp', 'url'=>array('index','oid'=>$oid)),
	array('label'=>'Create UserIp', 'url'=>array('create','oid'=>$oid)),
	array('label'=>'Update UserIp', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserIp', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage UserIp', 'url'=>array('admin')),
);
?>

<h1>View OrgIp #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'org_id',
		'ip',
		'mask',
	),
)); ?>
