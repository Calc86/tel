<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 29.09.14
 * Time: 14:32
 */

/*
 * @var $dataProvider
 */

/*$this->breadcrumbs=array(
    'Config'=>array('channels'),
    'Ext'=>array('ext'),
    'Sip'=>array('sip'),
    'oExt'=>array('oext'),
    'oSip'=>array('osip')
);*/

?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("asterisk/channel", array("id"=>$data->id)))',
        ),
        /*array(
            'name'=>'oid',
            'type'=>'raw',
            'value'=>'$data->org->name',
        ),
        array(
            'name'=>'intno',
            'type'=>'raw',
            'value'=>'CHtml::link($data->intno,Yii::app()->createUrl("users/update",array("id"=>$data->primaryKey)))',
        ),
        'intno',
        'secret',
        array(
            'name'=>'pid',
            'type'=>'raw',
            'value'=>'$data->peer->tel_id',
        ),
        'dtmfmode',
        'flags',
        'call_limit',
        't38',
        'criv',
        'nat',
        'dtmf',*/
        'id',
        'peer',
        'user',
        'format',
        'hold',
        'lastMessage',
        array(
            'name'=>'direction',
            'type'=>'raw',
            'value'=>'isset($data->descr[1]) ? $data->descr[1] : ""',
        ),
    ),
));

?>
