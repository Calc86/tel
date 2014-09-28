<?php
/* @var $this OrgController */
/* @var $model Org */

$this->breadcrumbs=array(
	'Организации'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage Org', 'url'=>array('admin')),
);
?>

<h1>Create Организацию</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>