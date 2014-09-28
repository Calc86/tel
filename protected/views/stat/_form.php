<?php
/* @var $this StatController */
/* @var $model Stat */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stat-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cdrid'); ?>
		<?php echo $form->textField($model,'cdrid'); ?>
		<?php echo $form->error($model,'cdrid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'direction'); ?>
		<?php echo $form->textField($model,'direction',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'direction'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cd'); ?>
		<?php echo $form->textField($model,'cd'); ?>
		<?php echo $form->error($model,'cd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ut'); ?>
		<?php echo $form->textField($model,'ut'); ?>
		<?php echo $form->error($model,'ut'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src'); ?>
		<?php echo $form->textField($model,'src',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'src'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dst'); ?>
		<?php echo $form->textField($model,'dst',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'dst'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kod'); ?>
		<?php echo $form->textField($model,'kod',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'kod'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ch'); ?>
		<?php echo $form->textField($model,'ch',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dstch'); ?>
		<?php echo $form->textField($model,'dstch',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'dstch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
		<?php echo $form->error($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'billsec'); ?>
		<?php echo $form->textField($model,'billsec'); ?>
		<?php echo $form->error($model,'billsec'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cause'); ?>
		<?php echo $form->textField($model,'cause',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'cause'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'uniqueid'); ?>
		<?php echo $form->textField($model,'uniqueid',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'uniqueid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'minute'); ?>
		<?php echo $form->textField($model,'minute'); ?>
		<?php echo $form->error($model,'minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost'); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->