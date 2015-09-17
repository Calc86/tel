<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 26.08.15
 * Time: 21:14
 */

class MyYii extends Yii {
    public static $session = null;

    public static function openSessionOrIgnore(){
        if(self::$session == null){
            self::$session = new CHttpSession();
            self::$session->open();
        }
        return self::$session;
    }
} 