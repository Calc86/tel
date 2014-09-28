<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $oid */

$this->breadcrumbs=array(
	'Линии'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Create Линию</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>