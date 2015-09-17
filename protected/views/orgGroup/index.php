<?php
/* @var $this OrgGroupController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Org Groups',
);

$this->menu=array(
	array('label'=>'Create OrgGroup', 'url'=>array('create')),
	array('label'=>'Manage OrgGroup', 'url'=>array('admin')),
);
?>

<h1>Org Groups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
