<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 29.09.14
 * Time: 13:15
 */

class AsteriskController extends Controller {
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('channels','channel'),
                'users'=>array('@'),
            ),

            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionChannels(){
        $model = new AsteriskChannel();
        $channels = $model->findAll();
        //print_r($channels);

        $dataProvider = new CArrayDataProvider($channels);
        $dataProvider->pagination = false;
        $this->render('channels',array(
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ));
    }

    public function actionChannel($id){
        $model = new AsteriskChannel();
        $ch = $model->find($id);

        $this->render('channel',array(
            'model'=>$ch,
        ));
    }
} 