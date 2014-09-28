<?php
/* @var $this OrgListController */
/* @var $model OrgList */

$this->breadcrumbs=array(
	'Org Lists'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OrgList', 'url'=>array('index')),
	array('label'=>'Create OrgList', 'url'=>array('create')),
	array('label'=>'View OrgList', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OrgList', 'url'=>array('admin')),
);
?>

<h1>Update OrgList <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>