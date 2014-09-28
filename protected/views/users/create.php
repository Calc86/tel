<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Линии'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index', 'id' => $model->oid)),
	//array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Create Линию</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>