<?php
/* @var $this DialOptsController */
/* @var $data DialOpts */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('oid')); ?>:</b>
	<?php echo CHtml::encode($data->oid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid1')); ?>:</b>
	<?php echo CHtml::encode($data->pid1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid2')); ?>:</b>
	<?php echo CHtml::encode($data->pid2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rule')); ?>:</b>
	<?php echo CHtml::encode($data->rule); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prior')); ?>:</b>
	<?php echo CHtml::encode($data->prior); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('internal')); ?>:</b>
	<?php echo CHtml::encode($data->internal); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('context')); ?>:</b>
	<?php echo CHtml::encode($data->context); ?>
	<br />

	*/ ?>

</div>