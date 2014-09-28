<?php
/* @var $this NumPoolController */
/* @var $model NumPool */

$this->breadcrumbs=array(
	'Номера'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage NumPool', 'url'=>array('admin')),
);
?>

<h1>Update Номера <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>