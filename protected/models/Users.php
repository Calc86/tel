<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property integer $oid
 * @property string $intno
 * @property string $secret
 * @property integer $pid
 * @property string $dtmfmode
 * @property integer $flags
 * @property integer $call_limit
 * @property Org $org
 * @property Peers $peer
 */
class Users extends LogActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

    const FLAG_T38 = 0x01;
    const FLAG_T38_POS = 0;
    public $t38;

    //can reinvite
    const FLAG_CRIV = 0x02;
    const FLAG_CRIV_POS = 1;
    public $criv;

    const FLAG_NAT = 0x04;
    const FLAG_NAT_POS = 2;
    public $nat;

    const FLAG_DTMF = 0x18;
    const FLAG_DTMF_POS = 3;
    public $dtmf;
    const FLAG_DTMF_AUTO = 0;
    const FLAG_DTMF_INBAND = 1;
    const FLAG_DTMF_RFC2833 = 2;
    const FLAG_DTMF_INFO = 3;

    public function init(){
        //default for flag
        $this->t38 = 1;
        $this->criv = 1;
        $this->nat = 1;
        $this->dtmf = 0;
    }

    public function setCriv($v){
        $this->criv = $v;
    }

    public function pack_flags(){
        $this->flags = 0;
        $this->flags+= $this->t38 << Users::FLAG_T38_POS;
        $this->flags+= $this->criv << Users::FLAG_CRIV_POS;
        $this->flags+= $this->nat << Users::FLAG_NAT_POS;
        $this->flags+= $this->dtmf << Users::FLAG_DTMF_POS;
    }

    public function unpack_flags(){
        //результат записывается в поля
        // @todo Избавиться бы от этого идиотизма, кто меня дернул такой херней страдать....
        $this->t38 =  ($this->flags & Users::FLAG_T38)  >> Users::FLAG_T38_POS;
        $this->criv = ($this->flags & Users::FLAG_CRIV) >> Users::FLAG_CRIV_POS;
        $this->nat =  ($this->flags & Users::FLAG_NAT)  >> Users::FLAG_NAT_POS;
        $this->dtmf = ($this->flags & Users::FLAG_DTMF) >> Users::FLAG_DTMF_POS;
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oid, pid, intno, secret', 'required'),
            array('intno', 'unique'),
			array('oid, pid, flags, call_limit', 'numerical', 'integerOnly'=>true),
			array('intno, secret', 'length', 'max'=>20),
			array('dtmfmode', 'length', 'max'=>50),
            array('t38, criv, nat, dtmf','safe'),   //иначе они не воспримутся как атрибуты!!!
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, oid, intno, secret, pid, dtmfmode, flags, call_limit', 'safe', 'on'=>'search'),
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
            'org' => array(self::BELONGS_TO, 'Org', 'oid'),
            'peer' => array(self::BELONGS_TO, 'Peers', 'pid'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'oid' => 'Организация',
			'intno' => 'Внутренний номер',
			'secret' => 'Пароль',
			'pid' => 'Внешняя линия',
			'dtmfmode' => 'Dtmfmode',
			'flags' => 'Flags',
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
		$criteria->compare('intno',$this->intno,true);
		$criteria->compare('secret',$this->secret,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('dtmfmode',$this->dtmfmode,true);
		$criteria->compare('flags',$this->flags);
		$criteria->compare('call_limit',$this->call_limit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function afterFind(){
        parent::afterFind();
        $this->unpack_flags();
    }

    protected function beforeSave(){
        $this->pack_flags();
        return parent::beforeSave();
    }

    private function do_template($name,$params,$values){
        $tmp = file_get_contents(Yii::app()->basePath.'/../tmp/'.$name);
        return str_replace($params,$values,$tmp);
    }

    public function sip_user(){
        $params = array(
            '{org}',
            '{intno}',
            '{tel_id}',
            '{t38}',
            '{secret}',
            '{nat}',
            '{call_limit}',
            '{dtmf}',
            '{crinv}',
            '{access}',
            '{params}',
        );

        $dtmf = array('auto','inband','rfc2833','info');

        $access = '';

        if(count($this->org->ip)){

             //deny=0.0.0.0/0.0.0.0
             //permit=216.207.245.47/255.255.255.255
            $access.= "deny=0.0.0.0/0.0.0.0\n";
            foreach($this->org->ip as $permit){
                $access.= "permit=$permit->ip/$permit->mask\n";
            }
        }

        $values = array(
            $this->org->name,
            $this->intno,
            $this->peer->tel_id,
            $this->t38 ? 'yes' : 'no',
            $this->secret,
            $this->nat ? 'yes' : 'no',
            $this->call_limit,
            $dtmf[$this->dtmf],
            $this->criv ? 'yes' : 'no',
            $access,
            '',
        );

        return $this->do_template('sip.user',$params,$values);
    }

    protected function getName($name=''){
        return parent::getName($this->org->name.":".$this->intno);
    }
}
