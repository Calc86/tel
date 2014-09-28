<?php
/* @var $this DialOptsController */
/* @var $model DialOpts */

$this->breadcrumbs=array(
	'Правила набора'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage DialOpts', 'url'=>array('admin')),
);
?>

<h1>Создать правило набора</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>