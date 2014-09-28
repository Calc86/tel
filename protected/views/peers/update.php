<?php
/* @var $this PeersController */
/* @var $model Peers */

$this->breadcrumbs=array(
	'Peers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Peers', 'url'=>array('admin')),
);
?>

<h1>Update Peer <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>