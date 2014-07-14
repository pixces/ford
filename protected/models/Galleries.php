<?php

/**
 * This is the model class for table "galleries".
 *
 * The followings are the available columns in table 'galleries':
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property string $description
 * @property integer $order_id
 * @property string $title
 * @property integer $is_active
 * @property integer $is_ugc
 * @property integer $voting_enabled
 * @property integer $is_moderated
 * @property string $date_created
 * @property string $date_modified
 */
class Galleries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'galleries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, date_created, date_modified', 'required'),
			array('order_id, is_active, is_ugc, voting_enabled, is_moderated', 'numerical', 'integerOnly'=>true),
			array('name, title', 'length', 'max'=>128),
			array('thumb', 'length', 'max'=>255),
			array('description', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, thumb, description, order_id, title, is_active, is_ugc, voting_enabled, is_moderated, date_created, date_modified', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'thumb' => 'Thumb',
			'description' => 'Description',
			'order_id' => 'Order',
			'title' => 'Title',
			'is_active' => '-1 is Pending, 0 Inactive, 1 Active',
			'is_ugc' => 'Is Ugc',
			'voting_enabled' => 'Voting Enabled',
			'is_moderated' => 'Is Moderated',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_ugc',$this->is_ugc);
		$criteria->compare('voting_enabled',$this->voting_enabled);
		$criteria->compare('is_moderated',$this->is_moderated);
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
	 * @return Galleries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
