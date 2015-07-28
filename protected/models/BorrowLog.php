<?php

/**
 * This is the model class for table "umb_borrow_log".
 *
 * The followings are the available columns in table 'umb_borrow_log':
 * @property integer $id
 * @property string $umbrellaid
 * @property integer $user_id
 * @property integer $borrowed_from
 * @property string $borrowed_locate
 * @property double $borrowed_x
 * @property double $borrowed_y
 * @property string $borrowed_at
 * @property string $repaid_at
 * @property integer $borrowed_type
 * @property string $des
 */
class BorrowLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'umb_borrow_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('umbrellaid, user_id, borrowed_from, borrowed_at', 'required'),
			array('user_id, borrowed_from, borrowed_type', 'numerical', 'integerOnly'=>true),
			array('borrowed_x, borrowed_y', 'numerical'),
			array('umbrellaid', 'length', 'max'=>25),
			array('borrowed_locate', 'length', 'max'=>20),
			array('des', 'length', 'max'=>200),
			array('repaid_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, umbrellaid, user_id, borrowed_from, borrowed_locate, borrowed_x, borrowed_y, borrowed_at, repaid_at, borrowed_type, des', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'borrowed_from' => 'Borrowed From',
			'borrowed_locate' => 'Borrowed Locate',
			'borrowed_x' => 'Borrowed X',
			'borrowed_y' => 'Borrowed Y',
			'borrowed_at' => 'Borrowed At',
			'repaid_at' => 'Repaid At',
			'borrowed_type' => 'Borrowed Type',
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
		$criteria->compare('umbrellaid',$this->umbrellaid,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('borrowed_from',$this->borrowed_from);
		$criteria->compare('borrowed_locate',$this->borrowed_locate,true);
		$criteria->compare('borrowed_x',$this->borrowed_x);
		$criteria->compare('borrowed_y',$this->borrowed_y);
		$criteria->compare('borrowed_at',$this->borrowed_at,true);
		$criteria->compare('repaid_at',$this->repaid_at,true);
		$criteria->compare('borrowed_type',$this->borrowed_type);
		$criteria->compare('des',$this->des,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BorrowLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
