<?php

/**
 * This is the model class for table "social_auths".
 *
 * The followings are the available columns in table 'social_auths':
 * @property integer $id
 * @property integer $user_id
 * @property string $social
 * @property string $identifier
 * @property string $first_name
 * @property string $last_name
 * @property string $display_name
 * @property string $email
 * @property string $location
 * @property string $access_token
 * @property string $access_secret
 * @property string $token_expiry
 * @property string $date_added
 * @property string $profile_url
 * @property string $profile_photo
 */
class SocialAuth extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'social_auths';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('identifier, first_name, last_name, display_name, access_token, date_added', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('social', 'length', 'max'=>9),
			array('identifier', 'length', 'max'=>32),
			array('first_name, last_name, display_name, location', 'length', 'max'=>64),
			array('email, access_token, access_secret, token_expiry, profile_url, profile_photo', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, social, identifier, first_name, last_name, display_name, email, location, access_token, access_secret, token_expiry, date_added, profile_url, profile_photo', 'safe', 'on'=>'search'),
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
			'social' => 'Social',
			'identifier' => 'Identifier',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'display_name' => 'Display Name',
			'email' => 'Email',
			'location' => 'Location',
			'access_token' => 'Access Token',
			'access_secret' => 'Access Secret',
			'token_expiry' => 'Token Expiry',
			'date_added' => 'Date Added',
			'profile_url' => 'Profile Url',
			'profile_photo' => 'Profile Photo',
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
		$criteria->compare('social',$this->social,true);
		$criteria->compare('identifier',$this->identifier,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('access_secret',$this->access_secret,true);
		$criteria->compare('token_expiry',$this->token_expiry,true);
		$criteria->compare('date_added',$this->date_added,true);
		$criteria->compare('profile_url',$this->profile_url,true);
		$criteria->compare('profile_photo',$this->profile_photo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SocialAuth the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
