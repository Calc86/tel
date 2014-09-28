<?php
/* @var $this PtController */
/* @var $model Pt */

$this->breadcrumbs=array(
	'Провайдеры'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Pt', 'url'=>array('admin')),
);
?>

<h1>Update Провайдера <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>