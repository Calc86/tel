<?php

/**
 * This is the model class for table "peers".
 *
 * The followings are the available columns in table 'peers':
 * @property integer $id
 * @property integer $oid
 * @property string $name
 * @property string $tel
 * @property string $host
 * @property string $username
 * @property string $secret
 * @property integer $nid
 * @property integer $ban
 * @property integer $call_limit
 */
class Peers extends LogActiveRecord
{
    public $in_route;   //входящая маршрутизацияы смысл утерян, не трогать
    public $tel_id;
    public $users_sip;  //массив с пользователями

    /*public $context;    //c+name+id
    public $point;      //ip+name+id
    public $peer;   //имя пира в конфиг файле у нас это p+name+id*/

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'peers';
	}

    protected function getName($name=''){
        return parent::getName($this->num->no.":".$this->name);
    }

    public function init(){
        $this->in_route = 'ip'; //заглушка, смысл утерян со временем.... не помню что это
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oid, name, tel, host, username, secret, nid', 'required'),
            array('name, tel', 'unique'),
			array('oid, nid, ban, call_limit', 'numerical', 'integerOnly'=>true),
			array('name, tel, host, username, secret', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, oid, name, tel, host, username, secret, nid, ban, call_limit', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'num' => array(self::BELONGS_TO, 'NumPool', 'nid'),
            'org' => array(self::BELONGS_TO, 'Org', 'oid'),
            //у пира есть несколько конечных точек, но они ограничены организацией
            'users' => array(self::HAS_MANY, 'Users', array('pid'=>'id', 'oid'=>'oid'),
                /*'condition'=>'users.oid=oi1d'*/),
            'usersCount' => array(self::STAT, 'Users', 'pid',
                /*'condition'=>'status='.Comment::STATUS_APPROVED*/),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'oid' => 'Oid',
			'name' => 'Name',
			'tel' => 'Tel',
			'host' => 'Host',
			'username' => 'Username',
			'secret' => 'Secret',
			'nid' => 'Nid',
			'ban' => 'Ban',
			'call_limit' => 'Call Limit',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('oid',$this->oid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('host',$this->host,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('secret',$this->secret,true);
		$criteria->compare('nid',$this->nid);
		$criteria->compare('ban',$this->ban);
		$criteria->compare('call_limit',$this->call_limit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Peers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function afterFind()
    {
        parent::afterFind();
        // @todo прийти к одному знаменателю в модели

        if($this->num->id){
            $this->tel_id = $this->num->no.'_'.$this->id;
        }
        else{
            $this->tel_id = $this->tel.'_'.$this->id;
        }

        //users_sip is array
        //exten => 1007460,n,Macro(dialip,SIP/10064&SIP/10074600,t,${EXTEN},c6639982_13)
        $users = $this->users;
        foreach($users as $user){
            $this->users_sip[] = 'SIP/'.$user->intno;
        }
    }

    private function do_template($name,$params,$values){
        $tmp = file_get_contents(Yii::app()->basePath.'/../tmp/'.$name);
        return str_replace($params,$values,$tmp);
    }

    //sip conf
    public function sip_register(){
        $tmp = "register => {username}:{secret}@{host}/{username}\n";
        $register = '';

        $params = array(
            '{username}',
            '{secret}',
            '{host}',
        );

        $values = array(
            $this->username,
            $this->secret,
            $this->host,
        );
        //формируем
        $register = str_replace($params,$values,$tmp);

        return $register;
    }

    public function sip_peer(){
        $params = array(
            '{pid}',
            '{tel_id}',
            '{oid}',
            '{org}',
            '{banned}',
            '{host}',
            '{username}',
            '{secret}',
            '{call_limit}',
            '{params}',
        );

        $values = array(
            $this->id,
            $this->tel_id,
            $this->org->id,
            $this->org->name,
            $this->ban ? '(!)' : '',
            $this->host,
            $this->username,
            $this->secret,
            $this->call_limit,
            '',
        );

        return $this->do_template('sip.peer',$params,$values);
    }

    //ext conf
    public function ext_outbound(){

    }

    public function ext_inbound(){

    }

    public function sip_inbound(){

        $params = array(
            '{username}',
            '{org}',
            '{tel_id}',
            '{ibr}',
            '{intno}',
        );

        $values = array(
            $this->username,
            $this->org->name,
            $this->tel_id,
            $this->in_route,
            implode('&',$this->users_sip),      //звоним на все внутренние номера
        );
        //формируем
        return $this->do_template('ext.inbound',$params,$values);
    }

    public function sip_outbound(){
        $params = array(
            '{oid}',
            '{org}',
            '{pid}',
            '{tel_id}',
            '{dial_opts}',  //full string
        );

        $values = array(
            $this->org->id,
            $this->org->name,
            $this->id,
            $this->tel_id,
            $this->options(),    // @todo вынести эту логику из dialOpts
        );

        return $this->do_template('ext.out_context',$params,$values);
    }

    private function options(){
        $options = '';

        $opts = $this->org->opts;
        foreach($opts as $opt){
            $options.= $opt->options($this->tel_id);
        }

        return $options;
    }
}
