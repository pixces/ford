<?php

class UgcController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';
    public $error = false;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {

        return array(
                /*
                  array('allow',  // allow all users to perform 'index' and 'view' actions
                  'actions'=>array('index','view'),
                  'users'=>array('*'),
                  ),
                  array('allow', // allow authenticated user to perform 'create' and 'update' actions
                  'actions'=>array('submission','update','getSocialPhotos','load_more_photos'),
                  'users'=>array('@'),
                  ),
                  array('allow', // allow admin user to perform 'admin' and 'delete' actions
                  'actions'=>array('admin','delete'),
                  'users'=>array('admin@cnk.com'),
                  ),
                  array('deny',  // deny all users
                  'users'=>array('*'),
                  ), */
        );
    }

    /**
     * 1. Check if User is Loggedin
     * 2. If any of the users submission is submited for APPoval
     *    -- redirect to user/profile page
     * 3. Else show the page and ask for submission
     *    a. Get all the content for this user & display
     */
    public function actionSubmission() {

        //check if already loggedIn else redirect to login page
        if (Yii::app()->user->isGuest) {
            //redirect the user to the
            $redirectUrl = Yii::app()->createAbsoluteUrl("user/login", $this->getSiteParams());
            $this->redirect($redirectUrl);
            Yii::app()->end();
        }


        try {

            $currentUser = Yii::app()->user->getId();
            $maxUploadAllowed = Yii::app()->params['MAX_USER_UPLOAD'];
            $ugcGalleryId = Yii::app()->params['ugcGalleryId'];
            $userSubmissions = 0;
            $submittedForApproval = 0;

            $this->page_name = 'submission';

            $model = new Content;
            $response = array();

            if (!empty($_POST)) {
                //sanitize the data not in case od submit for approval
                $msgType = $errorMessage = '';
                $uploadType = $_POST['type'];
                // this is for the edit part .....
                if (isset($_POST['content-id']) && !empty($_POST['content-id'])) {
                    $contentId = $_POST['content-id'];
                    //$this->model=  Content::model()->findByPk($_POST['content-id']);exit;
                    $criteria = new CDbCriteria;
                    $criteria->compare('id', $contentId);
                    $model = Content::model()->find($criteria);
                    $model->title = $_POST['title'];
                    $model->description = $_POST['description'];
                    $model->location = $_POST['locations'];
                    if ($model->save()) {
                        $msgType = 'success';
                        $errorMessage = 'Updated Successful';
                    } else {
                        $msgType = 'error';
                        $errorMessage = 'error while uploading,try again..';
                    }
                    echo $errorMessage;
                    exit;
                }
                // Fetch gallery id
                //            $galleries = new Galleries;
                //            $activeGallery = $galleries->find("status = 'active' and is_ugc=1");
                $galleryId = Yii::app()->params['ugcGalleryId'];
                $model->gallery_id = $galleryId; //$activeGallery->id; //$activeGallery->id;
                $model->user_id = Yii::app()->user->id;
                $model->is_ugc = 1;
                //

                if ($uploadType == 'submit-approval') {
                    // set the is_submitted to 1 
                    $criteria = new CDbCriteria;
                    $criteria->addCondition('is_ugc=1');
                    $criteria->addCondition('user_id=' . $model->user_id);
                    $criteria->addCondition('gallery_id=' . $galleryId);
                    Content::model()->updateAll(array('is_submitted' => 1, 'status' => 'under_review'), $criteria);

                    //redirect the user to the profile page.
                    $redirectUrl = Yii::app()->createAbsoluteUrl("user/profile", $this->getSiteParams());
                    $this->redirect($redirectUrl);
                    exit;
                } else {
                    $criteria = new CDbCriteria;
                    $criteria->addCondition('is_ugc=1');
                    $criteria->addCondition('user_id=' . Yii::app()->user->id);
                    $contentUploadCount = Content::model()->count($criteria);

                    if ($contentUploadCount < $maxUploadAllowed) {
                        // validate the data;
                        $validateFlag = $this->validateUserData($_POST);
                        if ($validateFlag) {

                            if ($uploadType == 'blog') {

                                $model->title = $_POST['title'];
                                $model->description = $_POST['description'];
                                $model->location = $_POST['locations'];
                                $model->type = $uploadType;

                                if ($model->save()) {
                                    $msgType = 'success';
                                    $errorMessage = 'Upload Successful';
                                } else {
                                    $msgType = 'error';
                                    $errorMessage = 'error while uploading,try again..';
                                }
                                echo $errorMessage;
                                exit;
                            } else {
                                //social submission
                                if (isset($_POST['social_photo']) && !empty($_POST['social_photo'])) {
                                    //social image data save ;
                                    $model->title = $_POST['title'];
                                    $model->description = $_POST['description'];
                                    $model->location = $_POST['locations'];
                                    $model->thumb_image = $_POST['social_photo'];
                                    $model->media_url = $_POST['social_photo'];
                                    $model->type = $uploadType;
                                    if ($model->save()) {
                                        $msgType = 'success';
                                        $errorMessage = 'Upload Successful';
                                    } else {
                                        print_r($model->getErrors());
                                        $msgType = 'error';
                                        $errorMessage = 'error while uploading,try again..';
                                    }
                                } else {
                                    //ugc submission
                                    $name = $_FILES[$uploadType]['name'];
                                    $size = $_FILES[$uploadType]['size'];
                                    $tmp = $_FILES[$uploadType]['tmp_name'];

                                    $filename = strtoupper($name);
                                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                    $extensions = $uploadType . 'Extensions';
                                    if ($uploadType == 'file')
                                        $maxSize = $ext . 'MaxSize';
                                    else
                                        $maxSize = $uploadType . 'MaxSize';

                                    $allowed = Yii::app()->params[$extensions];
                                    $size = Yii::app()->params[$maxSize];
                                    $maxSize = $size * 1024 * 1024;
                                    if (in_array($ext, $allowed)) {
                                        if ($size <= $maxSize) {
                                            $actual_file_name = 'season1/' . $uploadType . '/' . time() . "." . $ext;

                                            if ($uploadType == 'file')
                                                $model->type = strtolower($ext);
                                            else
                                                $model->type = $uploadType;
                                            //check if the content type is image/photo
                                            //thumb image
                                            if ($uploadType == 'image') {
                                                $thumbImageName = ImageUpload::uploadImage($model, 'image', 125, 90, 'contentImageName', 'thumb');
                                                $model->thumb_image = $thumbImageName;
                                            }


                                            require_once Yii::app()->basePath . '/vendor/S3Folder/s3_config.php';

                                            if ($s3->putObjectFile($tmp, $bucket, $actual_file_name, S3::ACL_PUBLIC_READ)) {
                                                $s3file = 'http://' . $bket . '.s3.amazonaws.com/' . $actual_file_name;
                                                $model->title = $_POST['title'];
                                                $model->description = $_POST['description'];
                                                $model->location = $_POST['locations'];
                                                $model->media_url = $s3file;

                                                if ($model->save()) {
                                                    $msgType = 'success';
                                                    $errorMessage = 'Upload Successful';
                                                } else {
                                                    $msgType = 'error';
                                                    $errorMessage = 'error while uploading,try again..';
                                                }
                                            }
                                        } else {
                                            $msgType = 'error';
                                            $errorMessage = ' File size exceeds ' . $size . ' MB';
                                        }
                                    } else {
                                        $msgType = 'error';
                                        $errorMessage = 'Check the video file extension';
                                    }
                                }
                            }
                        } else {
                            $msgType = 'error';
                            $errorMessage = 'Data type is wrong...';
                        }
                    } else {
                        $msgType = 'error';
                        $errorMessage = 'Upload Limit Exceeded';
                    }
                }
                $response['state'] = $msgType;
                $response['message'] = $errorMessage;
                echo json_encode($response);
                exit;
            }//echo '<pre>';print_r($_REQUEST);exit;
            //get all entries by the selected user
            //from UGC Gallery, Status = pending &
            //echo $ugcGalleryId."---".$currentUser;
            $criteria = new CDbCriteria;
            $criteria->compare('is_ugc', 1);
            $criteria->compare('gallery_id', $ugcGalleryId);
            $criteria->compare('user_id', $currentUser);
            $content = Content::model()->findAll($criteria);

            if ($content) {
                //check if there is any entry which has already been submitted for approval
                foreach ($content as $item) {
                    //do this later
                    if ($item->is_submitted == 1) {
                        $submittedForApproval++;
                    }
                    $userSubmissions++;
                }
            }

            $pendingUploads = $maxUploadAllowed - $userSubmissions;

            if ($submittedForApproval > 0) {
                //atleast one of the submissions are sent for approval
                //redirect the user to the profile page
                $this->redirect($this->createAbsoluteUrl('user/profile'));
                Yii::app()->end();
            }
            //display the current submissionpage
            $this->render($this->page_name, array(
                'content' => $content,
                'response' => $response,
                'page_name' => $this->page_name,
                'submission' => $userSubmissions,
                'pendingUploads' => $pendingUploads,
                'nav' => $this->getNav(),
                'site' => $this->getSite(),
                'lang' => $this->getLang(),
                'env' => $this->getEnv(),
                'widget' => array(
                    'partners' => $this->getSitePartners(),
                    'footer' => $this->getSiteFooter(),
                ),
                'siteParams' => $this->getSiteParams()
            ));
            Yii::app()->end();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Content;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Content'])) {
            $model->attributes = $_POST['Content'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        echo 'Deleted The Data';
        exit;
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Content');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Content('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Content']))
            $model->attributes = $_GET['Content'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Content the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Content::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Content $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'content-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
