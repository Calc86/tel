<?php
/* @var $this StatController */
/* @var $model Stat */

$this->breadcrumbs=array(
	'Stats'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Stat', 'url'=>array('index')),
	array('label'=>'Create Stat', 'url'=>array('create')),
	array('label'=>'View Stat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Stat', 'url'=>array('admin')),
);
?>

<h1>Update Stat <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>