<?php
/* @var $this PtController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Провайдеры',
);

$this->menu=array(
	array('label'=>'Добавить', 'url'=>array('create')),
	//array('label'=>'Manage Pt', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Pts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
