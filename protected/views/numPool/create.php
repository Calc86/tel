<?php
/* @var $this NumPoolController */
/* @var $model NumPool */

$this->breadcrumbs=array(
	'Номера'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage NumPool', 'url'=>array('admin')),
);
?>

<h1>Create Номер</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>