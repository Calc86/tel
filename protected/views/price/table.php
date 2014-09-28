<?php
/* @var $this PriceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Prices',
);

$this->menu=array(
    array('label'=>'Добавить', 'url'=>array('create')),
    //array('label'=>'Manage Price', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Prices</h1>

<?php

Yii::beginProfile('table');
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'enableSorting'=>false,
    'enablePagination'=>false,
    'itemsCssClass'=>'items price',
    'columns'=>array(
        /*array(
            'name'=>'id',
            'value'=>'print_r($data)',
        ),*/
        array(
            'name'=>'ptid',
            'value'=>'$data->pt->name',
            'filter'=>'&nbsp;',
        ),
        array(
            'name'=>'prid',
            'value'=>'$data->zone_name($data->ptid,$data->prid)',
            'filter'=>'&nbsp;',
        ),
        'name',
        'kod',
        array(
            'name'=>'cost',
            'value'=>'number_format($data->cost,2)',
            'cssClassExpression'=>'"cost"',
            'filter'=>'&nbsp;',
        )
    ),
));

Yii::endProfile('table');

?>