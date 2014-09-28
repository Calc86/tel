<?php
/* @var $this DtypesController */
/* @var $model Dtypes */

$this->breadcrumbs=array(
	'Типы оборудования'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Dtypes', 'url'=>array('admin')),
);
?>

<h1>Update Тип оборудования <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>