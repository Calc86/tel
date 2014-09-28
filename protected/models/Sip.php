<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Sip extends CFormModel
{
    public $sip;

    private $tmp_path;
    private $tmp_sip_conf;
    private $tmp_user;
    private $tmp_peer;

    //переменные для шаблона
    public $users;    //out_context
    public $peers;
    public $register;

    private function fill_templates(){
        $this->tmp_path = Yii::app()->basePath.'/../tmp';
        //основной файл extensions.conf
        $this->tmp_sip_conf = file_get_contents($this->tmp_path."/sip.tmp.conf");
        /*//контекст для исходящих звонков
        $this->tmp_user = file_get_contents($this->tmp_path."/sip.user");
        //общий контекст для входящих
        $this->tmp_peer = file_get_contents($this->tmp_path."/sip.peer");*/
    }

    public function init(){
        $this->sip = '';

        $this->fill_templates();

        $this->register = '';
        $organisations = Org::model()->findAll();
        //регистрация пиров
        foreach($organisations as $org){
            if(!$org->id) continue; //нулевую организацию не роутим
            // @todo if($org->ban) continue; // организация забанена
            $this->register.=$this->register($org);
        }
        //пиры
        foreach($organisations as $org){
            if(!$org->id) continue; //нулевую организацию не роутим
            // @todo if($org->ban) continue; // организация забанена
            $this->peers.=$this->peers($org);
        }
        //пользователи
        foreach($organisations as $org){
            if(!$org->id) continue; //нулевую организацию не роутим
            // @todo if($org->ban) continue; // организация забанена
            $this->users.=$this->users($org);
        }


        //замена в шаблоне
        $params = array(
            '{register}',
            '{peers}',
            '{users}',
        );

        $values = array(
            $this->register,
            $this->peers,
            $this->users,
        );

        $this->sip = str_replace($params,$values,$this->tmp_sip_conf);
    }

    private function register($org){
        $register = '';

        $peers = $org->peers;
        foreach($peers as $peer){
            //есди у пира нет конечных точек, то его вообще не маршрутизируем
            if(!$peer->usersCount) continue;
            //if($peer->ban) continue;    //если забанен - продолжаем
            $register.= $peer->ban ? ';'.$peer->sip_register() : $peer->sip_register();
        }

        return $register;
    }

    private function peers($org){
        $return = '';

        $peers = $org->peers;
        foreach($peers as $peer){
            //if(!$peer->usersCount) continue; //есди у пира нет конечных точек, то его вообще не маршрутизируем
            //if($peer->ban) continue;    //если забанен - продолжаем
            $return.= $peer->sip_peer();
        }

        return $return;
    }


    private function users($org){
        $return = '';


        $users = $org->users;
        foreach($users as $user){
            if(!$user->pid) continue; //не принадлежим пиру, значит мы не нужны
            if($user->peer->ban) continue;  //с забанеными пирами не выводим

            $return.= $user->sip_user()."\n";
        }

        return $return;
    }

    public function backup($date){
        $sip = "sip.conf";
        copy("/etc/asterisk/$sip","/etc/asterisk/bak/$date/$sip");

    }

    public function save(){
        $f = fopen("/etc/asterisk/sip.conf","w");
        if($f) {
            fwrite($f,$this->sip);
            fclose($f);
        }
    }

    public function reload(){
        passthru('sudo asterisk -rx "sip reload"');
    }
}
