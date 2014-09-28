<?php
/* @var $this StatController */
/* @var $model CActiveDataProvider */

$this->breadcrumbs=array(
	'Stats',
);

$this->menu=array(
	array('label'=>'Create Stat', 'url'=>array('create')),
	array('label'=>'Manage Stat', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Stats</h1>

<?php $this->renderPartial('_date', array('date_start'=>$date_start,'date_end'=>$date_end,'cause'=>$cause,'in'=>$in,'out'=>$out)); ?>
<?php

?>
<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'itemsCssClass'=>'items stat',
    'columns'=>array(
        //'id',
        //'cdrid',
        'direction',
        'cd',
        //'ut',
        'src',
        'dst',
        'kod',
        'ch',
        'dstch',
        array(
            'name'=>'duration',
            'value'=>'$data->to_time($data->duration)',
            'cssClassExpression'=>'"dutarion"',
            'footer' => Stat::to_time($total->duration),
        ),
        array(
            'name'=>'billsec',
            'value'=>'$data->to_time($data->billsec)',
            'cssClassExpression'=>'"billsec"',
            'footer' => Stat::to_time($total->billsec),
        ),
        array(
            'name'=>'cause',
            'value'=>'$data->cause',
            'cssClassExpression'=>'strtolower(str_replace(" ", "", $data->cause))',
        ),
        //'cause',
        //'uniqueid',
        array(
            'name'=>'minute',
            'value'=>'$data->minute',
            'cssClassExpression'=>'"minute"',
        ),
        array(
            'name'=>'cost',
            'value'=>'$data->cost',
            'cssClassExpression'=>'"cost"',
        ),
        array(
            'name'=>'cost',
            'value'=>'$data->to_money($data->cost)',
            'cssClassExpression'=>'"cost"',
            'footer' => Stat::to_money($total->cost),
        ),
        //$uid,$dstch,$src,$dst
        array(
            'name'=>'call',
            'type'=>'raw',
            //скачать можно только исходящие звонки
            'value'=>'$data->direction=="out" ? CHtml::link("скачать",$data->call_link($data->uniqueid,$data->dstch,$data->src,$data->dst)) : ""'
        ),
    ),
));

?>