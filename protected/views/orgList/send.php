<?php
/* @var $this OrgListController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Org Lists',
);

$this->menu=array(
	array('label'=>'Create OrgList', 'url'=>array('create')),
	array('label'=>'Manage OrgList', 'url'=>array('admin')),
);
?>

<h1>Список кому будет оправлено письмо</h1>

<a href="<?=$this->createUrl("send",array('do'=>1))?>">Отправить</a>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'org-list-grid',
    'dataProvider'=>$dataProvider,
    //'filter'=>$model,
    'columns'=>array(
        'id',
        'name',
        'address',
        'email',
        'send',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
)); ?>