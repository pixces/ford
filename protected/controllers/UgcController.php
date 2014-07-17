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

        $user = Yii::app()->user->getId();
        $ugcGalleryId = Yii::app()->params['ugcGalleryId'];
        $userSubmissions = 0;
        $submittedForApproval = 0;
        $this->page_name = 'submission';

        $model = new Content;
        $response = array();

        $criteria = new CDbCriteria;
        $criteria->compare('is_ugc', 1);
        $criteria->compare('gallery_id', $ugcGalleryId);
        $criteria->compare('user_id', $user);
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
        $maxUploadAllowed = 0;
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

    public function getContent(){

    }

    /**
     * Must be an ajax call
     * Call to post content to the database
     */
    public function postContent(){
        //make sure this is an ajax call
        //if not user loggedin... return error
        if (Yii::app()->user->isGuest) {
            //redirect the user to the
            echo "Not loggedIn";
            Yii::app()->end();
        }

        //prepare content params
        $content['user_id'] = Yii::app()->user->getId();
        $content['channel_name'] = $_POST['celeb'];
        $content['title'] = $content['description'] = $this->sanitizeData($_POST['comment']);
        $content['gallery_id'] = Yii::app()->params['ugcGalleryId'];
        $content['type'] = 'text';
        $content['is_ugc'] = 1;

        //send data to post the content
        $result = Yii::app()->services->performRequest('/content',$content,'POST')->getResponseData(true);
        echo $result;
        Yii::app()->end();
    }

}
