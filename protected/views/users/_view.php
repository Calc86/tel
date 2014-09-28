<?php
/* @var $this UsersController */
/* @var $data Users */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('oid')); ?>:</b>
	<?php echo CHtml::encode($data->oid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('intno')); ?>:</b>
	<?php echo CHtml::encode($data->intno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('secret')); ?>:</b>
	<?php echo CHtml::encode($data->secret); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::encode($data->pid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dtmfmode')); ?>:</b>
	<?php echo CHtml::encode($data->dtmfmode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('flags')); ?>:</b>
	<?php echo CHtml::encode($data->flags); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('call_limit')); ?>:</b>
	<?php echo CHtml::encode($data->call_limit); ?>
	<br />

	*/ ?>

</div>