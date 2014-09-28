<?php
/* @var $this PriceController */
/* @var $data Price */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ptid')); ?>:</b>
	<?php echo CHtml::encode($data->ptid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prid')); ?>:</b>
	<?php echo CHtml::encode($data->prid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kod')); ?>:</b>
	<?php echo CHtml::encode($data->kod); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cost')); ?>:</b>
	<?php echo CHtml::encode($data->cost); ?>
	<br />


</div>