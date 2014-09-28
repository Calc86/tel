<?php

class ConfigController extends Controller
{
	public function actionSip()
	{
        $model = new Sip();
        $this->render('sip',array(
            'model'=>$model,
        ));
	}

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('save','sip','ext','oext','osip'),
                'users'=>array('@'),
            ),

            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionExt()
    {
        $model = new Ext();
        $this->render('ext',array(
            'model'=>$model,
        ));
    }

    protected  function Log($text){
        $vars = array(
            'user' => Yii::app()->user->id,
            'dt' => time(),
            'controller' => Yii::app()->controller->id,
            'action' => Yii::app()->controller->action->id,
            'name'=> 'config',
            'text' => $text,
        );

        $model=new Log;

        $model->attributes=$vars;
        $model->save(false);
    }

    public function actionSave()
    {
        if(isset($_POST['Config']))
        {
            $ext=new Ext;
            $sip=new Sip;

            $date = date("Y_m_d_H_i_s");

            mkdir("/etc/asterisk/bak/$date");

            $sip->backup($date);
            $sip->save();
            $ext->backup($date);
            $ext->save();

            $this->Log("Бекап, сохранение и обновление конфигурации сервера");

            $sip->reload();
            $ext->reload();

            $this->redirect(array('Save'));
        }

        $this->render('save',array(
        ));
    }

    private function backup()
    {

    }

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}