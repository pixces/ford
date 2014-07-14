<?php

class SocialAccountController extends Controller
{
	public function actionIndex()
	{
//        echo "<pre>";
//        print_r($_REQUEST);

        if (!isset($_GET['provider']))
        {
//            $this->redirect('/site/index');
//            return;
        }

        try
        {

            /*
            Yii::import('ext.components.HybridAuthIdentity');
            $haComp = new HybridAuthIdentity();
            if (!$haComp->validateProviderName($_GET['provider']))
                throw new CHttpException ('500', 'Invalid Action. Please try again.');

            $haComp->adapter = $haComp->hybridAuth->authenticate($_GET['provider']);
            $haComp->userProfile = $haComp->adapter->getUserProfile();

            $haComp->processLogin();  //further action based on successful login or re-direct user to the required url
            */
        }
        catch (Exception $e)
        {
//            echo "<pre>";
//            print_r($e);
            //process error message as required or as mentioned in the HybridAuth 'Simple Sign-in script' documentation
//            $this->redirect('/site/index');
            return;
        }
		$this->render('index');
	}

    public function actionSocialLogin()
    {

        Yii::import('application.components.HybridAuthIdentity');
        $path = Yii::getPathOfAlias('ext.HybridAuth');
        require_once $path . '/hybridauth-' . HybridAuthIdentity::VERSION . '/hybridauth/index.php';

    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

    /**
     * Authenticte action
     */
    public function actionAuthenticate(){
        if (!isset($_GET['provider']))
        {
            $this->redirect('/site/index');
            return;
        }

        if (isset($_GET['return']))
        {
            Yii::app()->session['return']  = $_GET['return'];
        }


        Yii::import('application.components.HybridAuthIdentity');
        $haComp = new HybridAuthIdentity();


        if (!$haComp->validateProviderName($_GET['provider']))
            throw new CHttpException ('500', 'Invalid Action. Please try again.');

        $haComp->adapter = $haComp->hybridAuth->authenticate($_GET['provider']);
        $haComp->userProfile = $haComp->adapter->getUserProfile();

        if(Yii::app()->session['return']=="create_user"){
//            echo Yii::app()->baseUrl; exit;
            $this->redirect(Yii::app()->baseUrl.'/index.php?r=user/create/social/'.$_GET['provider']);
            return;
        }


    }

}