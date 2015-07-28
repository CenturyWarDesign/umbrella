<?php

/**
 * This is the model class for table "umb_community".
 *
 * The followings are the available columns in table 'umb_community':
 * @property integer $id
 * @property string $communityid
 * @property integer $user_id
 * @property string $begin_time
 * @property string $end_time
 * @property double $lng
 * @property double $lat
 * @property string $address
 * @property integer $umb10
 * @property integer $umb20
 * @property integer $umb30
 * @property integer $umb50
 * @property integer $type
 * @property string $des
 */
class Community extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'umb_community';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, begin_time', 'required'),
			array('user_id, umb10, umb20, umb30, umb50, type', 'numerical', 'integerOnly'=>true),
			array('lng, lat', 'numerical'),
			array('communityid', 'length', 'max'=>25),
			array('address, des', 'length', 'max'=>200),
			array('end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, communityid, user_id, begin_time, end_time, lng, lat, address, umb10, umb20, umb30, umb50, type, des', 'safe', 'on'=>'search'),
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
			'communityid' => 'Communityid',
			'user_id' => 'User',
			'begin_time' => 'Begin Time',
			'end_time' => 'End Time',
			'lng' => 'Lng',
			'lat' => 'Lat',
			'address' => 'Address',
			'umb10' => 'Umb10',
			'umb20' => 'Umb20',
			'umb30' => 'Umb30',
			'umb50' => 'Umb50',
			'type' => 'Type',
			'des' => 'Des',
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
		$criteria->compare('communityid',$this->communityid,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('lng',$this->lng);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('umb10',$this->umb10);
		$criteria->compare('umb20',$this->umb20);
		$criteria->compare('umb30',$this->umb30);
		$criteria->compare('umb50',$this->umb50);
		$criteria->compare('type',$this->type);
		$criteria->compare('des',$this->des,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Community the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
