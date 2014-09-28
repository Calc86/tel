<?php
/* @var $this OrgIpController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Org'=>array('users/index','id'=>$oid),
	'Org Ips',
);

$this->menu=array(
	array('label'=>'Create OrgIp', 'url'=>array('create','oid'=>$oid)),
	//array('label'=>'Manage UserIp', 'url'=>array('admin')),
);
?>

<h1>Org Ips</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
