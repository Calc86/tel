<?php
/* @var $this StatController */
/* @var $data Stat */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cdrid')); ?>:</b>
	<?php echo CHtml::encode($data->cdrid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('direction')); ?>:</b>
	<?php echo CHtml::encode($data->direction); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cd')); ?>:</b>
	<?php echo CHtml::encode($data->cd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ut')); ?>:</b>
	<?php echo CHtml::encode($data->ut); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('src')); ?>:</b>
	<?php echo CHtml::encode($data->src); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dst')); ?>:</b>
	<?php echo CHtml::encode($data->dst); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('kod')); ?>:</b>
	<?php echo CHtml::encode($data->kod); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ch')); ?>:</b>
	<?php echo CHtml::encode($data->ch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dstch')); ?>:</b>
	<?php echo CHtml::encode($data->dstch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('billsec')); ?>:</b>
	<?php echo CHtml::encode($data->billsec); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cause')); ?>:</b>
	<?php echo CHtml::encode($data->cause); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uniqueid')); ?>:</b>
	<?php echo CHtml::encode($data->uniqueid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minute')); ?>:</b>
	<?php echo CHtml::encode($data->minute); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cost')); ?>:</b>
	<?php echo CHtml::encode($data->cost); ?>
	<br />

	*/ ?>

</div>