<?php
/* @var $this PriceController */
/* @var $model Price */

$this->breadcrumbs=array(
	'Prices'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('table')),
	//array('label'=>'Manage Price', 'url'=>array('admin')),
);
?>

<h1>Create Price</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>