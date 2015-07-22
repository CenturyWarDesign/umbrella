<?php

/**
 * This is the model class for table "umb_message_log".
 *
 * The followings are the available columns in table 'umb_message_log':
 * @property integer $id
 * @property string $openid
 * @property integer $createtime
 * @property string $msgtype
 * @property string $content
 * @property string $msgid
 * @property string $picurl
 * @property string $mediaid
 * @property string $format
 * @property string $recognition
 * @property string $thumbmediaid
 * @property double $location_x
 * @property double $location_y
 * @property double $scale
 * @property string $lable
 * @property string $title
 * @property string $description
 * @property string $url
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
			array('createtime', 'numerical', 'integerOnly'=>true),
			array('location_x, location_y, scale', 'numerical'),
			array('openid', 'length', 'max'=>50),
			array('msgtype, format', 'length', 'max'=>10),
			array('content, recognition', 'length', 'max'=>255),
			array('msgid', 'length', 'max'=>20),
			array('picurl, mediaid, thumbmediaid, lable, title, description, url', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, openid, createtime, msgtype, content, msgid, picurl, mediaid, format, recognition, thumbmediaid, location_x, location_y, scale, lable, title, description, url', 'safe', 'on'=>'search'),
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
			'openid' => 'Openid',
			'createtime' => 'Createtime',
			'msgtype' => 'Msgtype',
			'content' => 'Content',
			'msgid' => 'Msgid',
			'picurl' => 'Picurl',
			'mediaid' => 'Mediaid',
			'format' => 'Format',
			'recognition' => 'Recognition',
			'thumbmediaid' => 'Thumbmediaid',
			'location_x' => 'Location X',
			'location_y' => 'Location Y',
			'scale' => 'Scale',
			'lable' => 'Lable',
			'title' => 'Title',
			'description' => 'Description',
			'url' => 'Url',
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
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('msgtype',$this->msgtype,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('msgid',$this->msgid,true);
		$criteria->compare('picurl',$this->picurl,true);
		$criteria->compare('mediaid',$this->mediaid,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('recognition',$this->recognition,true);
		$criteria->compare('thumbmediaid',$this->thumbmediaid,true);
		$criteria->compare('location_x',$this->location_x);
		$criteria->compare('location_y',$this->location_y);
		$criteria->compare('scale',$this->scale);
		$criteria->compare('lable',$this->lable,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('url',$this->url,true);

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
