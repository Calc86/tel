<?php

class OrgListController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'send', 'sended'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('mxtel'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new OrgList;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OrgList']))
		{
			$model->attributes=$_POST['OrgList'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OrgList']))
		{
			$model->attributes=$_POST['OrgList'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

    public function actionSended(){
        $this->render('sended');
    }

    public function mailSend($oid, $to,$from,$subject,$message){
        $mail=Yii::app()->Smtpmail;
        /** @var $mail PHPMailer */
        $mail->CharSet = 'UTF-8';
        $mail->ContentType = 'text/pain';

        $mail->SetFrom($from, 'МХ Телеком');
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to, "");
        $mail->AddAttachment('./test.odt',"Коммерческое предложение.odt");
        //if(!$mail->Send()) {
        $ojl = new OrgJobList();
        $ojl->org_id = $oid;
        if(0){
            $ojl->sended = 0;
            $ojl->status = $mail->ErrorInfo;
            //echo "Mailer Error: " . $mail->ErrorInfo;
        }else {
            $ojl->sended = 1;
            //echo "Message sent!";
        }
    }

    public function actionSend($do = 0){
        $criteria = new CDbCriteria();
        $criteria->addCondition('send=1');
        $dataProvider=new CActiveDataProvider('OrgList',array(
            'criteria' => $criteria,
            'pagination' => false,
        ));

        if($do){
            $job = new JobList();
            $job->date = time();
            $job->count = $dataProvider->getItemCount();

            $this->mailSend($oid, 'calc@list.ru', 'calc@list.ru',"Коммерческое предложение МХ","Тестим что то, русский текст");
            exit();
            $this->redirect($this->createUrl('sended'));
        }

        $this->render('send',array(
            'dataProvider' => $dataProvider,
        ));
    }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('OrgList');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new OrgList('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['OrgList']))
			$model->attributes=$_GET['OrgList'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return OrgList the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=OrgList::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param OrgList $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='org-list-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
