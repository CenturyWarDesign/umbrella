<?php

/**
 * This is the model class for table "umb_user".
 *
 * The followings are the available columns in table 'umb_user':
 * @property integer $id
 * @property string $udid
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $type
 * @property double $x
 * @property double $y
 * @property string $locate
 * @property integer $status
 * @property string $create_at
 * @property string $update_at
 * @property integer $times
 * @property string $nickname
 * @property integer $sex
 * @property string $language
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $headimgurl
 * @property string $remark
 * @property integer $subscribe_time
 * @property integer $groupid
 * @property string $accesstoken
 * @property string $refresh_token
 * @property integer $expires_in
 * @property integer $token_refreshed
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'umb_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('udid, create_at', 'required'),
			array('status, times, sex, subscribe_time, groupid, expires_in, token_refreshed', 'numerical', 'integerOnly'=>true),
			array('x, y', 'numerical'),
			array('udid, username, password, email', 'length', 'max'=>255),
			array('type', 'length', 'max'=>10),
			array('locate, remark', 'length', 'max'=>100),
			array('nickname', 'length', 'max'=>50),
			array('language, city, province, country', 'length', 'max'=>20),
			array('headimgurl, accesstoken, refresh_token', 'length', 'max'=>200),
			array('update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, udid, username, password, email, type, x, y, locate, status, create_at, update_at, times, nickname, sex, language, city, province, country, headimgurl, remark, subscribe_time, groupid, accesstoken, refresh_token, expires_in, token_refreshed', 'safe', 'on'=>'search'),
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
			'udid' => 'Udid',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'type' => 'Type',
			'x' => 'X',
			'y' => 'Y',
			'locate' => 'Locate',
			'status' => 'Status',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'times' => 'Times',
			'nickname' => 'Nickname',
			'sex' => 'Sex',
			'language' => 'Language',
			'city' => 'City',
			'province' => 'Province',
			'country' => 'Country',
			'headimgurl' => 'Headimgurl',
			'remark' => 'Remark',
			'subscribe_time' => 'Subscribe Time',
			'groupid' => 'Groupid',
			'accesstoken' => 'Accesstoken',
			'refresh_token' => 'Refresh Token',
			'expires_in' => 'Expires In',
			'token_refreshed' => 'Token Refreshed',
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
		$criteria->compare('udid',$this->udid,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('x',$this->x);
		$criteria->compare('y',$this->y);
		$criteria->compare('locate',$this->locate,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('times',$this->times);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('headimgurl',$this->headimgurl,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('subscribe_time',$this->subscribe_time);
		$criteria->compare('groupid',$this->groupid);
		$criteria->compare('accesstoken',$this->accesstoken,true);
		$criteria->compare('refresh_token',$this->refresh_token,true);
		$criteria->compare('expires_in',$this->expires_in);
		$criteria->compare('token_refreshed',$this->token_refreshed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function scopes()
	{
		return array(
				'get_id'=>array(
						'select' => 'id,udid',
				),
				'get_locate'=>array(
						'select' => 'nickname,x,y',
				)
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
