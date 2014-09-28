<?php
/* @var $this ExtController
   @var $model
 */

$this->breadcrumbs=array(
    'Config'=>array('save'),
    'Ext'=>array('ext'),
    'Sip'=>array('sip'),
    'oExt'=>array('oext'),
    'oSip'=>array('osip')
);

?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
    echo new AsteriskHttpConfig($model->ext);
?>

