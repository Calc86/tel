<?php
/* @var $this OrgIpController */
/* @var $model OrgIp */

$this->breadcrumbs=array(
    'Org'=>array('users/index','id'=>$oid),
	'Org Ips'=>array('index','oid'=>$oid),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrgIp', 'url'=>array('index')),
	array('label'=>'Manage OrgIp', 'url'=>array('admin')),
);
?>

<h1>Create OrgIp</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>