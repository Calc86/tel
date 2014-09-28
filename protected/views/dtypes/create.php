<?php
/* @var $this DtypesController */
/* @var $model Dtypes */

$this->breadcrumbs=array(
	'Типы оборудования'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage Dtypes', 'url'=>array('admin')),
);
?>

<h1>Create тип оборудования</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>