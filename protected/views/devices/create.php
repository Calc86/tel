<?php
/* @var $this DevicesController */
/* @var $model Devices */

$this->breadcrumbs=array(
	'Оборудование'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'Список', 'url'=>array('index')),
    Devices::$MENU_LIST,
	//array('label'=>'Manage Devices', 'url'=>array('admin')),
);
?>

<h1>Создание устройства</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>