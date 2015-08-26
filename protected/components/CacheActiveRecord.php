<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 29.09.14
 * Time: 20:15
 */

class CacheActiveRecord extends CActiveRecord {

    protected function getCaching(){
        return false;
    }

    protected function beforeFind()
    {
        if($this->getCaching()) $this->cache(30, new CTagCacheDependency(get_class($this)));
        parent::beforeFind();
    }

    protected function afterSave()
    {
        if($this->getCaching()) Yii::app()->cache->set(get_class($this), microtime(true), 0);
        parent::afterSave();
    }
} 