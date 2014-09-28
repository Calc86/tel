<?php

/**
 * This is the model class for table "devices".
 *
 * The followings are the available columns in table 'devices':
 * @property integer $id
 * @property integer $dtype
 * @property integer $oid
 * @property string $ip
 * @property string $mac
 * @property string $serial
 * @property string $soft_ver
 * @property string $hard_ver
 * @property string $user
 * @property string $admin
 */
class Devices extends LogActiveRecord
{

    //не хорошая идея, нужно подумать
    public static $MENU_LIST = array('label'=>'Список', 'url'=>array('index'));
    public static $MENU_CREATE = array('label'=>'Создать', 'url'=>array('create'));

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'devices';
	}

    protected function getName($name=''){
        return parent::getName($this->ip);
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dtype, oid, ip', 'required'),
			array('dtype, oid', 'numerical', 'integerOnly'=>true),
			array('ip, mac, serial, soft_ver, hard_ver, user, admin', 'length', 'max'=>50),
            array('ip', 'match', 'pattern'=>'/^\b(?:\d{1,3}\.){3}\d{1,3}\b/'),  //http://www.regular-expressions.info/examples.html
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dtype, oid, ip, mac, serial, soft_ver, hard_ver, user, admin', 'safe', 'on'=>'search'),
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
            'type' => array(self::BELONGS_TO, 'Dtypes', 'dtype'),
            'org' => array(self::BELONGS_TO, 'Org', 'oid'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dtype' => 'Тип',
			'oid' => 'Организация',
			'ip' => 'Ip',
			'mac' => 'Mac',
			'serial' => 'Serial',
			'soft_ver' => 'Soft Ver',
			'hard_ver' => 'Hard Ver',
			'user' => 'User',
			'admin' => 'Admin',
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
		$criteria->compare('dtype',$this->dtype);
		$criteria->compare('oid',$this->oid);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('mac',$this->mac,true);
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('soft_ver',$this->soft_ver,true);
		$criteria->compare('hard_ver',$this->hard_ver,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('admin',$this->admin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Devices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
