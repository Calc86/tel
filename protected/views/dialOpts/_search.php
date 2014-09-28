<?php
/* @var $this DialOptsController */
/* @var $model DialOpts */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'oid'); ?>
		<?php echo $form->textField($model,'oid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pid1'); ?>
		<?php echo $form->textField($model,'pid1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pid2'); ?>
		<?php echo $form->textField($model,'pid2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rule'); ?>
		<?php echo $form->textField($model,'rule',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prior'); ?>
		<?php echo $form->textField($model,'prior'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'internal'); ?>
		<?php echo $form->textField($model,'internal'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'context'); ?>
		<?php echo $form->textField($model,'context',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->