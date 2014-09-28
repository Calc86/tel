<?php
/* @var $this DialOptsController */
/* @var $model DialOpts */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dial-opts-form',
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
		<?php echo $form->labelEx($model,'pid1'); ?>
        <?php
        $list =
            CHtml::listData(
                Peers::model()->findAllByAttributes(array('oid'=>$model->oid)),
                'id','name'
            );
        $list = array_merge(array('0'=>'Не выбран'),$list);
        echo $form->dropDownList($model,'pid1',$list);
        ?>
		<?php echo $form->error($model,'pid1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pid2'); ?>
        <?php
        $list =
            CHtml::listData(
                Peers::model()->findAllByAttributes(array('oid'=>$model->oid)),
                'id','name'
            );
        $list = array_merge(array('0'=>'Не выбран'),$list);
        echo $form->dropDownList($model,'pid2',$list);
        ?>
		<?php echo $form->error($model,'pid2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rule'); ?>
		<?php echo $form->textField($model,'rule',array('maxlength'=>255)); ?>
		<?php echo $form->error($model,'rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prior'); ?>
		<?php echo $form->textField($model,'prior'); ?>
		<?php echo $form->error($model,'prior'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'internal'); ?>
        <?php
        $list =
            CHtml::listData(
                Users::model()->findAllByAttributes(array('oid'=>$model->oid)),
                'id','intno'
            );
        //$list = array_merge(array('0'=>'Не выбран'),$list);
        $list = array('0'=>'Не выбран') + $list;
        echo $form->dropDownList($model,'internal',$list);
        ?>
		<?php echo $form->error($model,'internal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'context'); ?>
        <?php
        $list = array("out1"=>"out1","dialc"=>"dialc");
        echo $form->dropDownList($model,'context',$list);
        ?>
		<?php echo $form->error($model,'context'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->