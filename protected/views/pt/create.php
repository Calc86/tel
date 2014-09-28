<?php
/* @var $this PtController */
/* @var $model Pt */

$this->breadcrumbs=array(
	'Провайдеры'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage Pt', 'url'=>array('admin')),
);
?>

<h1>Create Pt</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>