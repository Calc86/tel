<?php
/* @var $this DevicesController */
/* @var $model Devices */

$this->breadcrumbs=array(
	'Оборудование'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
    Devices::$MENU_LIST,
	Devices::$MENU_CREATE,
    //Devices::$MENU_VIEW,
	//array('label'=>'Manage Devices', 'url'=>array('admin')),
);
?>

<h1>Update Оборудования<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>