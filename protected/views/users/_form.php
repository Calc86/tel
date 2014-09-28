<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
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

<?php
    if($model->isNewRecord){
        $model->intno = 10000 + $model->oid * 10;
        $model->secret = substr(md5(time() + rand()), 0, 5 + rand(0, 3));
    }
?>
	<div class="row">
		<?php echo $form->labelEx($model,'intno'); ?>
		<?php echo $form->textField($model,'intno',array('maxlength'=>20)); ?>
		<?php echo $form->error($model,'intno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'secret'); ?>
		<?php echo $form->textField($model,'secret',array('maxlength'=>20)); ?>
		<?php echo $form->error($model,'secret'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pid'); ?>
        <?php
        $list =
            CHtml::listData(
                Peers::model()->findAllByAttributes(array('oid'=>$model->oid)),
                'id','name'
            );
        $list[0] = 'Не выбран';

        echo $form->dropDownList($model,'pid',$list);
        ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->labelEx($model,'dtmfmode'); ?>
		<?php echo $form->textField($model,'dtmfmode',array('maxlength'=>50)); ?>
		<?php echo $form->error($model,'dtmfmode'); ?>
	</div>-->

    <div class="row">
        <?php echo $form->labelEx($model,'t38'); ?>
        <?php echo $form->checkBox($model,'t38'); ?>
        <?php echo $form->error($model,'t38'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'criv'); ?>
        <?php echo $form->checkBox($model,'criv'); ?>
        <?php echo $form->error($model,'criv'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'nat'); ?>
        <?php echo $form->checkBox($model,'nat'); ?>
        <?php echo $form->error($model,'nat'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'dtmf'); ?>
        <?php
        $list = array(
            '0' => 'auto',
            '1' => 'inband',
            '2' => 'rfc2833',
            '3' => 'info',
        );
        echo $form->dropDownList($model,'dtmf',$list);
        ?>
        <?php echo $form->error($model,'dtmf'); ?>
    </div>

	<!--<div class="row">
		<?php echo $form->labelEx($model,'flags'); ?>
		<?php echo $form->textField($model,'flags'); ?>
		<?php echo $form->error($model,'flags'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->labelEx($model,'call_limit'); ?>
		<?php echo $form->textField($model,'call_limit'); ?>
		<?php echo $form->error($model,'call_limit'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->