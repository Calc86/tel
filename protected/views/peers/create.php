<?php
/* @var $this PeersController */
/* @var $model Peers */

$this->breadcrumbs=array(
	'Peers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Manage Peers', 'url'=>array('admin')),
);
?>

<h1>Create Peer</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>