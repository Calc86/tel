<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Ext extends CFormModel
{
    public $ext;
    private $tmp_path;
    private $tmp_ext_conf;
    private $tmp_in_context;
    private $tmp_out_context;
    private $tmp_inbound;

    //переменные для шаблона
    public $outbound;    //out_context
    public $inbound;
    public $in_context;

    private function fill_templates(){
        $this->tmp_path = Yii::app()->basePath.'/../tmp';
        //основной файл extensions.conf
        $this->tmp_ext_conf = file_get_contents($this->tmp_path."/ext.tmp.conf");
        //уже не используется
        $this->tmp_in_context = file_get_contents($this->tmp_path."/ext.in_context");
        //контекст для исходящих звонков
        $this->tmp_out_context = file_get_contents($this->tmp_path."/ext.out_context");
        //общий контекст для входящих
        $this->tmp_inbound = file_get_contents($this->tmp_path."/ext.inbound");
    }

    public function init(){
        $this->ext = '';

        $this->fill_templates();

        $this->inbound = '';
        $organisations = Org::model()->findAll();
        //входящая маршрутизация
        foreach($organisations as $org){
            if(!$org->id) continue; //нулевую организацию не роутим
            // @todo if($org->ban) continue; // организация забанена
            $this->inbound.=$this->inbound($org);
        }
        //исходящая маршрутизация
        foreach($organisations as $org){
            if(!$org->id) continue; //нулевую организацию не роутим
            // @todo if($org->ban) continue; // организация забанена
            $this->outbound.=$this->outbound($org);
        }

        //замена в шаблоне
        $params = array(
            '{out_context}',
            '{inbound}',
            '{in_context}', //не используется, вся логика ушла в inbound
        );

        $values = array(
            $this->outbound,
            $this->inbound,
            '', //не используется
        );

        $this->ext = str_replace($params,$values,$this->tmp_ext_conf);
    }

    //получить outbound для конкретной организации
    private function outbound($org){
        $outbound = '';

        $peers = $org->peers;
        foreach($peers as $peer){
            //есди у пира нет конечных точек, то его вообще не маршрутизируем
            if(!$peer->usersCount) continue;
            if($peer->ban) continue;    //если забанен - продолжаем
            //формируем
            $outbound.= $peer->sip_outbound();
        }

        return $outbound;
    }

    //получить inbound Для конкретной организации
    private function inbound($org){
        $inbound = '';

        $peers = $org->peers;
        foreach($peers as $peer){
            if(!$peer->usersCount) continue; //есди у пира нет конечных точек, то его вообще не маршрутизируем
            if($peer->ban) continue;    //если забанен - продолжаем
            //формируем
            $inbound.= $peer->sip_inbound();
        }

        return $inbound;
    }

    public function backup($date){
        $ext = "extensions.conf";
        copy("/etc/asterisk/$ext","/etc/asterisk/bak/$date/$ext");
    }

    public function save(){
        $f = fopen("/etc/asterisk/extensions.conf","w");
        if($f) {
            fwrite($f,$this->ext);
            fclose($f);
        }
    }

    public function reload(){
        passthru('sudo asterisk -rx "dialplan reload"');
    }
}
