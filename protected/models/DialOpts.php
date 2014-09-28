<?php

/**
 * This is the model class for table "dial_opts".
 *
 * The followings are the available columns in table 'dial_opts':
 * @property integer $id
 * @property integer $oid
 * @property integer $pid1
 * @property integer $pid2
 * @property string $rule
 * @property integer $prior
 * @property integer $internal
 * @property string $context
 */
class DialOpts extends LogActiveRecord
{
    public $options;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dial_opts';
	}

    protected function getName($name=''){
        return parent::getName($this->org->name);
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oid, pid1, pid2, rule, prior, internal, context', 'required'),
			array('oid, pid1, pid2, prior, internal', 'numerical', 'integerOnly'=>true),
			array('rule, context', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, oid, pid1, pid2, rule, prior, internal, context', 'safe', 'on'=>'search'),
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
            'peer1' => array(self::BELONGS_TO, 'Peers', 'pid1'),
            'peer2' => array(self::BELONGS_TO, 'Peers', 'pid2'),
            'user' => array(self::BELONGS_TO, 'Users', 'internal'),
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
			'pid1' => 'Pid1',
			'pid2' => 'Pid2',
			'rule' => 'Правило набора',
			'prior' => 'Приоритет',
			'internal' => 'Внутренний номер',
			'context' => 'Context',
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
		$criteria->compare('pid1',$this->pid1);
		$criteria->compare('pid2',$this->pid2);
		$criteria->compare('rule',$this->rule,true);
		$criteria->compare('prior',$this->prior);
		$criteria->compare('internal',$this->internal);
		$criteria->compare('context',$this->context,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DialOpts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function afterFind(){
        parent::afterFind();

        $this->options = $this->options();
    }

    public function options($tel_id='{tel_id}'){
        /*
         * exten => _9[1-79]X.,1,Noop(degunino-external-ip9677581_5)
            exten => _9[1-79]X.,n,Macro(dialc,${EXTEN:1},ip9677581_5,${TRUNK_OPTS})
            exten => _9[1-79]X.,n,Busy

            exten => _849[59]X.,1,Noop(degunino-external-ip9677581_5)
            exten => _849[59]X.,n,Macro(dialc,${EXTEN},ip9677581_5,${TRUNK_OPTS})
            exten => _849[59]X.,n,Busy

            exten => _8X.,1,Noop(degunino-external-ip9677581_5)
            exten => _8X.,n,Macro(out1,${EXTEN},ip9677581_5,${TRUNK_OPTS})
            exten => _8X.,n,Busy

            exten => _98X.,1,Noop(degunino-external-ip9677581_5)
            exten => _98X.,n,Macro(out1,${EXTEN:1},ip9677581_5,${TRUNK_OPTS})
            exten => _98X.,n,Busy

            exten => _9XXXXXX,1,Noop(degunino-external-ip9677581_5)
            exten => _9XXXXXX,n,Macro(dialc,${EXTEN},ip9677581_5,${TRUNK_OPTS})
            exten => _9XXXXXX,n,Busy
         */

        $ext_rule = $this->rule;
        $ext_var = '${EXTEN}';
        $rr = explode("|", $ext_rule);
        if(count($rr)>1){   //имеем | в правиле _9|8
            $ext_rule = str_replace("|", "", $ext_rule);
            $e_o = strlen($rr[0])-1;   //смещение в EXTEN
            $ext_var = '${EXTEN:'.$e_o.'}';
        }


        $sip = '[Need sip!!!]';
        $options = "\n";
        if($this->internal){
            //правила для внутреннего набора
            $sip = $this->user->intno;
            $options.= "exten => $ext_rule,1,Noop(".$this->org->name."-internal-$sip)\n";
            $options.= "exten => $ext_rule,n(startdial),Dial(SIP/$sip,100,tT)\n";
        }
        else{
            //внешняя маршрутизация
            $sip = 'ip'.$tel_id;   //
            $options.= "exten => $ext_rule,1,Noop(".$this->org->name."-external-$sip)\n";
            $options.= "exten => $ext_rule,n,Macro($this->context,$ext_var,$sip,\${TRUNK_OPTS})\n";
        }

        $options.= "exten => $ext_rule,n,Busy\n";

        return $options;
    }
}
