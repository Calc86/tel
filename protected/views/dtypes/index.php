<?php
/* @var $this DtypesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Типы оборудования',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage Dtypes', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Типы оборудования</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
