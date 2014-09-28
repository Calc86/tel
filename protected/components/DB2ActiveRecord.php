<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 02.04.14
 * Time: 12:40
 */

class DB2ActiveRecord {
    private static $db2 = null;

    protected static function getDB2Connection()
    {
        if (self::$db2 !== null)
            return self::$db2;
        else
        {
            self::$db2 = Yii::app()->db2;
            if (self::$db2 instanceof CDbConnection)
            {
                self::$db2->setActive(true);
                return self::$db2;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "db2" CDbConnection application component.'));
        }
    }

    public function getDbConnection()
    {
        return self::getDb2Connection();
    }
} 