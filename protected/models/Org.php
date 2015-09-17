<?php

/**
 * This is the model class for table "org".
 *
 * The followings are the available columns in table 'org':
 * @property integer $id
 * @property string $name
 * @property string $login
 * @property string $passwd
 * @property string $hash
 * @property double $money
 * @property double $group
 * @property string $fullname
 * @property Peers $peers
 * @property Users $users
 * @property DialOpts $opts
 * @property OrgIp $org_id
 * @property string $user_list
 */
class Org extends LogActiveRecord
{
    public $user_list = '';
    public $peer_list = '';
    public $group_name = '';
    public $group_filter;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'org';
	}

    protected  function afterFind(){
        parent::afterFind();

        $u = array();
        foreach($this->users as $user){
            $u[] = $user->intno;
        }

        $this->user_list = implode(', ',$u);

        $p = array();
        foreach($this->peers as $peer){
            /** @var $peer Peers */
            $p[] = $peer->name;
        }

        $this->peer_list = implode(', ', $p);

        $this->group_name = OrgGroup::model()->findByPk($this->group)->name;

    }

    protected function getName($name=''){
        return parent::getName($this->name);
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, login, passwd, group', 'required'),
            array('name, login', 'unique'),
			//array('money', 'numerical'),
			array('name, login, passwd, fullname', 'length', 'min'=>4, 'max'=>255),
            array('login', 'match', 'pattern'=>'/^[A-z][\w]+$/'),
			//array('hash', 'length', 'max'=>50),
			// The following rule is used by search().
			//array('id, name, login, passwd, hash, money, fullname', 'safe', 'on'=>'search'),
            array('name, login, fullname, group', 'safe', 'on'=>'search')
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
            'peers' => array(self::HAS_MANY, 'Peers', 'oid',
                //'condition'=>'peers.status='.Comment::STATUS_APPROVED,
            ),
            'users' => array(self::HAS_MANY, 'Users', 'oid'),
            'opts'  => array(self::HAS_MANY, 'DialOpts', 'oid'),
            'ip'=>array(self::HAS_MANY, 'OrgIp', 'org_id'),
            //'group'=>array(self::HAS_ONE, 'OrgGroup', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название организации',
			'login' => 'Логин',
			'passwd' => 'Пароль',
			'hash' => 'Hash',
			'money' => 'Money',
            'group' => 'Группа',
			'fullname' => 'Полное название организации',
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

		$criteria=new CDbCriteria;

		//$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('login',$this->login,true);
        if($this->group>=0)$criteria->compare('`group`',$this->group,false);
		//$criteria->compare('passwd',$this->passwd,true);
		//$criteria->compare('hash',$this->hash,true);
		//$criteria->compare('money',$this->money);
		$criteria->compare('fullname',$this->fullname,true);

        $dataProvider =new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

        $dataProvider->setPagination(false);

		return $dataProvider;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Org the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /*protected function beforeSave(){
        if(parent::beforeSave()){
            if($this->fullname==='') $this->fullname=$this->name;
            return true;
        }
        else
            return false;
    }*/
}

