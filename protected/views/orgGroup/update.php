<?php
/* @var $this OrgGroupController */
/* @var $model OrgGroup */

$this->breadcrumbs=array(
	'Org Groups'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OrgGroup', 'url'=>array('index')),
	array('label'=>'Create OrgGroup', 'url'=>array('create')),
	array('label'=>'View OrgGroup', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OrgGroup', 'url'=>array('admin')),
);
?>

<h1>Update OrgGroup <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>