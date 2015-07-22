<?php

/**
 * This is the model class for table "umb_wxaccesstoken".
 *
 * The followings are the available columns in table 'umb_wxaccesstoken':
 * @property string $appid
 * @property string $appsecret
 * @property string $accesstoken
 * @property string $create_at
 * @property integer $expire_at
 */
class Wxaccesstoken extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'umb_wxaccesstoken';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('accesstoken, create_at', 'required'),
			array('expire_at', 'numerical', 'integerOnly'=>true),
			array('appid, appsecret', 'length', 'max'=>50),
			array('accesstoken', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('appid, appsecret, accesstoken, create_at, expire_at', 'safe', 'on'=>'search'),
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
			'appid' => 'Appid',
			'appsecret' => 'Appsecret',
			'accesstoken' => 'Accesstoken',
			'create_at' => 'Create At',
			'expire_at' => 'Expire At',
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

		$criteria->compare('appid',$this->appid,true);
		$criteria->compare('appsecret',$this->appsecret,true);
		$criteria->compare('accesstoken',$this->accesstoken,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('expire_at',$this->expire_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wxaccesstoken the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
