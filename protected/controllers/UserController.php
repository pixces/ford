<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{

        return array(
            /*
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','socialLogin','captcha'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin@cnk.com'),
                // TODO: There is a glitch in admin role. Enable the admin role from db. then it will be easy to handle
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
            */
		);

	}

    /**
     * Default/Index landing page
     */
    public function actionIndex(){
        //create absolute url for redirection
        $this->redirect($this->createAbsoluteUrl('user/login'));
        Yii::app()->end();
    }

    /**
     * Registration Action
     * Check if the user is not already logged-in
     * Display user registration form & Submit Action
     */
    public function actionRegister(){
        $siteParams = $this->getSiteParams();

        //redirect user if already registered
        if(!Yii::app()->user->isGuest){
            $redirectUrl = Yii::app()->createAbsoluteUrl("user/login",$this->getSiteParams());
            $this->redirect($redirectUrl);
        }

        $model = new User;
        $modelUserProfile = new UserProfiles;

        if($_POST){
//            print_r($_POST); exit;
            //check if the user already exists in the system
            $emailId = $this->sanitizeData($_POST['user']['email']);
            $user = User::model()->find('email=:emailId', array(':emailId' => $emailId));

            if (!$user){
                $username = explode(" ",$this->sanitizeData($_POST['user']['name']));
                $userObj = array();
                $userObj['first_name'] = $username[0];
                $userObj['last_name']  = $username[1];
                $userObj['email']      = $emailId;
                $userObj['password']   = md5($_POST['user']['password']);
                $userObj['verification_code'] = md5( $userObj['email']."|".$userObj['first_name']."|".time() );

                $model->attributes = $userObj;

                //save this user information
                if ($model->save()){
                    //now save the profile image and then continue to save the user profile data
                    $profilePic = null;

                    if($_FILES['UserProfiles']['error']['profile_image'] == 0){
                        //photo uploaded into the application
                        if(CUploadedFile::getInstance($modelUserProfile,'profile_image')){

                            $uploadPath = "/upload/images/";
                            $basePath = Yii::app()->basePath."/..".$uploadPath;

                            $modelUserProfile->profile_image=CUploadedFile::getInstance($modelUserProfile,'profile_image');

                            $picName = $modelUserProfile->profile_image;

                            $profilePicImage = str_replace(" ","_",$picName);

                            $profilePicImage = time()."_".$profilePicImage;
                            $modelUserProfile->profile_image->saveAs($basePath.$profilePicImage);
                            $profilePic = Yii::app()->createAbsoluteUrl($uploadPath.$profilePicImage);
                        }
                    } else if(!empty($_POST['social_profile_pic'])) {
                        //profile captured form social auth
                        $model->scenario = 'social';
                        $profilePic = $_POST['social_profile_pic'];
                    }

                    //prepare additional data to be saved ti db
                    $userProfile = array();
                    $userProfile['user_id']             = $model->id;
                    $userProfile['full_name']           = $model->first_name." ".$model->last_name;
                    $userProfile['displayname']         = $model->first_name;
                    $userProfile['city']                = $this->sanitizeData($_POST['UserProfile']['city']);
                    $userProfile['profile_image']       = $profilePic;
                    $userProfile['phone']               = $_POST['UserProfile']['phone'];

                    $modelUserProfile->attributes = $userProfile;

                    if($modelUserProfile->save()){

                        //prepare user session to for login
                        Yii::app()->session['user_full_name'] = $userProfile['full_name'];
                        Yii::app()->session['user_id'] = $userProfile['user_id'];
                        Yii::app()->session['user_email'] = $model->email;

                        //force login this use
                        $this->forceLogin($_POST['user']['email'], $_POST['user']['password']);

                        //redirect the user to the profile page
                        $this->redirect($this->createAbsoluteUrl('user/participate'));


                    } else {
                        // Failed to save profile info, delete the user info
                        User::model()->deleteByPk($model->primaryKey);
                        $error =  "Profile data save failed, profile data dump is : ".serialize($modelUserProfile->getErrors());
                        Yii::log($error);
                    }
                } else {
                    $error = "Unable to save user information";
                    Yii::log( $error.serialize($_POST));
                }
            } else {
                $error = "User ".$_POST['user']['email']." already exists. Please login!";
                Yii::log($error);
                //throw the error messages and quit
                $model->addError('email',$error);
            }
        }

        $this->page_name = 'register';
        $this->render($this->page_name, array(
            'page_name' => $this->page_name,
            'nav'       => $this->getNav(),
            'siteParams'=> $this->getSiteParams(),
            'widget'    => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),
            'model'=>$model,
            'modelUserProfile'=>$modelUserProfile,
        ));
    }

    /**
     * Script to force login users
     * @param $username
     * @param $password
     */
    private function forceLogin($username, $password) {

        $identity = new UserIdentity($username,$password);
        $identity->authenticate('user', true);

        if($identity->errorCode === UserIdentity::ERROR_NONE){
            $duration = 3600*24*60; // 60 days
            Yii::app()->user->login($identity,$duration);
            User::model()->updateByPk($identity->getId(),array('last_login_time' => new CDbExpression('NOW()')));
        }
    }


    /**
     * Login Action
     * Check if the user is already logged in
     * redirect the user to the Submission Page
     * after login
     */
    public function actionLogin(){

        if(!Yii::app()->user->isGuest){
            $this->isContentSubmitted(Yii::app()->user->getId());
            Yii::app()->end();
        }

        $model = new LoginForm;
        $this->page_name = 'login';

        if(!empty($_POST)) {

            //scan the $_POST for xss
            $data = $this->sanitizeData($_POST);
            $model->attributes= $data;

            if($model->validate() && $model->login())
            {
                //now create a new cookie to enable voting
                $user = User::model()->findByPk(Yii::app()->user->getId());
                $name = $user->first_name." ".$user->last_name;
                $email = $user->email;

                //set the actual cookie
                $cookie = new CHttpCookie('userVote', $name.'|'.$email);
                $cookie->expire = time() + (30*60*60*24); // 24 hours
                $cookie->path = "/";
                Yii::app()->request->cookies['userVote'] = $cookie;

                $this->isContentSubmitted(Yii::app()->user->getId());
                Yii::app()->end();
            }
        }

        if(isset($_GET['not_verified']) && $_GET['not_verified'] == true) {
            Yii::app()->user->setFlash('error', 'You are not verified. Please check your email to complete verification.');
        }

        $this->page_name = 'login';
        $this->render($this->page_name, array(
            'model' => $model,
            'page_name'=>$this->page_name,
            'nav' => $this->getNav(),
            'siteParams' => $this->getSiteParams(),
            'widget' => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),
        ));

        Yii::app()->end();
    }

    /**
     * Method to redirect users based on their submissions
     * @param $userId
     */

    public function isContentSubmitted($userId)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('user_id',$userId);
        $criteria->compare('is_submitted', 1);
        $contentCount = Content::model()->count($criteria);

        if($contentCount > 0)
        {
            // redirect to profile page
            $redirectUrl = Yii::app()->createAbsoluteUrl("user/profile",$this->getSiteParams());

        } else {
            // redirect to Submission page
            $redirectUrl = Yii::app()->createAbsoluteUrl("ugc/submission",$this->getSiteParams());
        }
        $this->redirect($redirectUrl);
    }


    /**
     * Check if the user is already loggedin
     * redirect the user to the sbumission page
     * else redirect the use to the participate page
     */
    public function actionParticipate(){

        if(!Yii::app()->user->isGuest){
            $this->isContentSubmitted(Yii::app()->user->getId());
            Yii::app()->end();
        } else {
            $this->redirect(Yii::app()->createAbsoluteUrl("user/login",$this->getSiteParams()));
        }
        Yii::app()->end();
    }

    /**
     * Display user profile after login
     * @param null $id
     */
    public function actionProfile($id=null){

        //check for user login
        if(Yii::app()->user->isGuest){
            $this->redirect(Yii::app()->createAbsoluteUrl("user/login",$this->getSiteParams()));
            Yii::app()->end();
        }

        //basic content array
        $content = Yii::app()->params['celebrity'];
        $ugcGalleryId = Yii::app()->params['ugcGalleryId'];
        $userSubmissions = 0;
        $userId = Yii::app()->user->getId();

        //get user details
        $user = $this->loadModel($userId);
        $profile = UserProfiles::model()->find('user_id=:userId',array(":userId"=>$user->id));

        //get the content submitted by this user
        $params = array(
            'user_id' => $userId,
            'gallery_id' => $ugcGalleryId,
            'is_ugc' => 1,
        );
        //$contentList = Yii::app()->services->performRequest('/content',$params,'GET')->getResponseData(true);
        //print_r($contentList);



        $profile_page_name = 'profile';
        $this->page_name = $profile_page_name;
        $this->render($this->page_name, array(
            'model'     => $user,
            'profile'   => $profile,
            'content'   => $content,
            'page_name' => $this->page_name,
            'nav'       => $this->getNav(),
            'siteParams'=> $this->getSiteParams(),
            'widget'    => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),
        ));
    }




    /**
     * Profile Edit Action
     *
     * Check if user is already logged in
     * redirect to login form if not
     *
     * Display Edit Profile Form
     * Submit and Update information
     */
    public function actionEdit(){

        // If user not logged in redirect the user to login page
        if(Yii::app()->user->isGuest){
            $this->redirect($this->createAbsoluteUrl('user/login'));
            Yii::app()->end();
        }

        //get the current loggedin user
        $userId = Yii::app()->user->id;

        $model = $this->loadModel($userId);
        $modelUserProfile = UserProfiles::model()->find('user_id=:userId',array(":userId"=>$model->id));

        if(isset($_REQUEST['phase'])=="winner")
		{
		if($_POST){
			$userObj['wruskills'] = $this->sanitizeData($_POST['User']['wruskills']);
            $model->attributes=$userObj;
            $model->scenario = "update";		
			
			if($model->save()){ 


				$userProfile = array();
				$userProfile['user_id'] = $model->id;               
				$userProfile['wruskills'] = $this->sanitizeData($_POST['User']['wruskills']);
				$userProfile['wmuwatched'] = $this->sanitizeData($_POST['User']['wmuwatched']);	
				$userProfile['wburead'] = $this->sanitizeData($_POST['User']['wburead']);
				$userProfile['wrufmusic'] = $this->sanitizeData($_POST['User']['wrufmusic']);
				$userProfile['wsportslike'] = $this->sanitizeData($_POST['User']['wsportslike']);
				$userProfile['wruointerests'] = $this->sanitizeData($_POST['User']['wruointerests']);
				$userProfile['wruhobbies'] = $this->sanitizeData($_POST['User']['wruhobbies']);			
               
                $modelUserProfile->attributes=$userProfile;                
                if($modelUserProfile->save()){
                    $message =  "updating user profile data update";
                    Yii::log($message);
                    Yii::app()->user->setFlash('update_profile','Your profile has been updated.');
                } else {
                    $message = "Unable to update user profile info, Error : ".serialize($modelUserProfile->getErrors());
                    Yii::log($message);
                }
            } else {
                $message = "Unable to update user data, Error : ".serialize($model->getErrors());
                Yii::log($message);
            }
			}
		}
		else
		{
        if($_POST){
            $userObj['first_name'] = $this->sanitizeData($_POST['User']['firstname']);
            $userObj['last_name']  = $this->sanitizeData($_POST['User']['lastname']);
            if($_POST['new_password']) {
                $userObj['password']   = md5($_POST['new_password']);
            }    
            
            
            $model->attributes=$userObj;
            $model->scenario = "update";
            
            if($model->save()){ 
                // Upload profile pic if not empty
                if(CUploadedFile::getInstance($modelUserProfile,'profile_image')){
                    $message =  "Uploading user profile pic from desktop";
                    Yii::log($message);
                    $uploadPath = "/uploadedImages/";
                    $basePath = Yii::app()->basePath."/..".$uploadPath;
                    $modelUserProfile->profile_image=CUploadedFile::getInstance($modelUserProfile,'profile_image');
                    $picName = $modelUserProfile->profile_image;
                    
                    // Replace all the space with underscore
                    $profilePicImage = str_replace(" ","_",$picName);

                    // Add a unique code before the profile image name to avoid the overwrite
                    $profilePicImage = time()."_".$profilePicImage;

                    // $modelUserProfile['profile_image'] = $profilePicImage;
                    // $modelUserProfile->save();
                    $modelUserProfile->profile_image->saveAs($basePath.$profilePicImage);
                    $profilePicImage = Yii::app()->createAbsoluteUrl($uploadPath.$profilePicImage);

                    //$modelUserProfile->profile_image->saveAs($profilePicImage);

                } else {
                    $profilePicImage = $modelUserProfile->profile_image;
                }

                $fbUrl = isset($_POST['facebook-url']) ? $_POST['facebook-url'] : null;
                $twUrl = isset($_POST['twitter-url']) ? $_POST['twitter-url'] : null;
                $igUrl = isset($_POST['instagram-url']) ? $_POST['instagram-url'] : null;
                $fkUrl = isset($_POST['flickr-url']) ? $_POST['flickr-url'] : null;
                
                $userProfile = array();
                $userProfile['user_id']             = $model->id;
                $userProfile['full_name']           = $model->first_name." ".$model->last_name;
                $userProfile['displayname']         = $model->first_name;
                $userProfile['mobile']              = $_POST['User']['mobile'];
                $userProfile['dob']                 = date('Y-m-d',strtotime($_POST['User']['dob']));
                $userProfile['city']                = $this->sanitizeData($_POST['User']['city']);
                $userProfile['occupation']          = $this->sanitizeData($_POST['UserProfiles']['occupation']);
                if($_POST['passport'] == 'yes')
                    $userProfile['passport'] = true;
                else
                    $userProfile['passport'] = false;
                $userProfile['facebook']            = $this->sanitizeData($fbUrl);
                $userProfile['twitter']             = $this->sanitizeData($twUrl);
                $userProfile['instagram']           = $this->sanitizeData($igUrl);
                $userProfile['flickr']              = $this->sanitizeData($fkUrl);
                $userProfile['about_me']            = $this->sanitizeData($_POST['UserProfiles']['about_me']);
               
                $modelUserProfile->attributes=$userProfile;
                $modelUserProfile->profile_image=$profilePicImage;
                if($modelUserProfile->save()){
                    $message =  "updating user profile data update";
                    Yii::log($message);
                    Yii::app()->user->setFlash('update_profile','Your profile has been updated.');
                } else {
                    $message = "Unable to update user profile info, Error : ".serialize($modelUserProfile->getErrors());
                    Yii::log($message);
                }
            } else {
                $message = "Unable to update user data, Error : ".serialize($model->getErrors());
                Yii::log($message);
            }
        }
		}

        $this->page_name = 'edit-profile';
        $this->render($this->page_name, array(
            'page_name'=>$this->page_name,
            'nav' => $this->nav,
            'siteParams' => $this->getSiteParams(),
            'widget' => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),
            'model' => $model,
            'modelUserProfile' => $modelUserProfile,
        ));
    }

    /**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        if(!Yii::app()->user->isGuest){
            $this->redirect(array('view','id'=>Yii::app()->user->id));
        }
		$model=new User;
        //$model->scenario = 'registerwcaptcha';
        $socialProfile = array();
        $socialType = null;
        $error = "";

		// Uncomment the following line if AJAX validation is needed
	    $this->performAjaxValidation($model);


		if(isset($_POST['User']))
		{
//            echo "<pre>"; print_r($_POST); exit;
			$model->attributes=$_POST['User'];
            try {
                if($model->save()){
                    // Send mail to user

                    // Auto login the user if the user is logged in via

                    $model->scenario = NULL;
                    $this->redirect(array('view','id'=>$model->id));
                }
            } catch (Exception $e){
                $error = $e->errorInfo[2];
            }
		}


        if(isset($_GET['provider'])){
            $socialType = $_GET['provider'];
            $socialProfile = $this->__actionAuthenticate($socialType);
            $socialProfile->social_type = $socialType;

            //print_r($socialProfile);
        }

        $model->addError('', $error);

		$this->render('create',array(
			'model'=>$model,
            'socialProfile'=>$socialProfile,
		));
	}

	/**
	 * Change Password Action
     * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionUpdatepass()
	{
		//check if user is logged-in
        if(Yii::app()->user->isGuest){
            $redirectUrl = Yii::app()->createAbsoluteUrl("user/login",$this->getSiteParams());
            $this->redirect($redirectUrl);
        }

        if(isset($_POST))
		{
            if($_POST['new_password'] == $_POST['confirm_new_password']) {

                //get the user information
                $id = Yii::app()->user->id;

                $user = User::model()->findByPk($id);
                if ($user){
                    //verify if the old password is same as the existing password
                    if ($user->password == md5($_POST['old_password'])){

                        //update the new password
                        $user->password = md5($_POST['new_password']);

                        //save the details
                        if ($user->save()){
                            Yii::app()->user->setFlash('update_profile','New password has been updated successfully.');
                        } else {
                            Yii::app()->user->setFlash('update_profile','Unable to update your password, invalid existing password. Try again');
                        }
                    } else {
                        Yii::app()->user->setFlash('update_profile','Invalid Credentials Supplied. Please reset your password.');
                    }
                } else {
                    Yii::app()->user->setFlash('update_profile','Invalid Credentials Supplied. No user found.');
                }

            } else {
                Yii::app()->user->setFlash('update_profile','Password & Confirm Password fields mismatch.');
            }
		}
        $this->redirect($this->createAbsoluteUrl('user/edit'));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * Authenticte action
     */
    private function __actionAuthenticate($provider){
        Yii::import('application.components.HybridAuthIdentity');
        $haComp = new HybridAuthIdentity();

        if (!$haComp->validateProviderName($provider))
            throw new CHttpException ('500', 'Invalid Action. Please try again.');

        $haComp->adapter = $haComp->hybridAuth->authenticate($provider);
        $haComp->userProfile = $haComp->adapter->getUserProfile();

        return $haComp->userProfile;
    }

    public function actionSocialLogin(){
        Yii::import('application.components.HybridAuthIdentity');
        $path = Yii::getPathOfAlias('ext.HybridAuth');
        require_once $path . '/hybridauth-' . HybridAuthIdentity::VERSION . '/hybridauth/index.php';
    }
    
    /**
     * Function to do the logout process
     * Will logout the user
     * and redirect them to the site index page
     */
    public function actionLogout(){
        Yii::app()->user->logout();
        $redirectUrl = Yii::app()->createAbsoluteUrl("pages/display",$this->getSiteParams());
        $this->redirect($redirectUrl);
    }

    public function actionPasswordReset(){

        if($_POST){
            if (isset($_POST['email'])){
                //check if the user already exists in the system
                $user = User::model()->find('email = :emailId', array(':emailId' => $_POST['email']));
                
                if(empty($_POST['email'])){
                    Yii::app()->user->setFlash('error', 'Enter a valid email address.');
                }
                else if ($user){
                    if ( $user->is_verified ){
                        //create a new password (6 - 8 digit alphanum random)
                        $randomNumber = $this->randomNumber(8);

                        $user->password = md5($randomNumber);

                        //update password in the database
                        if ( $user->save() ){
                            //send email to the user

                            $mailData =  array('name' => $user->first_name." ".$user->last_name , 'password'=>$randomNumber);
                            $subject = '[Grab Your Dream] Password reset mail';

                            //send the email to the user
                            if ( $this->sendUserMail('password-reset',$user->email,$mailData,$subject) ){
                                //$redirectUrl = Yii::app()->createAbsoluteUrl("user/confirmation",$this->getSiteParams());
                                //$this->redirect($redirectUrl);
                                //$user->addError('email',"Password reset Successful. An email has been sent with new login information.");
                                Yii::app()->user->setFlash('error', 'Password reset Successful. An email has been sent with new login information.');

                            } else {
                                // Failed to save profile info, delete the user info
                                $error =  "Cannot send reset password email ".$user->email;
                                Yii::log($error);
                            }
                        }
                    } else {
                        //if user not verified throw error -- You are not verified. Please check your email and complete verification.
                        //$user->addError('email',"You are not verified. Please check your email to complete verification.");
                        Yii::app()->user->setFlash('error', 'You are not verified. Please check your email to complete verification.');
                    }
                } else {
                    //if not a valid email -- display error - Invalid email provided.
                    //$user->addError('email',"Invalid email address provided.");
                    //print_r($user); exit;
                    Yii::app()->user->setFlash('error', 'Invalid email address provided.');
                }
            }
        }
        $this->page_name = 'password-reset';
        $this->render($this->page_name, array(
            'model' => isset($user) ? $user : new User,
            'page_name'=>$this->page_name,
            'nav' => $this->getNav(),
            'siteParams' => $this->getSiteParams(),
            'widget' => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),
        ));
    }

    public function randomNumber($digits){
        $alphaString = "ABCDEDGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";
        for($i=0; $i < $digits; $i++){
            $string .= $alphaString[rand(0,strlen($alphaString)-1)];
        }
        return $string;
    }


    public function actionCreatecaptcha(){

        //Let's generate a totally random string using md5
        $md5_hash = md5(rand(0,999));
        //We don't need a 32 character long string so we trim it down to 5
        $security_code = substr($md5_hash, 15, 5);

        //Set the session to store the security code
        if(isset(Yii::app()->session["security_code"])) {
            unset(Yii::app()->session["security_code"]);
        }
        Yii::app()->session["security_code"] = $security_code;

        //putting it also in the tmp params array
        Yii::app()->params['security_code'] = $security_code;

        //Set the image width and height
        $width = 251;
        $height = 79;

        //Create the image resource
        $image = ImageCreate($width, $height);

        //We are making three colors, white, black and gray
        $white = ImageColorAllocate($image, 255, 255, 255);
        $black = ImageColorAllocate($image, 0, 0, 0);
        $grey = ImageColorAllocate($image, 204, 204, 204);

        //Make the background black
        ImageFill($image, 0, 0, $black);

        //Add randomly generated string in white to the image
        ImageString($image, 30, 40, 25, $security_code, $white);

        //Throw in some lines to make it a little bit harder for any bots to break
        ImageRectangle($image,0,0,$width-1,$height-1,$grey);
        imageline($image, 0, $height/rand(2,6), $width, $height/2, $grey);
        imageline($image, $width/rand(2,6), 0, $width/rand(2,6), $height, $grey);

        //Tell the browser what kind of file is come in
        header("Content-Type: image/jpeg");

        //Output the newly created image in jpeg format
        ImageJpeg($image);

        //Free up resources
        ImageDestroy($image);

        exit;
    }

    public function actionCheckcaptcha () {

        if(isset($_GET['security_code']) && !empty($_GET['security_code'])) {
            //check if the session exist and then validate it
            $cacheCode = '';
            //if (isset(Yii::app()->params['security_code'])){
            //    $cacheCode = Yii::app()->params['security_code'];
            //} else
            if (isset(Yii::app()->session['security_code'])){
                $cacheCode = Yii::app()->session['security_code'];
            }

            Yii::log('cached code for Captcha :'.$cacheCode);

            if (!empty($cacheCode)){
                if ($_GET['security_code'] == $cacheCode) {
                    echo "success";
                } else {
                    Yii::log('Unable to validate captcha Code:'.$_GET['security_code'].' Cached: '.$cacheCode);
                    echo "failure";
                }
            } else {
                Yii::log('Captcha is Empty:'.$_GET['security_code'].' Cached: '.$cacheCode);
                echo "success";
            }
            Yii::app()->end();
        } else {
            Yii::log('Incomplete params recieved '.json_encode($_GET));
            echo "failure";
        }
    }
}
