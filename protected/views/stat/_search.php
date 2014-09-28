<?php
/* @var $this StatController */
/* @var $model Stat */
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
		<?php echo $form->label($model,'cdrid'); ?>
		<?php echo $form->textField($model,'cdrid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'direction'); ?>
		<?php echo $form->textField($model,'direction',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cd'); ?>
		<?php echo $form->textField($model,'cd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ut'); ?>
		<?php echo $form->textField($model,'ut'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src'); ?>
		<?php echo $form->textField($model,'src',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dst'); ?>
		<?php echo $form->textField($model,'dst',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kod'); ?>
		<?php echo $form->textField($model,'kod',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ch'); ?>
		<?php echo $form->textField($model,'ch',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dstch'); ?>
		<?php echo $form->textField($model,'dstch',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'billsec'); ?>
		<?php echo $form->textField($model,'billsec'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cause'); ?>
		<?php echo $form->textField($model,'cause',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'uniqueid'); ?>
		<?php echo $form->textField($model,'uniqueid',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minute'); ?>
		<?php echo $form->textField($model,'minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cost'); ?>
		<?php echo $form->textField($model,'cost'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->