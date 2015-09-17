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
    'rowCssClassExpression' => '
        ( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ) .
        ( $data->ban ? " ban" : null )
    ',
    'columns'=>array(
        array(  //ID
            'name'=>'id',
            'type'=>'raw',
            'value'=>'CHtml::link($data->id,Yii::app()->createUrl("peers/view",array("id"=>$data->primaryKey)))',
            'cssClassExpression' => '"id"',
        ),
        array(
            'name'=>'name',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "<s>".$data->name."</s>" : $data->name;',
            'cssClassExpression' => '"name"',
        ),
        array(
            'name'=>'peer',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "<s>".$data->tel_id."</s>" : $data->tel_id;',
            'cssClassExpression' => '"peer"',
        ),
        array(
            'name'=>'tel',
            'type'=>'raw',
            'value'=>'$data->nid==0 ? "<b>".$data->tel."</b>" : $data->tel;',
            'cssClassExpression' => '"tel"',
        ),
        'username',
        'host',
        //'secret',
        //'nid',
        array(
            'name'=>'nid',
            'type'=>'raw',
            'value'=>'$data->num->no',
            'cssClassExpression' => '"nid"',
        ),
        array(
            'name'=>'oid',
            'type'=>'raw',
            'value'=>'$data->org->name',
            'cssClassExpression' => '"oid" . ($data->org->group==3 ? " ban" : null)',
        ),
        array(
            'name'=>'buy',
            'type'=>'raw',
            'value'=>'$data->buy',
            'cssClassExpression' => '"buy" . ($data->org->group==3 ? " ban" : null)',
        ),
        array(
            'name'=>'sell',
            'type'=>'raw',
            'value'=>'$data->sell',
            'cssClassExpression' => '"sell" . ($data->org->group==3 ? " ban" : null)',
        ),
        array(
            'name'=>'ban',
            'type'=>'raw',
            'value'=>'$data->ban==1 ? "Yes" : "No";',
            'cssClassExpression' => '"ban"',
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
