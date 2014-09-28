<?php
/* @var $this StatController */
/* @var $model Stat */
/* @var $form CActiveForm */
?>

<div class="form search">

    <?php
    echo CHtml::beginForm();

    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'name'=>'date[start]',
        'value'=>$date_start,
        // additional javascript options for the date picker plugin
        'options'=>array(
            /*'showAnim'=>'fold',*/
            'dateFormat'=>'yy-mm-dd',
            'defaultDate'=>$date_start,
        ),
    ));

    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'name'=>'date[end]',
        'value'=>$date_end,
        // additional javascript options for the date picker plugin
        'options'=>array(
            /*'showAnim'=>'fold',*/
            'dateFormat'=>'yy-mm-dd',
            'defaultDate'=>$date_end,
        ),
    ));
    ?>

    <div class="checkbox">
    <?php
    echo CHtml::label('Показать только отвеченные','date_cause');
    echo CHtml::checkBox('date[cause]',$cause);
    ?>
    </div>
    <div class="checkbox">
    <?php
    echo CHtml::label('Показать входящие','date_in');
    echo CHtml::checkBox('date[in]',$in);
    ?>
    </div>
    <div class="checkbox">
    <?php
    echo CHtml::label('Показать исходящие','date_out');
    echo CHtml::checkBox('date[out]',$out);
    ?>
    </div>
    <?php
    echo CHtml::submitButton('Search');

    echo CHtml::endForm();

    ?>

</div><!-- form -->