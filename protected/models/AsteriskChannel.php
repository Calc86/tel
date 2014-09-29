<?php
/**
 * Created by PhpStorm.
 * User: calc
 * Date: 29.09.14
 * Time: 12:58
 */

/**
 * This is the model class for asterisk -n -rx "sip show channels"/"sip show channel #####".
 *
 * The followings are the available columns in table 'devices':
 * @property integer $id
 * @property integer $peer
 * @property integer $user
 * @property string $format
 * @property string $hold
 * @property string $lastMessage
 */
class AsteriskChannel extends CModel {
    /**
     * @var AstCLI
     */
    private static $ast = null;

    protected $primaryKey = 'id';

    public $id = '';
    public $peer;
    public $user;
    public $format;
    public $hold;
    public $lastMessage;
    public $descr = array();

    function __construct()
    {
        self::$ast = new AstCLI();
    }

    protected function init(){

    }

    public function loadDescr(){
        if($this->id != '')
            $this->descr = self::$ast->sip_show_channel($this->id);
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        array(
            "id",   //16
            "peer", //16
            "user", //16
            "format",   //16
            "hold", //8
            "lastMessage",  //other
        );
    }

    /**
     * @return array
     */
    public function findAll(){
        $return = array();
        foreach(self::$ast->channels as $channel){
            list($peer, $user, $id, $f1, $f2, $hold, $lm1, $lm2)
                = sscanf($channel, "%16s %16s %16s %s %s %8s %s %s");
            $ch = new AsteriskChannel();
            $ch->id = $id;
            $ch->peer = $peer;
            $ch->user = $user;
            $ch->format = $f1.' '.$f2;
            $ch->hold = $hold;
            $ch->lastMessage = $lm1.' '.$lm2;
            $ch->loadDescr();
            $return[] = $ch;
        }
        return $return;
    }

    /**
     * @param $id
     * @return $this AsteriskChannel
     */
    public function find($id){
        $channels = $this->findAll();
        foreach($channels as $ch){
            /** @var $ch AsteriskChannel */
            if($ch->id == $id) return $ch;
        }
        return null;
    }
}
