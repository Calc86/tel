<?php

/**
 * This is the model class for table "price".
 *
 * The followings are the available columns in table 'price':
 * @property integer $id
 * @property integer $ptid
 * @property integer $prid
 * @property string $name
 * @property string $kod
 * @property double $cost
 */
class Price extends LogActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'price';
	}

    protected function getName($name=''){
        return parent::getName($this->pt->name.":".$this->kod);
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ptid, prid, name, kod, cost', 'required'),
			array('ptid, prid', 'numerical', 'integerOnly'=>true),
			array('cost', 'numerical'),
			array('name, kod', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ptid, prid, name, kod, cost', 'safe', 'on'=>'search'),
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
            'pt' => array(self::BELONGS_TO, 'Pt', 'ptid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ptid' => 'Ptid',
			'prid' => 'Prid',
			'name' => 'Name',
			'kod' => 'Kod',
			'cost' => 'Cost',
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

        $criteria= new CDbCriteria();
        $criteria->select = array('id','ptid','prid','t.name', "GROUP_CONCAT(kod SEPARATOR ', ') as kod", 'cost');
        $criteria->group = 't.name,cost';
        $criteria->order = 'prid,t.name';
        $criteria->condition = 'ptid=0';

        $criteria->with = array('pt');

		//$criteria->compare('id',$this->id);
		//$criteria->compare('ptid',$this->ptid);
		//$criteria->compare('prid',$this->prid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('kod',$this->kod,true);
		//$criteria->compare('cost',$this->cost);

        Yii::beginProfile('search');
        $dataProvider = new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
        Yii::endProfile('search');

        $dataProvider->setPagination(false);

		return $dataProvider;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Price the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function zone_name($pt,$zone){
        $zn[0] = array(
            0 => "0, Местные/Входящие",
            1 => "1, Специальное предложение Россия",
            2 => "2, Россия сотовые",
            3 => "",
            4 => "4, Россия зона 1 М,О, до 100 км,",
            5 => "5, Россия зона 1 М,О, свыше 100 км,",
            6 => "6, Россия зона 2",
            7 => "7, Россия зона 3",
            8 => "8, Россия зона 4",
            9 => "9, Россия зона 5",
            10 => "10, Россия зона 6",
            11 => "11, СНГ и страны Балтии",
            12 => "12, Европа",
            13 => "13, Азия",
            14 => "14, Африка",
            15 => "15, Австралия и Океания",
            16 => "16, Северная Америка",
            17 => "17, Южная Америка"
        );

        $zn[2] = array(
            0 => "0, Местные/Входящие",
            1 => "1, Россия, зона 1",
            2 => "2, Россия, зона 2",
            3 => "3, Россия, зона 3",
            4 => "4, Россия, зона 4",
            5 => "5, Россия, зона 5",
            6 => "6, Россия, зона 6",
            7 => "7, Россия, федеральные номера",
            8 => "8, Ближнее зарубежье",
            9 => "9, Остальные страны А — З",
            10 => "10, Остальные страны И — О",
            11 => "11, Остальные страны П — Я",
            12 => "12, Спутниковая связь Инмарсат"
        );
        return $zn[$pt][$zone];
    }
}
