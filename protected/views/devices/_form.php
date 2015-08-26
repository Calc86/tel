<?php
/* @var $this DevicesController */
/* @var $model Devices */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'devices-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'oid'); ?>
        <?php
        $list =
            CHtml::listData(
                Org::model()->findAll(array('order' => 'name')),
                'id','name'
            );
        echo $form->dropDownList($model,'oid',$list);
        ?>
		<?php echo $form->error($model,'oid'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'dtype'); ?>
        <?php
        $list =
            CHtml::listData(
                Dtypes::model()->findAll(array('order' => 'name')),
                'id','name'
            );
        echo $form->dropDownList($model,'dtype',$list);
        ?>
        <?php echo $form->error($model,'dtype'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip'); ?>
		<?php echo $form->textField($model,'ip',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mac'); ?>
		<?php echo $form->textField($model,'mac',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'mac'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serial'); ?>
		<?php echo $form->textField($model,'serial',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'serial'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'soft_ver'); ?>
		<?php echo $form->textField($model,'soft_ver',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'soft_ver'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hard_ver'); ?>
		<?php echo $form->textField($model,'hard_ver',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'hard_ver'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user'); ?>
		<?php echo $form->textField($model,'user',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'user'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->