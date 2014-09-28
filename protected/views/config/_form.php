<?php

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<!--<p class="note">Fields with <span class="required">*</span> are required.</p>-->

    <input type="Hidden" name="Config[save]" value="save">

	<div class="row buttons">
		<?php echo CHtml::submitButton('Save and backup'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->