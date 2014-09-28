<?php
/* @var $this OrgListController */
/* @var $model OrgList */

$this->breadcrumbs=array(
	'Org Lists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrgList', 'url'=>array('index')),
	array('label'=>'Manage OrgList', 'url'=>array('admin')),
);
?>

<h1>Create OrgList</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>