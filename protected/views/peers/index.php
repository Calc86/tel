<?php
/* @var $this PeersController */
/* @var $dataProvider CActiveDataProvider */
/* ast */

$this->breadcrumbs=array(
	'Peers',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Manage Peers', 'url'=>array('admin')),
    array('label'=>'История', 'url'=>array('log/index','controller'=>Yii::app()->getController()->id))
);
?>

<h1>Peers</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("peers/view",array("id"=>$data->primaryKey)))',
        ),
        array(
            'name'=>'name',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "<s>".$data->name."</s>" : $data->name;',
        ),
        array(
            'name'=>'peer',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "<s>".$data->tel_id."</s>" : $data->tel_id;',
        ),
        array(
            'name'=>'tel',
            'type'=>'raw',
            'value'=>'$data->nid==0 ? "<b>".$data->tel."</b>" : $data->tel;',
        ),
        'username',
        'host',
        //'secret',
        //'nid',
        array(
            'name'=>'nid',
            'type'=>'raw',
            'value'=>'$data->num->no',
        ),
        array(
            'name'=>'oid',
            'type'=>'raw',
            'value'=>'$data->org->name',
        ),
        array(
            'name'=>'ban',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "Yes" : "No";',
        ),
        //'call_limit'
        array(
            'name'=>'route',
            /*'type'=> 'raw',*/
            'value' => array($ast,'render_peer_route_num'),
            'cssClassExpression'=>'"route"',
        ),
        array(
            'name'=>'registry',
            /*'type'=> 'raw',*/
            'value' => array($ast,'render_peer_registry'),
            'cssClassExpression'=>'"registry"',
        ),
    ),
));
//$a = substr(s,st,l)''
?>
