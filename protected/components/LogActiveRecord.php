<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 25.10.13
 * Time: 21:58
 */

//class LogActiveRecord extends CActiveRecord {
class LogActiveRecord extends CacheActiveRecord {

    protected function getName($name=''){
        return $name."(".$this->getOldPrimaryKey().")";
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()){

            $text = '';
            if($this->isNewRecord){
                $text = "Создание записи";
            }
            else{

                $model = $this->model()->findByPk($this->getOldPrimaryKey());
                //echo '<pre>';
                //print_r($this->attributes);
                //print_r($model->attributes);
                $diff = array_diff_assoc($this->attributes,$model->attributes);
                //attributeLabels()
                $labels = $this->attributeLabels();

                $text = "Изменения: ";
                foreach($diff as $k=>$v){
                    $text.= "{$labels[$k]}({$k}) c '{$model->attributes[$k]}' на '{$v}'; ";
                }
                //exit();
            }

            /*print_r(Yii::app()->controller->id);
            print_r(Yii::app()->controller->action->id);
            print_r(time());
            print_r(Yii::app()->user->id);
            print_r($text);*/
            //save to log differences
            $this->Log($text);

            return true;
        }
        else
            return false;
    }

    protected function beforeDelete(){
        if(parent::beforeDelete()){
            $text = "Удаление модели с данными: ";
            $labels = $this->attributeLabels();
            foreach($this->attributes as $k=>$v){
                $text.= "{$labels[$k]}({$k}) - '{$v}'; ";
            }
            $this->Log($text);
            return true;
        }
        else
            return false;

    }

    public function Log($text){
        $vars = array(
            'user' => Yii::app()->user->id,
            'dt' => time(),
            'controller' => Yii::app()->controller->id,
            'action' => Yii::app()->controller->action->id,
            'name'=> $this->getName(),
            'text' => $text,
        );

        $model=new Log;


        $model->attributes=$vars;

        //print_r($model);
        //echo 1; exit();
        $ret = $model->save(false);
    }

} 