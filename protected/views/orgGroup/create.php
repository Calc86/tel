<?php
/* @var $this OrgGroupController */
/* @var $model OrgGroup */

$this->breadcrumbs=array(
	'Org Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrgGroup', 'url'=>array('index')),
	array('label'=>'Manage OrgGroup', 'url'=>array('admin')),
);
?>

<h1>Create OrgGroup</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>