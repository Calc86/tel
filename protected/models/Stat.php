<?php

/**
 * This is the model class for table "stat".
 *
 * The followings are the available columns in table 'stat':
 * @property integer $id
 * @property integer $cdrid
 * @property string $direction
 * @property string $cd
 * @property string $ut
 * @property string $src
 * @property string $dst
 * @property string $kod
 * @property string $ch
 * @property string $dstch
 * @property integer $duration
 * @property integer $billsec
 * @property string $cause
 * @property string $uniqueid
 * @property double $minute
 * @property double $cost
 */
class Stat extends LogActiveRecord
{
    public $start_date;
    public $end_date;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cdrid, direction, cd, ut, src, dst, kod, ch, dstch, duration, billsec, cause, uniqueid, minute, cost', 'required'),
			array('cdrid, duration, billsec', 'numerical', 'integerOnly'=>true),
			array('minute, cost', 'numerical'),
			array('direction, cause', 'length', 'max'=>20),
			array('src, dst, kod, ch, dstch', 'length', 'max'=>255),
			array('uniqueid', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('direction, cd, ut, src, dst, kod, ch, dstch, duration, billsec, cause, uniqueid', 'safe', 'on'=>'search'),
            array('start_date, end_date','safe','on'=>'search')
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cdrid' => 'Cdrid',
			'direction' => 'i/o',
			'cd' => 'Дата звонка',
			'ut' => 'Ut',
			'src' => 'Src',
			'dst' => 'Dst',
			'kod' => 'Код',
			'ch' => 'Ch',
			'dstch' => 'Dstch',
			'duration' => 'Общее время',
			'billsec' => 'Время разговора',
			'cause' => 'Cause',
			'uniqueid' => 'Uniqueid',
			'minute' => 'Минута руб.',
			'cost' => 'Цена',
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

		//$criteria->compare('id',$this->id);
		//$criteria->compare('cdrid',$this->cdrid);
		$criteria->compare('direction',$this->direction,true);
		$criteria->compare('cd',$this->cd,true);
		$criteria->compare('ut',$this->ut,true);
		$criteria->compare('src',$this->src,true);
		$criteria->compare('dst',$this->dst,true);
		$criteria->compare('kod',$this->kod,true);
		$criteria->compare('ch',$this->ch,true);
		$criteria->compare('dstch',$this->dstch,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('billsec',$this->billsec);
		$criteria->compare('cause',$this->cause,true);
		$criteria->compare('uniqueid',$this->uniqueid,true);
		//$criteria->compare('minute',$this->minute);
		//$criteria->compare('cost',$this->cost);

        $dataProvider = new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

        $dataProvider->setPagination(false);

		return $dataProvider;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function to_time($sec){
        $time = '';

        $s = $sec % 60;
        (strlen($s) == 1) ? $s = "0".$s : $s = $s;
        $m = (int)($sec / 60 % 60);
        (strlen($m) == 1) ? $m = "0".$m : $m = $m;
        $h = (int)($sec / 3600);
        (strlen($h) == 1) ? $h = "0".$h : $h = $h;

        $time = "$h:$m:$s";

        return $time;
    }

    public static function to_money($val){
        return round($val,2);
    }

    public function call_link($uid,$dstch,$src,$dst){
        $srt = strtotime('2010-04-14 01:11:00');    //start record time
        $s12 = strtotime('2010-04-21 02:10:00');    //ввели запись по 12 часов
        $set = (mktime()-12*60*60)>$s12 ? mktime()-12*60*60 : $s12;    //start encoding time


        $aa = preg_split("/\./",$uid);
        $sct = $aa[0];  //start call time
        $dir = date("Y-m-d",$sct);  //папка для mp3
        $aa = preg_split("/\//",$dstch);
        $ch = $aa[1];
        $dst = $this->get_no($dst);
        $g = '-g';    //с 14 числа 04 месяца 2010 года
        //if(substr($ch,0,6)=='ip1903') $g='-g';
        $ext = $sct < $set ? 'mp3' : 'wav';
        $fname = "$src-$ch-$dst-$uid$g.$ext";
        $path = $sct < $set ? "/calls/$dir/$fname" : "/calls/$fname";
        return $path;
        //$link = text_show((($d=='out' || ($d=='redir')) && ($sct>=$srt)), tag_a($path,'Скачать','_blank'));
        //return $link;
    }

    private function get_no($no){
        if(strlen($no)>7)
            if($no[0]==9)
                return substr($no,1);
        return $no;
    }

    /**
     * @param $oid
     * @param int $date_start   старт, 0 - текущий месяц, -1 - прошлый месяц и т.д.
     * @param int $date_end     стоп, 0 - текущий месяц, -1 прошлый месяц и т.д.
     * @param int $cause        1 - только отвеченные
     * @param int $out          1 - исходящие
     * @param int $in           1 - входящие
     * @return CDbCriteria
     */
    public static function generateCriteria($oid,$date_start=0,$date_end=0,$cause=1,$out=1,$in=0){
        if(!$date_start)
            $date_start = date('Y-m-01');

        if(!$date_end)
            $date_end = date('Y-m-t');

        if($date_start < 0){
            $m = date("m") + $date_start;
            $date_start = date("Y-$m-01");
        }
        if($date_end < 0){
            $m = date("m") + $date_end;
            $date_end = date("Y-$m-t");
        }


        // in statement for sql query
        $users = Users::model()->findAll('oid='.$oid);
        $in_u = array();
        $in_sip_u = array();
        foreach($users as $user){
            array_push($in_u,$user->intno);
            array_push($in_sip_u,"SIP/".$user->intno);
        }

        $criteria = new CDbCriteria();

        //общий запрос по организации
        $criteria->addInCondition('ch',$in_sip_u);
        $criteria->addInCondition('dstch',$in_sip_u,'OR');
        $criteria->addInCondition('dst',$in_u,'OR');
        $criteria->addInCondition('src',$in_u,'OR');

        //критерий по дате
        $criteria->addBetweenCondition('cd',$date_start." 00:00:00",$date_end." 23:59:59");
        //критерий по статусу звонка, интересуют только отвеченные
        if($cause) $criteria->addCondition("cause='ANSWERED'");
        //критерий по нарправлению звонка
        $direction = array('otr','itr','redir');
        //if($out) $direction = array(/*'',*/'otr',/*'itr',*/'redir','out');
        if($out) $direction = array_merge($direction,array('out'));
        if($in)  $direction = array_merge($direction,array('in'));
        $criteria->addInCondition('direction', $direction);

        /*
         * direction
            Изменить	Удалить     -
            Изменить	Удалить	in  +
            Изменить	Удалить	itr -
            Изменить	Удалить	otr +
            Изменить	Удалить	out +
            Изменить	Удалить	redir +
         * itr - 7 записей за всё время
         * '' - закончились в 2011-01-12 07:30:07
         */

        return $criteria;
    }

    public static function getStat(/*CDbCriteria*/ $criteria){

    }

    /**
     * @param CDbCriteria $criteria
     * @return CActiveDataProvider, data[0]
     */
    public static function getTotal(/*CDbCriteria*/ $criteria){
        $criteria->select = "SUM(cost) as cost,SUM(billsec) as billsec, SUM(duration) as duration";
        return $sumProvider=new CActiveDataProvider('Stat',
            array(
                'criteria' => $criteria,
            )
        );
    }

    /**
     * @param CDbCriteria $criteria
     * * @return CActiveDataProvider, data[0]
     */
    public static function getMoscow($criteria){
        $criteria->select = "SUM(cost) as cost,SUM(billsec) as billsec, SUM(duration) as duration";
        $criteria->addCondition('kod=495 OR kod=499');
        return $sumProvider=new CActiveDataProvider('Stat',
            array(
                'criteria' => $criteria,
            )
        );
    }

    /**
     * @param CDbCriteria $criteria
     * * @return CActiveDataProvider, data[0]
     */
    public static function getSot($criteria){
        $criteria->select = "SUM(cost) as cost,SUM(billsec) as billsec, SUM(duration) as duration";
        $criteria->addCondition('kod like "9%"');
        return $sumProvider=new CActiveDataProvider('Stat',
            array(
                'criteria' => $criteria,
            )
        );
    }

    /**
     * @param CDbCriteria $criteria
     * * @return CActiveDataProvider, data[0]
     */
    public static function getOther($criteria){
        $criteria->select = "SUM(cost) as cost,SUM(billsec) as billsec, SUM(duration) as duration";
        $criteria->addCondition('kod not like "9%" and kod != "495" and kod != "499"');
        return $sumProvider=new CActiveDataProvider('Stat',
            array(
                'criteria' => $criteria,
            )
        );
    }
}
