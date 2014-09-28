<?php
/* @var $this DialOptsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Правила набора',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage DialOpts', 'url'=>array('admin')),
);
?>

<h1>Dial Opts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
