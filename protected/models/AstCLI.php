<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */

//@todo Перенести AstCLI в компоненты и сделать наследование от CComponent
class AstCLI extends CFormModel
{
    public $db;
    public $reg;
    public $channels;

    public function init(){
        $this->init_db();
        $this->init_registry();
        $this->channels = $this->sip_show_channels();
    }

    /**
     * @param $id string name to cache
     * @param $cmd string asterisk -n -rx "command"
     * @param $to int timeout ti expire, 0 - no cache
     * @return array
     */
    protected function execute($id, $cmd, $to=0){
        $dump = Yii::app()->cache->get($id);
        if($dump === false)
        {
            $dump = `$cmd`;
            if($to != 0) Yii::app()->cache->set($id, $dump, $to);
        }

        $lines = explode("\n",$dump);
        //вынуть мусор
        array_shift($lines);    //спереди/заголовки
        array_pop($lines);      //сзади
        array_pop($lines);
        return $lines;
    }

    private function sip_show_channels(){
        return $this->execute("sip show channels", 'sudo asterisk -n -rx "sip show channels"', 9);
    }

    public function sip_show_channel($id){
        return $this->execute("sip show channel ".$id, 'sudo asterisk -n -rx "sip show channel '.$id.'"', 60);
    }

    private function init_db(){
        $id = "database_show";
        $dump = Yii::app()->cache->get($id);
        if($dump===false)
        {
            $dump = `sudo asterisk -rx "database show"`;
            Yii::app()->cache->set($id, $dump,5);
        }

        $a = str_replace("[0;37m","\n",$dump);      //заменить некую гадость (перевод курсора) на новую строку
        $lines = explode("\n",$a);
        //вынуть мусор
        array_shift($lines);    //спереди
        array_pop($lines);      //сзади
        array_pop($lines);
        foreach($lines as $line){
            $line = explode(": ",$line);
            $key = $line[0];
            $val = explode(":",$line[1]);
            $this->db[trim($key)] = $val;
        }
    }

    private function init_registry(){
        //asterisk -rx "sip show registry" | awk '{ print $3";"$5";"$7" "$8" "$9" "$10 }' | sort
        //$dump = `sudo asterisk -rx "sip show registry" | awk '{ print $3";"$5";"$7" "$8" "$9" "$10 }' | sort`;
        $dump = `sudo asterisk -rx "sip show registry" | awk '{ print $3";"$5";"$7" "$8" "$9" "$10 }'`;
        //$dump = `sudo asterisk -rx "sip show peers"`;
        //var_dump($dump);

        $id = "sip_show_registry";
        $dump = Yii::app()->cache->get($id);
        if($dump===false)
        {
            $dump = `sudo asterisk -rx "sip show registry" | awk '{ print $3";"$5";"$7" "$8" "$9" "$10 }'`;
            Yii::app()->cache->set($id, $dump,5);
        }

        $lines = explode("\n",$dump);
        array_shift($lines);
        array_pop($lines);
        array_pop($lines);
        array_pop($lines);

        foreach($lines as $line){
            $line = explode(";",$line);
            $key = $line[0];
            $val = /*array(*/$line[1]/*,$line[2])*/;    // stat date
            $this->reg[trim($key)] = $val;
        }
    }

    //public function get_user_ip($username){
    public function get_user_ip($username){
        $ip = isset($this->db["/SIP/Registry/$username"]) ?  $this->db["/SIP/Registry/$username"][0] : 'Unknown';
        return $ip;
    }

    public function render_user_ip($data,$row){
        $star = '';
        if(count($data->org->ip)) $star = '*';
        $id = $data->org->id;
        $ip = $this->get_user_ip($data->intno);
        if($ip == "Unknown") $ip = "Not registered";
        return $star.$ip;
    }

    public function get_peer_route($username){
        return isset($this->db["/route/$username"]) ?  trim($this->db["/route/$username"][0]) : 0;
    }

    public function get_peer_route_num($username,$no){
        if($no){
            return isset($this->db["/route/$username/$no"]) ? $no.':'.$this->db["/route/$username/".$no][0] : $no.': empty';
        }
        else return 'no route';
    }

    public function render_peer_route_num($data,$row){
        return $this->get_peer_route_num($data->username,$this->get_peer_route($data->username));
    }

    //юзер нейм длиной 13 символов, если линий несколкьо - могут повтоярться.../наложиться
    public function get_peer_registry($username){
        $username = substr($username,0,12);

        return isset($this->reg[$username]) ?  $this->reg[$username] : 'Unknown';
    }

    public function render_peer_registry($data,$row){
        return $this->get_peer_registry($data->username);
    }
}
