<?php
/* @var $this DialOptsController */
/* @var $model DialOpts */

$this->breadcrumbs=array(
	'Правила набора'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List DialOpts', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage DialOpts', 'url'=>array('admin')),
);
?>

<h1>Update правила набора <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>