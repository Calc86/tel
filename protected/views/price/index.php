<?php
/* @var $this PriceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Prices',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage Price', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Prices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
