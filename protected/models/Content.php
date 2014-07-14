<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property integer $id
 * @property integer $gallery_id
 * @property string $user_id
 * @property string $title
 * @property string $description
 * @property string $source
 * @property string $media_url
 * @property string $alternate_url
 * @property string $type
 * @property string $author
 * @property string $channel_name
 * @property string $authentication
 * @property string $notes
 * @property string $flags
 * @property integer $is_ugc
 * @property integer $is_submitted
 * @property string $locations
 * @property string $thumb_image
 * @property string $alternate_image
 * @property string $status
 * @property string $date_created
 * @property string $date_modified
 * @property integer $is_submitted
 * @property string $location
 * @property integer $vote
 *
 * The followings are the available model relations:
 * @property AbuseReports[] $abuseReports
 * @property Galleries $gallery
 * @property Users $user
 * @property ContentViews[] $contentViews
 * @property ContentVotes[] $contentVotes
 */
class Content extends CActiveRecord
{
    public $image;
    public $video;
    public $file;
    public $ppt;
    public $pdf;
    public $doc;
    public $upload_type;
    public $locations;
    public $is_submitted;
    public $vote;
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gallery_id, user_id, title, description, type, date_created, date_modified', 'required'),
			array('gallery_id, user_id, is_ugc, is_submitted, vote', 'numerical', 'integerOnly'=>true),
			array('title, media_url, alternate_url, thumb_image, alternate_image', 'length', 'max'=>255),
			array('source, flags', 'length', 'max'=>50),
			array('media_id', 'length', 'max'=>15),
			array('type', 'length', 'max'=>5),
			array('author, channel_name, authentication, location', 'length', 'max'=>150),
			array('status', 'length', 'max'=>12),
			array('notes,vote,image', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, gallery_id, user_id, title, description, source, media_id, media_url, alternate_url, type, author, channel_name, authentication, notes, flags, is_ugc, thumb_image, alternate_image, status, date_created, date_modified, is_submitted, location, vote', 'safe', 'on'=>'search'),
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
			'abuseReports' => array(self::HAS_MANY, 'AbuseReports', 'content_id'),
			'gallery' => array(self::BELONGS_TO, 'Galleries', 'gallery_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'contentViews' => array(self::HAS_MANY, 'ContentViews', 'content_id'),
			'contentVotes' => array(self::HAS_MANY, 'ContentVotes', 'content_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'gallery_id' => 'Gallery',
			'user_id' => 'User',
			'title' => 'Title',
			'description' => 'Description',
			'source' => 'Source',
			'media_id' => 'Media',
			'media_url' => 'Media Url',
			'alternate_url' => 'Alternate Url',
			'type' => 'Type',
			'author' => 'Author',
			'channel_name' => 'Channel Name',
			'authentication' => 'Authentication',
			'notes' => 'Notes',
			'flags' => 'Flags',
			'is_ugc' => 'Is Ugc',
			'thumb_image' => 'Thumb Image',
			'alternate_image' => 'Alternate Image',
			'status' => 'Status',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
                        'is_submitted' => 'Is Submitted',
			'location' => 'Location',
                        'vote' => 'Vote',
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
		$criteria->compare('gallery_id',$this->gallery_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('media_id',$this->media_id,true);
		$criteria->compare('media_url',$this->media_url,true);
		$criteria->compare('alternate_url',$this->alternate_url,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('channel_name',$this->channel_name,true);
		$criteria->compare('authentication',$this->authentication,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('flags',$this->flags,true);
		$criteria->compare('is_ugc',$this->is_ugc);
		$criteria->compare('thumb_image',$this->thumb_image,true);
		$criteria->compare('alternate_image',$this->alternate_image,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);
        $criteria->compare('is_submitted',$this->is_submitted);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('vote',$this->vote);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Content the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /**
     * @return bool
     */
    public function beforeValidate() {

        if ($this->isNewRecord){
            $this->date_created = new CDbExpression('NOW()');
        }
        
           $this->date_modified = new CDbExpression('NOW()');
        

        return parent::beforeSave();
    }
 
}
