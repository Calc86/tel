<?php
/* @var $this PeersController */
/* @var $model Peers */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'peers-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'tel'); ?>
        <?php echo $form->textField($model,'tel',array('maxlength'=>255)); ?>
        <?php echo $form->error($model,'tel'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'host'); ?>
        <?php echo $form->textField($model,'host',array('maxlength'=>255)); ?>
        <?php echo $form->error($model,'host'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username',array('maxlength'=>255)); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'secret'); ?>
        <?php echo $form->textField($model,'secret',array('maxlength'=>255)); ?>
        <?php echo $form->error($model,'secret'); ?>
    </div>

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
		<?php echo $form->labelEx($model,'nid'); ?>
        <?php
        $list =
            CHtml::listData(
                NumPool::model()->findAll(array('order' => 'no')),
                'id','no'
            );
        echo $form->dropDownList($model,'nid',$list);
        ?>
		<?php echo $form->error($model,'nid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ban'); ?>
        <?php echo $form->checkBox($model, 'ban'); ?>
		<?php echo $form->error($model,'ban'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'call_limit'); ?>
		<?php echo $form->textField($model,'call_limit'); ?>
		<?php echo $form->error($model,'call_limit'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'buy'); ?>
        <?php echo $form->textField($model,'buy'); ?>
        <?php echo $form->error($model,'buy'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'sell'); ?>
        <?php echo $form->textField($model,'sell'); ?>
        <?php echo $form->error($model,'sell'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->