<?php

class StatController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public $date_start;

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
			/*array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),*/
			/*array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),*/
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
		$model=new Stat;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Stat']))
		{
			$model->attributes=$_POST['Stat'];
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

		if(isset($_POST['Stat']))
		{
			$model->attributes=$_POST['Stat'];
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

	/**
	 * Lists all models.
	 */
    // @todo переписать передачу параметров в этой функии, слишком много переменных
	public function actionIndex($oid,$date_start=0,$date_end=0,$cause=1,$out=1,$in=0)
	{
        //$oid = Yii::app()->request->getQuery('oid');

        //отработка формы поиска
        if(isset($_POST['date'])){
            print_r($_POST);
            //exit();

            $this->redirect(
                array('index',
                    'oid'=>$oid,
                    'date_start'=>$_POST['date']['start'],
                    'date_end'=>$_POST['date']['end'],
                    'cause'=>isset($_POST['date']['cause']) ? 1 : 0,
                    'in'=>isset($_POST['date']['in']) ? 1 : 0,
                    'out'=>isset($_POST['date']['out']) ? 1 : 0,
                )
            );
        }

        if(!$date_start)
            $date_start = date('Y-m-01');

        if(!$date_end)
            $date_end = date('Y-m-t');


        // in statement for sql query
        $users = Users::model()->findAll('oid='.$oid);
        $in_u = array();
        $in_sip_u = array();
        foreach($users as $user){
            array_push($in_u,$user->intno);
            array_push($in_sip_u,"SIP/".$user->intno);
        }

        $criteria = new CDbCriteria();

        //общий запрос по организации
        $criteria->addInCondition('ch',$in_sip_u);
        $criteria->addInCondition('dstch',$in_sip_u,'OR');
        $criteria->addInCondition('dst',$in_u,'OR');
        $criteria->addInCondition('src',$in_u,'OR');

        //критерий по дате
        $criteria->addBetweenCondition('cd',$date_start." 00:00:00",$date_end." 23:59:59");
        //критерий по статусу звонка, интересуют только отвеченные
        if($cause) $criteria->addCondition("cause='ANSWERED'");
        //критерий по нарправлению звонка
        $direction = array('otr','itr','redir');
        //if($out) $direction = array(/*'',*/'otr',/*'itr',*/'redir','out');
        if($out) $direction = array_merge($direction,array('out'));
        if($in)  $direction = array_merge($direction,array('in'));
        $criteria->addInCondition('direction', $direction);

        /*
         * direction
            Изменить	Удалить     -
            Изменить	Удалить	in  +
            Изменить	Удалить	itr -
            Изменить	Удалить	otr +
            Изменить	Удалить	out +
            Изменить	Удалить	redir +
         * itr - 7 записей за всё время
         * '' - закончились в 2011-01-12 07:30:07
         */

		$dataProvider=new CActiveDataProvider('Stat',
            array(
                'criteria' => $criteria,
                'sort'=>array(
                    'defaultOrder'=>'cd DESC',
                )
            )
        );
        $dataProvider->pagination = false;

        $criteriaTotal = clone $criteria;
        $criteriaTotal->select = "SUM(cost) as cost,SUM(billsec) as billsec, SUM(duration) as duration";
        $sumProvider=new CActiveDataProvider('Stat',
            array(
                'criteria' => $criteriaTotal,
            )
        );

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
            'date_start'=>$date_start,
            'date_end'=>$date_end,
            'cause'=>$cause,
            'in'=>$in,
            'out'=>$out,
            'total'=>$sumProvider->data[0],
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Stat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stat']))
			$model->attributes=$_GET['Stat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Stat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Stat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Stat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
