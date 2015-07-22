<?php

/**
 * This is the model class for table "umb_message_log".
 *
 * The followings are the available columns in table 'umb_message_log':
 * @property integer $id
 * @property integer $user_id
 * @property integer $createtime
 * @property string $msgtype
 * @property string $content
 * @property string $msgid
 * @property string $PicUrl
 * @property string $MediaId
 * @property string $format
 * @property string $recognition
 * @property string $thumbmediaid
 * @property double $location_x
 * @property double $location_y
 * @property double $scale
 * @property string $lable
 */
class MessageLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'umb_message_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, createtime', 'numerical', 'integerOnly'=>true),
			array('location_x, location_y, scale', 'numerical'),
			array('msgtype, format', 'length', 'max'=>10),
			array('content, recognition', 'length', 'max'=>255),
			array('msgid', 'length', 'max'=>20),
			array('PicUrl, MediaId, thumbmediaid, lable', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, createtime, msgtype, content, msgid, PicUrl, MediaId, format, recognition, thumbmediaid, location_x, location_y, scale, lable', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'createtime' => 'Createtime',
			'msgtype' => 'Msgtype',
			'content' => 'Content',
			'msgid' => 'Msgid',
			'PicUrl' => 'Pic Url',
			'MediaId' => 'Media',
			'format' => 'Format',
			'recognition' => 'Recognition',
			'thumbmediaid' => 'Thumbmediaid',
			'location_x' => 'Location X',
			'location_y' => 'Location Y',
			'scale' => 'Scale',
			'lable' => 'Lable',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('msgtype',$this->msgtype,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('msgid',$this->msgid,true);
		$criteria->compare('PicUrl',$this->PicUrl,true);
		$criteria->compare('MediaId',$this->MediaId,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('recognition',$this->recognition,true);
		$criteria->compare('thumbmediaid',$this->thumbmediaid,true);
		$criteria->compare('location_x',$this->location_x);
		$criteria->compare('location_y',$this->location_y);
		$criteria->compare('scale',$this->scale);
		$criteria->compare('lable',$this->lable,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessageLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
