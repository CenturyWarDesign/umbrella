<?php

/**
 * This is the model class for table "umb_umbrella".
 *
 * The followings are the available columns in table 'umb_umbrella':
 * @property integer $id
 * @property string $umbrellaid
 * @property integer $create_userid
 * @property integer $now_userid
 * @property string $des
 * @property string $img
 * @property double $price
 * @property integer $status
 * @property string $create_at
 * @property string $update_at
 */
class Umbrella extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'umb_umbrella';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('umbrellaid, create_userid, now_userid, create_at,img,des,price', 'required'),
			array('create_userid, now_userid, status', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('umbrellaid', 'length', 'max'=>25),
			array('des', 'length', 'max'=>255),
			array('img', 'length', 'max'=>200),
			array('update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, umbrellaid, create_userid, now_userid, des, img, price, status, create_at, update_at', 'safe', 'on'=>'search'),
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
			'umbrellaid' => 'Umbrellaid',
			'create_userid' => 'Create Userid',
			'now_userid' => 'Now Userid',
			'des' => '描述',
			'img' => '图片',
			'price' => '价格',
			'status' => 'Status',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
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
		$criteria->compare('umbrellaid',$this->umbrellaid,true);
		$criteria->compare('create_userid',$this->create_userid);
		$criteria->compare('now_userid',$this->now_userid);
		$criteria->compare('des',$this->des,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Umbrella the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
