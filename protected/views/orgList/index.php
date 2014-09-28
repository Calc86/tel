<?php
/* @var $this OrgListController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Org Lists',
);

$this->menu=array(
	array('label'=>'Create OrgList', 'url'=>array('create')),
	array('label'=>'Manage OrgList', 'url'=>array('admin')),
    array('label'=>'Отправить', 'url'=>array('send')),
);
?>

<h1>Org Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
