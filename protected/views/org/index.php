<?php
/* @var $this OrgController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Организации',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage Org', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Организации</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    //'dataProvider'=>$dataProvider,
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("org/view",array("id"=>$data->primaryKey)))',
            'filter'=>"&nbsp",
        ),
        //'name',
        array(
            'name'=>'name',
            'type'=>'raw',
            'value'=>'CHtml::link($data->name,Yii::app()->createUrl("users/index",array("id"=>$data->primaryKey)))',
        ),
        'login',
        'fullname',

        array(
            'filter' => CHtml::activeDropDownList($model,'group',array("-1" => "Все") + CHtml::listData(OrgGroup::model()->findAll(),'id', 'name'),array('unselectValue'=>'-1',/*'multiple'=>'multiple',*/'class'=>'select2')),
            //'title'=>'intno',
            'header'=>'group_name',
            'type'=>'raw',
            'value'=>'$data->group_name',
        ),
        'money',
        /*array(
            //'title'=>'intno',
            'header'=>'peer',
            'type'=>'raw',
            'value'=>'$data->peer_list',
        ),*/
        array(
            //'title'=>'intno',
            'header'=>'intno',
            'type'=>'raw',
            'value'=>'$data->user_list',
        ),
        array(
            //'title'=>'intno',
            'type'=>'raw',
            'value'=>'$data->peer_list',
        ),
    ),
)); ?>
