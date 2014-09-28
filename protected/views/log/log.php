<?php
/* @var $this LogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Logs',
);

$this->menu=array(
	/*array('label'=>'Create Log', 'url'=>array('create')),
	array('label'=>'Manage Log', 'url'=>array('admin')),*/
);
?>

<h1>Inbound for <?=$org->name?></h1>


<pre style="width: 900px; height:700px; border: 1px solid black; overflow-x: auto;">
    CMD: <?=$cmd?>

    ====

    <?=$log?>
</pre>
