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

        $userId = Yii::app()->user->getId();
        $ugcGalleryId = Yii::app()->params['ugcGalleryId'];
        $content = Yii::app()->params['celebrity'];
        $userSubmissions = 0;
        $submittedForApproval = 0;
        $this->page_name = 'submission';

        //get the contents for the selected user
        $aParams = array(
            'user_id' => $userId,
            'gallery_id' => $ugcGalleryId,
            'is_ugc' => 1
        );

        $result = Yii::app()->services->performRequest('/content',$aParams,'GET')->getResponseData(true);

        if ($result){
            foreach($result as $item){
                $content[$item['channel_name']] = array_merge($content[$item['channel_name']],$item);
                if ($item['is_submitted'] == 1 ){
                    $submittedForApproval ++;
                }
                $userSubmissions++;
            }
        }

        $maxUploadAllowed = 3;
        $pendingUploads = $maxUploadAllowed - $userSubmissions;

        if ($submittedForApproval > 0) {
            //at-least one of the submissions are sent for approval
            //redirect the user to the profile page
            $this->redirect($this->createAbsoluteUrl('user/profile'));
            Yii::app()->end();
        }
        //display the current submissionpage
        $this->render($this->page_name, array(
            'content' => $content,
            'page_name' => $this->page_name,
            'submission' => $userSubmissions,
            'pendingUploads' => $pendingUploads,
            'userSubmissions' => $userSubmissions,
            'submittedForApproval' => $submittedForApproval,
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
     * action Save
     * saves the content to the database
     * based on the values passed
     * $user_id -> loggedIn user
     * $comment -> content text (title / description)
     * $channel -> celebrity name
     * $is_ugc
     */
    public function actionSave(){
        //return error if not a loggedin User
        if (Yii::app()->user->isGuest) {
            //throw error
            $this->_sendResponse(200,json_encode(array('response'=>'false','message'=>'Not Logged In.')));
            Yii::app()->end();
        }

        if($_POST){
            if ($_POST['user_id'] == Yii::app()->user->getId()){

                $params = array();
                $params['user_id'] = $_POST['user_id'];
                $params['channel_name'] = $_POST['channel'];
                $params['title'] = $params['description'] = $this->sanitizeData($_POST['comment']);
                $params['gallery_id'] = Yii::app()->params['ugcGalleryId'];
                $params['type'] = 'text';
                $params['is_ugc'] = isset($_POST['is_ugc']) ? $_POST['is_ugc'] : 1 ;

                $result = $this->postContent($params);

                if ($result['id']){
                    //data successfully submitted
                    echo CJavaScript::jsonEncode(array('response'=>'success','message'=>'Content submitted.'));
                } else {
                    echo CJavaScript::jsonEncode(array('response'=>'false','message'=>$result));
                }
            } else {
                echo CJavaScript::jsonEncode(array('response'=>'false','message'=>'Loggin session has expired. Please login again'));
            }
        } else {
            echo CJavaScript::jsonEncode(array('response'=>'false','message'=>'Invalid request method found.'));
        }
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

    /**
     * Must be an ajax call
     * Call to post content to the database
     */
    public function postContent($data){
        //send data to post the content
        $result = Yii::app()->services->performRequest('/content',$data,'POST')->getResponseData(true);
        return $result;
        Yii::app()->end();
    }

}
