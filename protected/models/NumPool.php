<?php

/**
 * This is the model class for table "num_pool".
 *
 * The followings are the available columns in table 'num_pool':
 * @property integer $id
 * @property integer $prid
 * @property string $no
 * @property integer $lines
 * @property string $dt
 * @property string $descr
 * @property double $cost
 * @property double $cost2
 */
class NumPool extends LogActiveRecord
{
    protected function getCaching(){
        return false;
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'num_pool';
	}

    protected function getName($name=''){
        parent::getName($this->no);
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prid, no, lines', 'required'),
			array('prid, lines', 'numerical', 'integerOnly'=>true),
			array('cost, cost2', 'numerical'),
			array('no', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prid, no, lines, dt, descr, cost, cost2', 'safe', 'on'=>'search'),
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
            'pt' => array(self::BELONGS_TO, 'Pt', 'prid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prid' => 'Провайдер',
			'no' => 'Номер',
			'lines' => 'Количество линий',
			'dt' => 'Dt',
			'descr' => 'Descr',
			'cost' => 'Покупаем',
			'cost2' => 'Продаем',
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
		$criteria->compare('prid',$this->prid);
		$criteria->compare('no',$this->no,true);
		$criteria->compare('lines',$this->lines);
		$criteria->compare('dt',$this->dt,true);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('cost2',$this->cost2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NumPool the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function beforeSave(){
        if ($this->isNewRecord)
            $this->dt = new CDbExpression('NOW()');
    }

    public static $time = 0;
}
