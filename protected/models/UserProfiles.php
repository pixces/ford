<?php

/**
 * This is the model class for table "user_profiles".
 *
 * The followings are the available columns in table 'user_profiles':
 * @property integer $id
 * @property integer $user_id
 * @property string $full_name
 * @property string $displayname
 * @property string $phone
 * @property string $mobile
 * @property string $dob
 * @property string $city
 * @property string $country
 * @property string $occupation
 * @property string $profile_image
 * @property string $passport
 * @property string $passport_number
 * @property string $passport_authority
 * @property string $passport_expiry
 * @property string $facebook
 * @property string $twitter
 * @property string $flickr
 * @property string $instagram
 * @property string $interview_location
 * @property string $interview_address
 * @property string $travel_frequency
 * @property integer $international_travel
 * @property string $countries_visited
 * @property integer $sports_certificate
 * @property string $certificate_details
 * @property string $about_me
 * @property string $authenticated_from
 * @property string $skills
 * @property string $movies
 * @property string $books
 * @property string $music
 * @property string $sports
 * @property string $hobbies
 * @property string $others
 * @property string $date_created
 * @property string $date_modified
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserProfiles extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user_profiles';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, full_name', 'required'),
			array('user_id, international_travel, sports_certificate', 'numerical', 'integerOnly'=>true),
			array('full_name, displayname, city, country, occupation', 'length', 'max'=>100),
			array('phone, mobile, passport_number, passport_expiry', 'length', 'max'=>25),
			array('profile_image, facebook, twitter, flickr, instagram', 'length', 'max'=>256),
			array('passport', 'length', 'max'=>1),
			array('passport_authority, interview_location', 'length', 'max'=>50),
			array('travel_frequency, countries_visited', 'length', 'max'=>255),
			array('authenticated_from', 'length', 'max'=>8),
			array('dob, interview_address, certificate_details, skills, movies, books, music, sports, hobbies, others', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, full_name, displayname, phone, mobile, dob, city, country, occupation, profile_image, passport, passport_number, passport_authority, passport_expiry, facebook, twitter, flickr, instagram, interview_location, interview_address, travel_frequency, international_travel, countries_visited, sports_certificate, certificate_details, about_me, authenticated_from, skills, movies, books, music, sports, hobbies, others, date_created, date_modified', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
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
			'full_name' => 'Full Name',
			'displayname' => 'Displayname',
			'phone' => 'Phone',
			'mobile' => 'Mobile',
			'dob' => 'Dob',
			'city' => 'City',
			'country' => 'Country',
			'occupation' => 'Occupation',
			'profile_image' => 'Profile Image',
			'passport' => 'Passport',
			'passport_number' => 'Passport Number',
			'passport_authority' => 'Passport Authority',
			'passport_expiry' => 'Passport Expiry',
			'facebook' => 'Facebook',
			'twitter' => 'Twitter',
			'flickr' => 'Flickr',
			'instagram' => 'Instagram',
			'interview_location' => 'Interview Location',
			'interview_address' => 'Interview Address',
			'travel_frequency' => 'Travel Frequency',
			'international_travel' => 'International Travel',
			'countries_visited' => 'Countries Visited',
			'sports_certificate' => 'Sports Certificate',
			'certificate_details' => 'Certificate Details',
			'about_me' => 'About Me',
			'authenticated_from' => 'Authenticated From',
			'skills' => 'Skills',
			'movies' => 'Movies',
			'books' => 'Books',
			'music' => 'Music',
			'sports' => 'Sports',
			'hobbies' => 'Hobbies',
			'others' => 'Others',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
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
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('displayname',$this->displayname,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('dob',$this->dob,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('occupation',$this->occupation,true);
		$criteria->compare('profile_image',$this->profile_image,true);
		$criteria->compare('passport',$this->passport,true);
		$criteria->compare('passport_number',$this->passport_number,true);
		$criteria->compare('passport_authority',$this->passport_authority,true);
		$criteria->compare('passport_expiry',$this->passport_expiry,true);
		$criteria->compare('facebook',$this->facebook,true);
		$criteria->compare('twitter',$this->twitter,true);
		$criteria->compare('flickr',$this->flickr,true);
		$criteria->compare('instagram',$this->instagram,true);
		$criteria->compare('interview_location',$this->interview_location,true);
		$criteria->compare('interview_address',$this->interview_address,true);
		$criteria->compare('travel_frequency',$this->travel_frequency,true);
		$criteria->compare('international_travel',$this->international_travel);
		$criteria->compare('countries_visited',$this->countries_visited,true);
		$criteria->compare('sports_certificate',$this->sports_certificate);
		$criteria->compare('certificate_details',$this->certificate_details,true);
		$criteria->compare('about_me',$this->about_me,true);
		$criteria->compare('authenticated_from',$this->authenticated_from,true);
		$criteria->compare('skills',$this->skills,true);
		$criteria->compare('movies',$this->movies,true);
		$criteria->compare('books',$this->books,true);
		$criteria->compare('music',$this->music,true);
		$criteria->compare('sports',$this->sports,true);
		$criteria->compare('hobbies',$this->hobbies,true);
		$criteria->compare('others',$this->others,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserProfiles the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function beforeSave() {

        if ($this->isNewRecord){
            $this->date_created = new CDbExpression('NOW()');
            $this->date_modified = new CDbExpression('NOW()');
        }
        else {
            $this->date_modified = new CDbExpression('NOW()');
        }

        return parent::beforeSave();
    }
}
