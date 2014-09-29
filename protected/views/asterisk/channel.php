<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 29.09.14
 * Time: 14:32
 */

/*
 * @var $model
 */

$this->breadcrumbs=array(
    'channels'=>array('channels'),
    'channel'
);

?>

<?php
if($model == null){
    echo "no channel";
}
else
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'peer',
        'user',
        'format',
        'hold',
        'lastMessage',
        array(
            'name' => 'descr',
            'type' => 'raw',
            'value' => implode($model->descr, "<br>"),
        ),
    ),
)); ?>
