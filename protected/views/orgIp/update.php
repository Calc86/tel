<?php
/* @var $this OrgIpController */
/* @var $model OrgIp */

$this->breadcrumbs=array(
    'Org'=>array('users/index','id'=>$oid),
    'Org Ips'=>array('index','oid'=>$oid),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OrgIp', 'url'=>array('index','oid'=>$oid)),
	array('label'=>'Create OrgIp', 'url'=>array('create','oid'=>$oid)),
	array('label'=>'View OrgIp', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage UserIp', 'url'=>array('admin')),
);
?>

<h1>Update OrgIp <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>