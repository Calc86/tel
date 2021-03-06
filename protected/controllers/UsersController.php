<?php
// @todo Проработать бреадкумбсы у видов, что бы ссылки были с oid ($model->oid)
class UsersController extends Controller
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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
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
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        $model->oid = Yii::app()->request->getQuery('oid',0);
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
		$this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
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
	public function actionIndex()
	{
        $oid = Yii::app()->request->getQuery('id');
        $org = Org::model()->findByPk($oid);

        $criteria = new CDbCriteria();
        $criteria->addCondition("t.oid=$oid");
        $criteria->with = array('org');
		$dataProvider=new CActiveDataProvider('Users',
            array(
                'criteria'=>$criteria,
                /*'criteria'=>array(
                    'condition' => 'oid='.$oid,    //SQL where
                    //'with'=>'user', //model relations
                    //'order'=>'name',
                ),*/
                'sort'=>array(
                    'defaultOrder'=>'intno ASC',
                )
            )
        );
        $dataProvider->pagination = false;

        $dataProviderOpts=new CActiveDataProvider('DialOpts',
            array(
                'criteria'=>array(
                    'condition' => 'oid='.$oid,    //SQL where
                    //'with'=>'user', //model relations
                    //'order'=>'name',
                ),
                'sort'=>array(
                    'defaultOrder'=>'rule ASC',
                )
            )
        );
        $dataProviderOpts->pagination = false;

        $dataProviderPeers=new CActiveDataProvider('Peers',
            array(
                'criteria'=>array(
                    'condition' => 'oid='.$oid,    //SQL where
                    //'with'=>'user', //model relations
                    //'order'=>'name',
                ),
                'sort'=>array(
                    'defaultOrder'=>'nid ASC',
                )
            )
        );
        $dataProviderPeers->pagination = false;

        $dataProviderDev=new CActiveDataProvider('Devices',
            array(
                'criteria'=>array(
                    'condition' => 'oid='.$oid,    //SQL where
                    //'with'=>'user', //model relations
                    //'order'=>'name',
                ),
                'sort'=>array(
                    'defaultOrder'=>'ip ASC',
                )
            )
        );
        $dataProviderDev->pagination = false;

        $dataProviderIP=new CActiveDataProvider('OrgIp',
            array(
                'criteria'=>array(
                    'condition' => 'org_id='.$oid,    //SQL where
                    //'with'=>'user', //model relations
                    //'order'=>'name',
                ),
                'sort'=>array(
                    'defaultOrder'=>'ip ASC',
                )
            )
        );
        $dataProviderIP->pagination = false;

        $ast = new AstCLI();

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
            'dataProviderOpts'=>$dataProviderOpts,
            'dataProviderPeers'=>$dataProviderPeers,
            'dataProviderDev'=>$dataProviderDev,
            'dataProviderIP'=>$dataProviderIP,
            'ast' =>$ast,
            'oid' => $oid,
            'org' => $org,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
