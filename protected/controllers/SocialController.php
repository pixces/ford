<?php


class SocialController extends Controller
{
	public $allowedMedia = array('google','facebook','twitter','instagram','flickr');

    //make sure to hide the layout
    public $layout = false;

    private $connection = null;

    private $fb = null;
    private $gg = null;
    private $yt = null;
    private $gp = null;
    private static $tw = null;
    private $ig = null;
    private $fr = null;

    private $callbackURL;
    //private $callbackURL = "http://localhost:8888/cnk/social/authenticate";
    //private $callbackURL = "http://cnk.position2.com/social/authenticate?media=google";

    public function beforeAction($action){
        return true;
    }

    public function __construct(){
        $this->callbackURL = Yii::app()->params['APP_CALLBACK'];
    }



    /**
     * Subscribe Action
     * AJAX call is received here
     * API call to ADMIN is made to post data
     */
    public function actionSubscription(){
        if($_POST){
            if ($_POST['name'] && $_POST['email']){
                $_POST['user_ip'] = isset($_POST['user_ip']) ? $_POST['user_ip'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

                //send this post to the backend to save
                $response = Yii::app()->services->performRequest('/subscription',$_POST,'POST')->getResponseData(true);
                if ($response['id']){
                    //data successfully submited
                    $this->_sendResponse(200,json_encode(array('response'=>'success','message'=>'Subscription submitted.')));
                } else {
                    $this->_sendResponse(200,json_encode(array('response'=>'false','message'=>'You are already subscribed to this page.')));
                }
            } else {
                $this->_sendResponse(200,json_encode(array('response'=>'false','message'=>'Name & Email are mandatory.')));
            }
        } else {
            $this->_sendResponse(200,json_encode(array('response'=>'false','message'=>'Invalid request method found.')));
        }
        Yii::app()->end();
    }


    /**
     * Social Post Action
     * UI calls this function to post values
     * SOCIAL POST api is called to make actual submition to the database
     */
    public function actionSocialPost(){

        if($_POST){
            if(isset($_POST['post_text']) && isset($_POST['source'])){

                $response = Yii::app()->services->performRequest('/socialPost',$_POST,'POST')->getResponseData(true);

                //we will get the primary Id in the posted model
                if ($response['id']){
                    $message = "Post Published. Will be displayed after moderation.";
                } else {
                    $message = "Unable to post. Please try again";
                }
                $this->_sendResponse(200, CJSON::encode($message));
            } else {
                $this->_sendResponse(401, 'Error: Invalid parameters.');
            }
        } else {
            $this->_sendResponse(401,'Invalid request method. Only POST allowed');
        }
        Yii::app()->end();
    }


    public function actionAuthenticate()
	{
        if (!$_GET['media'] || !in_array($_GET['media'],$this->allowedMedia)){
            //throw message that you cannot access this page
            //directly.
        }
        if(isset($_GET['type']) && $_GET['type'] == 'login')
        {
            $_SESSION['type'] = 'login';
        }        
        call_user_func(array($this, 'login_'.$_GET['media']));
    }

    public function login_facebook(){
        $user = $this->validate_user('facebook');

        if ($user) {
            try {
            //Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $this->getFacebook()->api('/me');

            $media = 'facebook';
            if ($user_profile){
                $profile_photo = "https://graph.facebook.com/".$user_profile['id']."/picture";
                $email  = isset($user_profile['email']) ? $user_profile['email'] : null;
                $type = isset(Yii::app()->session['type'])?Yii::app()->session['type']:"authenticate";
                if($type == "register"){
//                        $media = 'facebook';
//                        $_SESSION['media'] = $media;
//                        $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                } else if($type == "submission"){

//                        $media = 'facebook';
//                        $_SESSION['media'] = $media;
                    $accessToken = $this->getFacebook()->getAccessToken();
                    //print_r($accessToken);
//                        $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                    $_SESSION[$media]['session'] = $accessToken;
                } else if($type == 'login') {
//                        $media = 'facebook';
//                        $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                    $this->actionCheckSocialLoginEmail($email, $media);

                } else {
                    $auth = array();
                    $auth['social'] = 'facebook';
                    $auth['identifier'] = $user_profile['id'];
                    $auth['display_name'] = $user_profile['name'];
                    $auth['first_name'] = isset($user_profile['first_name'])?$user_profile['first_name']:"";
                    $auth['last_name'] =  isset($user_profile['last_name'])?$user_profile['last_name']:"";
                    $auth['profile_url'] = $user_profile['link'];
                    $auth['profile_photo'] = $profile_photo;
                    $auth['email'] = $email;
                    $auth['location'] = isset($user_profile['location']) ? $user_profile['location']['name'] : null;
                    $auth['access_token'] = $_SESSION['fb_'.Yii::app()->params['FB_APP_ID'].'_access_token'];
                    $auth['access_secret'] = $_SESSION['fb_'.Yii::app()->params['FB_APP_ID'].'_code'];;
                    $auth['token_expiry'] = null;
                    $auth['date_added'] = date('Y-m-d h:i:s');

                    //saving data to database
                    $this->saveAuthInfo($auth);


                    if($type=="vote" && isset($_SESSION['content_id'])){
                        $logMessage = "Type is vote, and processing the voting functionality for content Id :".$_SESSION['content_id']." and media detail : ".serialize($_SESSION[$media]);
                        Yii::log($logMessage);
                        $this->__vote($_SESSION['content_id'],$media,$_SESSION[$media]);
                    }


                }

                $_SESSION['media'] = $media;
                $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                $_SESSION[$media]['user_id'] = $user_profile['id'];
                $_SESSION[$media]['user_name'] = $user_profile['name'];
                $_SESSION[$media]['appId'] = Yii::app()->params['FB_APP_ID'];
                $_SESSION[$media]['image'] = $profile_photo;
                $_SESSION[$media]['displayName'] = $user_profile['username'];
                $_SESSION[$media]['email'] = $email;

                //close this page now
                echo "<script>javascript:window.close(); </script>";

                exit;
            }
            //close this page now
            echo "<script>javascript:window.close(); </script>";
            exit;
            //get the logout url
            } catch(Exception $e){
                //Error occured, close the window
                $logMessage = serialize($e);
                Yii::log($logMessage);
                echo "<script>javascript:window.close(); </script>";
                exit;
            }
        } else {
            //redirect to the login
            $params['scope'] = array('read_stream','publish_stream','email','user_photos');
            $params['display'] = "popup";
            $loginUrl = $this->getFacebook()->getLoginUrl($params);

            Yii::app()->session['type'] = isset($_GET['type']) ? $_GET['type'] : "authenticate";

            if(isset($_GET['content_id']) && is_numeric($_GET['content_id']))
                $_SESSION['content_id'] = $_GET['content_id'];

            if ($loginUrl){
                header("location:".$loginUrl);
                exit;
            }
        }
    }

    public function login_twitter(){
        try {
            if(isset($_REQUEST['oauth_verifier'])){

                //create new tw connection usinh the credentials from session;
                $this->setTwitter(Yii::app()->session['oauth_token'], Yii::app()->session['oauth_token_secret']);
                $access_token = $this->getTwitter()->getAccessToken($_REQUEST['oauth_verifier']);

                $_SESSION['access_token'] = $access_token;
                Yii::app()->session['access_token'] = $access_token;

                /* If HTTP response is 200 continue otherwise send to connect page to retry */
                if (200 == $this->getTwitter()->http_code) {
                    /* The user has been verified and the access tokens can be saved for future use */
                    Yii::app()->session['twitter_status'] = 'verified';
                    $user_profile = $this->getTwitter()->get("account/verify_credentials");

                    $media = "twitter";

                    //print_r($user_profile); exit;
                    $type = Yii::app()->session['type'];

                    if(isset($type) && $type == "register"){
    //                    $media = 'twitter';
    //                    $_SESSION['media'] = $media;
    //                    $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                    } else {
                        $auth = array();
                        $auth['social'] = 'twitter';
                        $auth['identifier'] = $user_profile->id;
                        $auth['display_name'] = $user_profile->screen_name;
                        $auth['last_name'] = '';
                        $name = explode(" ",$user_profile->name);
                        if(is_array($name)) {
                            $auth['first_name'] = isset($name[0])?$name[0]:"";
                            $auth['last_name'] =  isset($name[1])?$name[1]:"";
                        }
                        $auth['first_name'] = $user_profile->name;                    
                        $auth['profile_url'] = "https://twitter.com/".$user_profile->screen_name;
                        $auth['profile_photo'] = $user_profile->profile_image_url;
                        $auth['email'] = isset($user_profile->email) ? $user_profile->email : null;
                        $auth['location'] = isset($user_profile->location) ? $user_profile->location : null;
                        $auth['access_token'] = Yii::app()->session['access_token']['oauth_token'];
                        $auth['access_secret'] = Yii::app()->session['access_token']['oauth_token_secret'];
                        $auth['token_expiry'] = null;
                        $auth['date_added'] = date('Y-m-d h:i:s');

                        //saving data to database
                        $this->saveAuthInfo($auth);


                        if(Yii::app()->session['type']=="vote" && isset(Yii::app()->session['content_id'])){
                            $logMessage = "Twitter :: Type is vote, and processing the voting functionality for content Id :".Yii::app()->session['content_id']." and media detail : ".serialize($_SESSION[$media]);
                            Yii::log($logMessage);
                            $this->__vote(Yii::app()->session['content_id'],$media,Yii::app()->session[$media]);
                        }
                    
                    }
                    Yii::app()->session['media'] = $media;

    //                    $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                    $tempSessionArray = array(
                        'user_id' => $user_profile->id,
                        'user_name' => $user_profile->name,
                        'image' => $user_profile->profile_image_url);
                    $sessionArray = array_merge($tempSessionArray,$this->__parseSocialProfileInfo($media,$user_profile));
                    Yii::app()->session['twitter']= $sessionArray;
                    //close this page now
                    echo "<script>javascript:window.close(); </script>";
                    exit;
                }

            } else {
                /* Get temporary credentials. */
                $twitter = $this->getTwitter();
                $twitterCallbackUrl = $this->callbackURL."?media=twitter&debug";
                $request_token = $twitter->getRequestToken($twitterCallbackUrl);

                /* Save temporary credentials to session. */
    //            Yii::app()->session['type'] =  isset($_GET['type']) && $_GET['type']=="register" ? "register" : "authenticate";
                Yii::app()->session['type'] = isset($_GET['type']) ? $_GET['type'] : "authenticate";

                if(isset($_GET['content_id']) && is_numeric($_GET['content_id']))
                    Yii::app()->session['content_id'] = $_GET['content_id'];

                Yii::app()->session['oauth_token'] = $token = $request_token['oauth_token'];
                Yii::app()->session['oauth_token_secret'] = $request_token['oauth_token_secret'];

                /* If last connection failed don't display authorization link. */
                switch ($twitter->http_code) {
                    case 200:
                        /* Build authorize URL and redirect user to Twitter. */
                        $url = $twitter->getAuthorizeURL($request_token['oauth_token']);
                        header('Location: ' . $url);
                        break;
                    default:
                        /* Show notification if something went wrong. */
                        echo 'Could not connect to Twitter. Refresh the page or try again later.';
                }
            }
        } catch(Exception $e){
            //Error occured, close the window
            $logMessage = serialize($e);
            Yii::log($logMessage);
            echo "<script>javascript:window.close(); </script>";
            exit;
        }
    }

    public function login_google(){
        try {
    		if(!empty(Yii::app()->session['google'])) {
    			// User info present in the session, close the window
                $media = "google";
                if(Yii::app()->session['type']=="vote" && isset(Yii::app()->session['content_id'])){
                    $logMessage = "Youtube :: Type is vote, and processing the voting functionality for content Id :".Yii::app()->session['content_id']." and media detail : ".serialize(Yii::app()->session[$media]);
                    Yii::log($logMessage);
                    $this->__vote(Yii::app()->session['content_id'],$media,Yii::app()->session[$media]);
                }
                $logMessage = "Google session exists so cleoing the window";
                Yii::log($logMessage);
    			echo "<script>javascript:window.close(); </script>";
    			exit;
    		}
            $client = $this->getGoogle();

            if (isset($_GET['code'])) {
    			$logMessage = "Received response code from google.";
    			Yii::log($logMessage);
                $client->authenticate($_GET['code']);
                $token = $client->getAccessToken();
                $client->setAccessToken($token);
                $session = Yii::app()->session;
                $session['gplus']  = array('access_token' => $client->getAccessToken());
    			$logMessage = "Stored google access token in session.";
    			Yii::log($logMessage);
                //$client->getAccessToken()
    //                    print_r($_SESSION['upload_token']); exit;
                $plus = $this->getGooglePlus();
                $user_profile = $plus->people->get('me');
                
    			$logMessage = "Received google plus's usre profile, details: .".serialize($user_profile);
    			Yii::log($logMessage);

                if($user_profile){

                    $type = Yii::app()->session['type'];
                    $media = "google";
                    if(isset($type) && $type == "register"){
    //                    $media = 'google';
    //                    $_SESSION['media'] = $media;
    //                    $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                    }  else if($type == 'login') {
    //                        $media = 'google';
                            $email  = isset($user_profile['email']) ? $user_profile['email'] : null;
    //                        $_SESSION[$media] = $this->__parseSocialProfileInfo($media,$user_profile);
                            $this->actionCheckSocialLoginEmail($email, $media);
                           
                    } else {
                        try {
                            $tokenInfo = json_decode(Yii::app()->session['gplus']['access_token'],true);

                            //print_r($user['data']); exit;

                            $auth = array();
                            $auth['social'] = 'google';
                            $auth['identifier'] = $user_profile->id;
                            $auth['display_name'] = $user_profile->displayName;
                            $name = explode(" ",$user_profile->displayName);
                            if(is_array($name)){
                                $auth['first_name'] = isset($name[0])?$name[0]:"";
                                $auth['last_name'] =  isset($name[1])?$name[1]:"";
                            }
                            $auth['profile_url'] = $user_profile->url;
                            $auth['profile_photo'] = $user_profile['data']['image']['url'];
                            $auth['email'] = isset($user_profile['data']['emails'][0]['value']) ? $user_profile['data']['emails'][0]['value'] : null;
                            $auth['location'] = isset($user_profile->currentLocation) ? $user_profile->currentLocation : null;
                            $auth['access_token'] = $tokenInfo['access_token'];
                            $auth['access_secret'] = $tokenInfo['id_token'];
                            $auth['token_expiry'] = $tokenInfo['expires_in'];
                            $auth['date_added'] = date('Y-m-d h:i:s');

                            // saving data to database
                            $this->saveAuthInfo($auth);

                            if(Yii::app()->session['type']=="vote" && isset(Yii::app()->session['content_id'])){
                                $logMessage = "Youtube :: Type is vote, and processing the voting functionality for content Id :".Yii::app()->session['content_id']." and media detail : ".serialize($_SESSION[$media]);
                                Yii::log($logMessage);
                                $this->__vote(Yii::app()->session['content_id'],$media,Yii::app()->session[$media]);
                            }
                                
                            if($type == 'login') {
                                $this->actionCheckSocialLoginEmail($auth['email'], 'google');
                            }

                        } catch (FacebookApiException $e) {
                            error_log($e);
                            $user = null;
                        }
                    }

                    Yii::app()->session['media'] = 'google';

                    $tempSessionArray = array(
                        'user_id' => $user_profile->id,
                        'user_name' => $user_profile->displayName,
                        'image' => $user_profile['data']['image']['url']);
                    $sessionArray = array_merge_recursive($tempSessionArray,$this->__parseSocialProfileInfo($media,$user_profile));
                    Yii::app()->session['google']= $sessionArray;

                    echo "<script>javascript:window.close(); </script>";
                    exit;
                }

            } else if (isset(Yii::app()->session['gplus']['access_token']) && Yii::app()->session['gplus']['access_token']) {
                $client->setAccessToken(Yii::app()->session['gplus']['access_token']);
                if ($client->isAccessTokenExpired()) {
                    unset(Yii::app()->session['gplus']['access_token']);
                }
    			// Access token present, close the window
    			echo "<script>javascript:window.close(); </script>";
    			exit;
            } else {

                $client->addScope("https://www.googleapis.com/auth/youtube.readonly");
                $client->addScope("https://www.googleapis.com/auth/userinfo.email");
                $client->addScope("https://www.googleapis.com/auth/plus.profile.emails.read");

                $authUrl = $client->createAuthUrl();

    //            Yii::app()->session['type'] = isset($_GET['type']) && $_GET['type']=="register" ? "register" : "authenticate";
                Yii::app()->session['type'] = isset($_GET['type']) ? $_GET['type'] : "authenticate";

                if(isset($_GET['content_id']) && is_numeric($_GET['content_id']))
                    Yii::app()->session['content_id'] = $_GET['content_id'];
                header("location:$authUrl");
            }
        } catch(Exception $e){
            //Error occured, close the window
            $logMessage = serialize($e);
            Yii::log($logMessage);
            echo "<script>javascript:window.close(); </script>";
            exit;
        }

    }


    public function login_instagram(){
        try {
            if(!empty(Yii::app()->session['instagram'])) {
                // User info present in the session, close the window
                echo "<script>javascript:window.close(); </script>";
                exit;
            }
            $ig = $this->getInstagram();

            if (isset($_GET['code'])) {

                $logMessage = "Hurray we got the code from instagram";
                Yii::log($logMessage);

                // receive OAuth token object
                $data = $ig->getOAuthToken($_GET['code']);

                $user_profile = $data->user;

                $logMessage = "Prepare user profile data :".serialize($user_profile);
                Yii::log($logMessage);

                $accessToken = $data->access_token;

                // store user access token
                $ig->setAccessToken($accessToken);

                $media = 'instagram';

                if($user_profile){

                    $type = Yii::app()->session['type'];
                    //if(isset($type) && $type == "submission"){

                        $logMessage = "type is submission";
                        Yii::log($logMessage);

                        $sessionData = array_merge($this->__parseSocialProfileInfo($media,$user_profile),array("access_token"=>$accessToken));
                        //$sessionData[$media]['access_token'] = $accessToken;

                        $logMessage = "Prepare the session data to save, details :".serialize($sessionData);
                        Yii::log($logMessage);

                        Yii::app()->session['media'] = "instagram";
                        Yii::app()->session[$media] = $sessionData;
                    //}
                    /* else {
                        try {
                            $tokenInfo = json_decode(Yii::app()->session['instagram']['access_token'],true);

                            //print_r($user['data']); exit;

                            $auth = array();
                            $auth['social'] = 'insgtagram';
                            $auth['identifier'] = $user_profile->id;
                            $auth['display_name'] = $user_profile->displayName;
                            $name = explode(" ",$user_profile->displayName);
                            $auth['first_name'] = $name[0];
                            $auth['last_name'] =  $name[1];
                            $auth['profile_url'] = $user_profile->url;
                            $auth['profile_photo'] = $user_profile['data']['image']['url'];
                            $auth['email'] = isset($user_profile['data']['emails'][0]['value']) ? $user_profile['data']['emails'][0]['value'] : null;
                            $auth['location'] = isset($user_profile->currentLocation) ? $user_profile->currentLocation : null;
                            $auth['access_token'] = $tokenInfo['access_token'];
                            $auth['access_secret'] = $tokenInfo['id_token'];
                            $auth['token_expiry'] = $tokenInfo['expires_in'];
                            $auth['date_added'] = date('Y-m-d h:i:s');

                            // saving data to database
                            $this->saveAuthInfo($auth);

                            Yii::app()->session['media'] = $media;
                            Yii::app()->session[$media]= array(
                                'user_id' => $auth['identifier'],
                                'user_name' => $auth['display_name'],
                                'image' => $auth['profile_photo']);

                            //close this page now
                            echo "<script>javascript:window.close(); </script>";
                            exit;

                        } catch (Exeption $e) {
                            error_log($e);
                            $user = null;
                        }
                    }    */
                    echo "<script>javascript:window.close(); </script>";
                    exit;
                }

            } else {

                Yii::app()->session['type'] = isset($_GET['type']) ? $_GET['type']  : "authenticate";

                $loginUrl = $ig->getLoginUrl();
                header('Location: ' . $loginUrl);
            }
        } catch(Exception $e){
            //Error occured, close the window
            $logMessage = serialize($e);
            Yii::log($logMessage);
            echo "<script>javascript:window.close(); </script>";
            exit;
        }
    }

    public function login_flickr(){
        try {
            if(!empty(Yii::app()->session['flickr'])) {
                $logMessage = "Flickr session exsits".serialize(Yii::app()->session['flickr']);
                Yii::log($logMessage);
                // User info present in the session, close the window
                echo "<script>javascript:window.close(); </script>";
                exit;
            }
            $fr = $this->getFlickr();

            $media = 'flickr';
            $user_profile = $this->validate_user($media);
            if ($user_profile) {


                $logMessage = "Prepare user profile data :".serialize($user_profile);
                Yii::log($logMessage);

                $accessToken = Yii::app()->session[$media]['token'];

                // store user access token
                $fr->setToken($accessToken);

                echo "<script>javascript:window.close(); </script>";
                exit;

            } else {
                ob_start();

                $media = "flickr";

                Yii::app()->session['type'] = isset($_GET['type']) ? $_GET['type']  : "submission";


                unset($_SESSION['phpFlickr_auth_token']);

                if ( isset($_SESSION['phpFlickr_auth_redirect']) && !empty($_SESSION['phpFlickr_auth_redirect']) ) {
                    $redirect = $_SESSION['phpFlickr_auth_redirect'];
                    unset($_SESSION['phpFlickr_auth_redirect']);
                }

                $permissions = "read";
                $redirect_uri = $this->callbackURL."?media=".$media."&debug";
                if (empty($_GET['frob'])) {
                    $fr->auth($permissions, false);
                } else {
                    $auth = $fr->auth_getToken($_GET['frob']);
    //                $_SESSION['user_info'] = $auth['user'];
                    $logMessage = "Prepare the session data to save, details :".serialize($auth);
                    Yii::log($logMessage);
                    Yii::app()->session['media'] = $media;
                    Yii::app()->session[$media] = $auth;
                }

                if (empty($redirect)) {
                    header("Location: " . $redirect_uri);
                } else {
                    header("Location: " . $redirect);
                }
            }
        } catch(Exception $e){
            //Error occured, close the window
            $logMessage = serialize($e);
            Yii::log($logMessage);
            echo "<script>javascript:window.close(); </script>";
            exit;            
        }
    }

    public function parseTwResponse(){


    }


    /**
     * Save all authentication informatoin
     * of the Authenticating User
     *
     * This is not a mandatory excercise
     *
     * @param array $params
     * @return bool
     */
    public function saveAuthInfo(array $params){
        if (!$params || !is_array($params)){
            return false;
        }
        //pass this data to the cnkAdmin API
        Yii::log("Requesting to save authorized user info");
        $response = Yii::app()->services->performRequest('/socialAuth',$params,"POST")->getResponseData(true);
        if($response){
            return;
        }
        return false;
    }

    public function getFacebook(){

        if (is_null($this->fb)){
        Yii::import("application.vendor.facebook.Facebook", true);
        $this->fb = new Facebook(array('appId'=>Yii::app()->params['FB_APP_ID'],'secret'=>Yii::app()->params['FB_SECRET_KEY']));
        }
        return $this->fb;
    }

    public function setTwitter($oauth_token = NULL, $oauth_token_secret = NULL){

        Yii::import(Yii::getPathOfAlias("application.vendor.twitter.TwitterOAuth"), true);
        self::$tw = new TwitterOAuth(
                Yii::app()->params['TW_ACCESS_KEY'],
                Yii::app()->params['TW_ACCESS_SECRET'],
                $oauth_token,
                $oauth_token_secret
        );
    }

    public function getTwitter(){
        if (is_null(self::$tw)){
            $this->setTwitter();
        }
        return self::$tw;
    }

    public function getGoogle(){
        if (is_null($this->gg)){
            Yii::import("application.vendor.Google.Client", true);
            $client_id = Yii::app()->params['GG_CLIENT_ID'];
            $client_secret = Yii::app()->params['GG_CLIENT_SECRET'];
            $redirect_uri = $this->callbackURL."?media=google";

            $this->gg = new Google_Client();
            $gg = $this->gg;
            $gg->setClientId($client_id);
            $gg->setClientSecret($client_secret);
            $gg->setRedirectUri($redirect_uri);

        }
        return $this->gg;
    }

    public function getYoutube(){
        if(is_null($this->yt)){
            //Yii::import("application.vendor.Google.Services.Youtube", true);
            Yii::import(Yii::getPathOfAlias("application.vendor.Google.Service.YouTube"),true);
            $this->yt = new Google_Service_YouTube($this->gg);

        }
        return $this->yt;
    }
    public function getGooglePlus(){
        if(is_null($this->gp)){
            //Yii::import("application.vendor.Google.Services.Youtube", true);
            Yii::import(Yii::getPathOfAlias("application.vendor.Google.Service.Plus"),true);
            $this->gp = new Google_Service_Plus($this->gg);

        }
        return $this->gp;
    }

    public function getInstagram(){
        if (is_null($this->ig)){
            Yii::import(Yii::getPathOfAlias("application.vendor.instagram.Instagram"), true);
//            require_once Yii::app()->basePath . '/vendor/instagram/Instagram/Auth.php';
            $client_id = Yii::app()->params['IG_CLIENT_ID'];
            $client_secret = Yii::app()->params['IG_CLIENT_SECRET'];
            $redirect_uri = $this->callbackURL."?media=instagram&debug";



            $auth_config = array(
                'apiKey'         => $client_id,
                'apiSecret'     => $client_secret,
                'apiCallback'      => $redirect_uri,
//        'scope'             => array( 'likes', 'comments', 'relationships' )
                'scope'             => array( 'basic' )
            );

            $this->ig = new Instagram($auth_config);

        }
        return $this->ig;
    }

    public function getFlickr(){
        if (is_null($this->fr)){
            Yii::import(Yii::getPathOfAlias("application.vendor.flickr.phpFlickr"), true);
            $client_id = Yii::app()->params['FR_CLIENT_ID'];
            $client_secret = Yii::app()->params['FR_CLIENT_SECRET'];

            $this->fr = new phpFlickr($client_id,$client_secret);

        }
        return $this->fr;
    }

    public function validate_user($type){

        $user = null;

        switch($type){
            case 'facebook':
                $user = $this->getFacebook()->getUser();
                break;
            case 'twitter':
                $user = Yii::app()->session['twitter'];
                if(empty($user)) {
                    $user =  false;
                }
                break;
            case 'google':
                $user = Yii::app()->session['google'];
                if(empty($user)) {
                    $user =  false;
                }
                break;
            case 'instagram':
                $user = Yii::app()->session['instagram'];
                if(empty($user)) {
                    $user =  false;
                }
                break;
            case 'flickr':
                $user = Yii::app()->session['flickr'];
                if(empty($user)) {
                    $user =  false;
                }
                break;
        }
        if ($user){
            return $user;
        } else {
            return false;
        }
    }

    public function actionUserInfo(){

        //check if ajax

        if(!$_GET['media'] || !in_array($_GET['media'],$this->allowedMedia)){
            $this->_sendResponse('501','Cannot send response for empty media');
        }


        $callback = isset($_GET['callback']) ? $_GET['callback'] : null;
        
        //else return the session data
        switch($_GET['media']){
            case 'facebook':
                $user = $this->validate_user('facebook');
                if ($user){
                    $this->_sendResponse(200,json_encode(array('media'=>'facebook','info'=>$_SESSION['facebook'])),$callback);
                } else {
                    $this->_sendResponse(200,json_encode(array('response'=>'false')),$callback);
                }
                break;
            case 'twitter':
                $user = $this->validate_user('twitter');
                if ($user){
                    $this->_sendResponse(200,json_encode(array('media'=>'twitter','info'=>$user)),$callback);
                } else {
                    $this->_sendResponse(200,json_encode(array('response'=>'false')),$callback);
                }
                break;
            case 'google':
                $user = $this->validate_user('google');
                if ($user){
                    $this->_sendResponse(200,json_encode(array('media'=>'google','info'=>$user)),$callback);
                } else {
                    $this->_sendResponse(200,json_encode(array('response'=>'false')),$callback);
                }
                break;
            case 'instagram':
                $user = $this->validate_user('instagram');
                if ($user){
                    $this->_sendResponse(200,json_encode(array('media'=>'google','info'=>$user)),$callback);
                } else {
                    $this->_sendResponse(200,json_encode(array('response'=>'false')),$callback);
                }
                break;
        }
    }


    private function _sendResponse($status = 200, $body = '', $callback = '', $content_type = 'application/json')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        //header('Content-type: ' . $content_type);
        header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');
         
        // pages with body are easy
        if($body != '')
        {
            if ($callback){
                echo $callback."(".$body.")";
            } else {
                echo $body;
            }
        } else {
            // create some body messages
            $message = '';
            switch($status)
            {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
                    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                    <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                        <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                    </head>
                    <body>
                        <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                        <p>' . $message . '</p>
                        <hr />
                        <address>' . $signature . '</address>
                    </body>
                    </html>';
            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }


    private function __parseSocialProfileInfo($media, $profile){
        $userInfo = array();
        switch($media){
            case 'facebook':
                $userInfo['identifier'] = $profile['id'];
                $userInfo['first_name'] = $profile['first_name'];
                $userInfo['last_name'] = $profile['last_name'];
                $userInfo['email_id'] = isset($profile['email']) ? $profile['email'] : null;
                $userInfo['dob'] = isset($profile['birthday']) ? $this->__parseDOB($profile['birthday']) : null;
                $userInfo['city'] = isset($profile['location']['name']) ? $profile['location']['name'] : null;
                $userInfo['username'] = $profile['username'];
                $userInfo['profile_photo'] = "https://graph.facebook.com/".$userInfo['identifier']."/picture";
                $userInfo['about_me'] = "";
                break;
            case 'google':
                $userInfo['identifier'] = $profile->id;
                $name = explode(" ",$profile->displayName);
                if(is_array($name)){
                    $userInfo['first_name'] = isset($name[0])?$name[0]:"";
                    $userInfo['last_name'] =  isset($name[1])?$name[1]:"";
                }
                $userInfo['email_id'] = isset($profile['data']['emails'][0]['value']) ? $profile['data']['emails'][0]['value'] : null;
                $userInfo['dob'] = $this->__parseDOB($profile->birthday);
                $userInfo['city'] = isset($profile['data']['placesLived'][0]['value']) ? $profile['data']['placesLived'][0]['value'] : null;
                $userInfo['username'] = $userInfo['identifier'];
                $userInfo['profile_photo'] = $profile['data']['image']['url'];
                $userInfo['about_me'] = $profile->aboutMe;
                break;
            case 'twitter':
//                print_r($profile); exit;
                $userInfo['identifier'] = $profile->id_str;
                $name = explode(" ",$profile->name);
                if(is_array($name)){
                    $userInfo['first_name'] = isset($name[0])?$name[0]:"";
                    $userInfo['last_name'] =  isset($name[1])?$name[1]:"";
                }
                $userInfo['email_id'] = isset($profile->email) ? $profile->email : null;
                $userInfo['dob'] = isset($profile->birthday) ? $this->__parseDOB($profile->birthday) : null;
                $userInfo['city'] = isset($profile->location) ? $profile->location : null;
                $userInfo['username'] = $profile->screen_name;
                $userInfo['profile_photo'] = $profile->profile_image_url;
                $userInfo['about_me'] = $profile->description;
                break;
            case 'instagram':
//                print_r($profile); exit;
                $userInfo['identifier'] = $profile->id;
                $name = explode(" ",$profile->full_name);
                if(is_array($name)){
                    $userInfo['first_name'] = isset($name[0])?$name[0]:"";
                    $userInfo['last_name'] =  isset($name[1])?$name[1]:"";
                }
                $userInfo['email_id'] = isset($profile->email) ? $profile->email : null;
                $userInfo['dob'] = isset($profile->birthday) ? $this->__parseDOB($profile->birthday) : null;
                $userInfo['city'] = isset($profile->location) ? $profile->location : null;
                $userInfo['username'] = $profile->username;
                $userInfo['profile_photo'] = $profile->profile_picture;
                $userInfo['about_me'] = $profile->bio;
                break;
        }
        return $userInfo;
    }

    private function __parseDOB($dob){
        if(isset($dob) && !is_null($dob)){
            return date('m/d/Y',strtotime($dob));
        }
        return $dob;
    }


    private function __vote($contentId, $media, $socialUserInfo){
//        print_r($socialUserInfo);
        $model = new ContentVote();
        $contentVote = $model->findByAttributes(
                                            array('content_id'=>$contentId,
                                                'social_id'=>$socialUserInfo['user_id']));

        if($contentVote){
            $status = "fail";
            $message = "You have already voted for this content.";

            $logMessage = "User has already voted, details : ".serialize($contentVote);
            Yii::log($logMessage);
        } else {
            try {
                $model->content_id = $contentId;
                $model->date = new CDbExpression('NOW()');
                $model->user_ip = $_SERVER['REMOTE_ADDR'];
                $model->auth_source = $media;
                $model->social_id = $socialUserInfo['user_id'];
                $model->username = $socialUserInfo['user_name'];
                $model->environment_id = 1;   //$this->getEnv();

                if($model->save()){
                    $status = "success";
                    $message = "Successfully voted.";

                    $logMessage = "Successfully voted for content id : ".$contentId;
                    Yii::log($logMessage);

                    // Now update the content table with +1 vote
//                    $contentModel = new Content($contentId);
                    	$contentModel = Content::model()->findByPk($contentId);
                        $logMessage = "model detail : ".serialize($contentModel);
                        Yii::log($logMessage);

                    $contentModel->vote += 1;

                    if($contentModel->update()){
//                        print_r($contentModel); exit;
                        $logMessage = "Updated vote count for content id : ".$contentId;
                        Yii::log($logMessage);
                    } else {
                        $logMessage = "Unable to update vote count for content id : ".$contentId.", error message : ".serialize($contentModel->getErrors());
                        Yii::log($logMessage);
                    }

                } else {
                    $logMessage = "Unable to save vote, error : ".serialize($model->getErrors());
                    Yii::log($logMessage);
                }
            } catch(Exception $e){
                $status = "fail";
                $message = $e->getMessage();

                $logMessage = "Unable to save details of vote, error : ".serialize($e);
                Yii::log($logMessage);
            }
        }

        $vote = array();
        $vote['status'] = $status;
        $vote['message'] = $message;
        $vote['content_id'] = $contentId;

        $session = Yii::app()->session;
        $session['vote'] = $vote;
        $_SESSION['vote'] = $vote;     // JUST A DIRTY TRICK TO HAVE ALTERNATE OPTION
		
// 		print_r($session['vote']); 
        // Close the pop up window

        echo "<script>javascript:window.opener.vote_result('".$media."','".$contentId."'); window.close();</script>";
    }


    public function actionGetVoteCount(){
        $result = array();
        $callback = isset($_GET['callback']) ? $_GET['callback'] : null;
        if(isset($_GET['content_id']) && $_GET['content_id']!=""){
            $content_id = $_GET['content_id'];
            $model = Content::model()->findByPk($content_id);
//            print_r($model);
            $result['status'] = "success";
            $result['message'] = "Success"; // This operation result
            $result['vote_count'] = $model->vote;
        } else {
            $result['status'] = "fail";
            $result['message'] = "Invalid content Id";
        }

        $voteDetails = isset($_SESSION['vote']) ? $_SESSION['vote'] : Yii::app()->session['vote'];
// 			var_dump($_SESSION);
//        var_dump(Yii::app()->session); exit;

        $result['vote'] = $voteDetails;  // Previous operation resutl, ie, voting
//         echo json_encode($result);
        $this->_sendResponse(200,json_encode($result),$callback);
//         Yii::app()->end();

    }
    
    public function actionShare($content_id, $media){
        
        if ($_GET['media'] || in_array($_GET['media'],$this->allowedMedia)){
            if ($_GET['content_id']) {
                
                $content_id = $_GET['content_id'];
                $media = $_GET['media'];
                
                //$redirect_uri = "https://grabyourdream.com/gallery/index?lang=en&env=base&phase=submission";
                $redirect_uri = Yii::app()->request->hostInfo . "/pages/redirect?lang=en&env=base&phase=submission";
                //$profile_link = "http://grabyourdream.com/user/profile/17?lang=en&env=base&phase=submission";
                
                $model = new Content();
                $fb_base_url = "https://www.facebook.com/dialog/feed?app_id=657818324256026";
                $twitter_base_url = "https://twitter.com/intent/tweet";
                $googleplus_base_url = "https://plus.google.com/share";
                $model = Content::model()->findByPk($content_id);
                $userModel = User::model()->findByPk($model->user_id);
                //$userModel = $this->loadModel($model->user_id);
                //$userProfile = UserProfiles::model()->find('user_id=:userId',array(":userId"=>$userModel->id));
                $userName = $userModel->first_name;
                
                
                $url = '';
                if($model != null) {
                    $profile_link = Yii::app()->request->hostInfo . "/user/profile/" . $model->user_id . "?lang=en&env=base&phase=submission"; 
                    // Facebook
                    if($media == 'facebook') {
                        $title = "I really liked " . $userName . "'s entry on Grab Your Dream" ;
                        $description = "You can also view & vote for your favourite entries on Grab Your Dream. Register & Participate in Grab Your Dream to win an all expenses paid trip to a dream destination anywhere across the world.";
                        $url = $fb_base_url . "&link=" . $profile_link . "&name=" . urlencode($title) . "&description=" . urlencode($description) . "&picture=" . $model->thumb_image . "&redirect_uri=" . $redirect_uri;   
                    }

                    
                    $text = "My vote goes to this entry on #GrabYourDream. Participate to win an all expenses paid trip to a dream destination";
                    
                    // Twitter
                    if($media == 'twitter') {
                        $url = $twitter_base_url . "?text=" . urlencode($text) ."&url=" . $profile_link;
                    }

                    if($media == 'google') {
                        $url = $googleplus_base_url . "?url=" . $profile_link;
                    }
                }    
                $this->redirect($url);
            }
        }
    } 

    /**
     * Function to fetch social images
     *
     */
    public function actionLoadMediaImages()
    {
        $result = array();
        $callback = isset($_GET['callback']) ? $_GET['callback'] : null;
        $imagesCount = 50;
        $apiError = false;
        if (isset($_GET['media']) || in_array($_GET['media'],$this->allowedMedia)){
            $media = $_GET['media'];
            $more = isset($_GET['more']) ? $_GET['more']:null;

            $logMessage = "Media : ".$media.", API call is about to start, and more is ".$more;
            Yii::log($logMessage);

            switch ($media) {
                case 'facebook':
                    $after = !is_null($more) ? "&after=".$more : "";
                    $accessToken = $this->getFacebook()->getAccessToken();
                    $this->getFacebook()->setAccessToken($accessToken);
                    $fb = $this->getFacebook();
                    //print_r($fb); exit;
                    $response = $fb->api("/me/photos?type=uploaded&access_token=".$accessToken."&limit=".$imagesCount.$after);
                    $images = $this->__parseImages($response,"facebook");


                    //$images = $fb->api("/me/photos");
                    break;
                case 'instagram':
                    $maxid = !is_null($more) ? $more : null;
                    $sessionData = Yii::app()->session['instagram'];

                    unset(Yii::app()->session['instagram']);

                    $accessToken = $sessionData['access_token'];
                    $this->getInstagram()->setAccessToken($accessToken);

                    $logMessage = "Restored instagram Session data, access token = ".$accessToken;
                    Yii::log($logMessage);

//                    $response = $this->getInstagram()->getUserFeed($imagesCount,$maxid);
                    $response = $this->getInstagram()->getUserMedia("self",$imagesCount,$maxid);
                    $images = $this->__parseImages($response,"instagram");
                    Yii::app()->session['instagram'] = $sessionData;
                    break;
                case 'flickr':
                    $page = !is_null($more) ? $more : null;
                    $sessionData = Yii::app()->session[$media];

                    //unset(Yii::app()->session[$media]);

                    $accessToken = $sessionData['token'];

//                    print_r($accessToken);

                    $this->getFlickr()->setToken($accessToken);

                    $logMessage = "Restored flickr Session data, access token = ".$accessToken;
                    Yii::log($logMessage);

//                    $response = $this->getInstagram()->getUserFeed($imagesCount,$maxid);
                    $userId = $sessionData['user']['nsid'];
                    $params = array();
                    $params['per_page'] = $imagesCount;
                    $params['page'] = $page;
                    $params['extras'] = "date_taken,owner_name,original_format,geo,url_o, url_t,media";
                    $response = $this->getFlickr()->people_getPhotos($userId,$params);
                    if($this->getFlickr()->getErrorCode()){
                        $logMessage = "There was some error ".$this->getFlickr()->getErrorMsg();
                        Yii::log($logMessage);
                    }
                    $images = $this->__parseImages($response,$media);
//                    Yii::app()->session[$media] = $sessionData;
                    break;
            }
            if(!empty($images['images'])){
                $logMessage = " Image result is not empty, so processing it";
                Yii::log($logMessage);
                $result['data'] = $images;
                $result['status'] = "success";
                $result['message'] = "success";
            } else if(!is_null($more)){
                $logMessage = " Image result is empty, return the fail";
                Yii::log($logMessage);
                $result['data'] = array("images"=>array(),"pagination"=>array("next"=>null));
                $result['status'] = "success";
                $result['message'] = "Unable to fetch photos from $media.";
            } else {
                $logMessage = " Retry";
                Yii::log($logMessage);
                $result['data'] = array("images"=>array(),"pagination"=>array("next"=>null));
                $result['status'] = "fail";
                $result['message'] = "Unable to fetch photos from $media.";
            }
        } else {
            $result['status'] = "fail";
            $result['message'] = "Unknown/Unsupported media type";
        }
        $this->_sendResponse(200,json_encode($result),$callback);
    }


    /**
     *
     * Function to parse and get a uniform images array
     */
    private function __parseImages($images,$media){
//        print_r($images);
        $filteredImages = array();
        $temp = array();
        $next = null;
        if($media=="instagram"){
            if(!empty($images) && !empty($images->data)){
                $c = 0;
//                print_r($images->data);
                foreach($images->data as $key=>$image){
                   if($image->type == "image"){
                       $temp[$c]['id'] = $image->id;
                       $temp[$c]['title'] = isset($image->caption->text)?$this->__getImageCaption($image->caption->text):"";
                       $temp[$c]['data_created'] = isset($image->created_time)?strtotime($image->created_time):null;
                       $temp[$c]['location'] = $image->location;
                       $temp[$c]['link'] = $image->link;
                       $temp[$c]['picture'] = $image->images->thumbnail->url;
                       $temp[$c]['source'] = $image->images->standard_resolution->url;
                       $c++;
                   }
                }

                $next = isset($images->pagination->next_max_id)?$images->pagination->next_max_id:null;
                $logMessage = "Instagram Next id $next";
                Yii::log($logMessage);
            }
        } else if($media =="facebook"){
            if(!empty($images) && !empty($images['data'])){
                $c = 0;
//                print_r($images['data']);
                foreach($images['data'] as $key=>$image){
                    $temp[$c]['id'] = $image['id'];
                    $temp[$c]['title'] = isset($image['name'])?$this->__getImageCaption($image['name']):"";
                    $temp[$c]['data_created'] = isset($image['created_time'])?strtotime($image['created_time']):"";
                    $temp[$c]['location'] = isset($image['place'])?$image['place']:null;
                    $temp[$c]['link'] = $image['link'];
                    $temp[$c]['picture'] = $image['picture'];
                    $temp[$c]['source'] = $image['source'];
                    $c++;
                }
                $next = isset($images['paging']['cursors']['after'])?$images['paging']['cursors']['after']:null;
                $logMessage = "Facebook Next id $next";
                Yii::log($logMessage);
            }
        } else if($media =="flickr"){
            if(!empty($images) && !empty($images['photos']['photo'])){
                $c = 0;
//                print_r($images['photos']['photo']);
                foreach($images['photos']['photo'] as $key=>$image){
                    if($image['media']=="photo"){
                        $place = array();
                        isset($image['place_id'])?$place['id']=$image['place_id']:null;

                        $temp[$c]['id'] = $image['id'];
                        $temp[$c]['title'] = isset($image['title'])?$this->__getImageCaption($image['title']):"";
                        $temp[$c]['data_created'] = isset($image['datetaken'])?strtotime($image['datetaken']):null;
                        $temp[$c]['location'] = $place;
                        $temp[$c]['link'] = "https://www.flickr.com/photos/".$image['owner']."/".$image['id'];
                        $temp[$c]['picture'] = $image['url_t'];
                        $temp[$c]['source'] = $image['url_o'];
                        $c++;
                    }
                }
                $next = null;
                if(isset($images['photos']['page']) && ($images['photos']['page'] < $images['photos']['pages'])) {
                    $next = (int) $images['photos']['page'] + 1;
                }
                $logMessage = "Flickr Next id $next";
                Yii::log($logMessage);
            }
        }

        $filteredImages['pagination']['next'] = $next;
        $filteredImages['images'] = $temp;

        return $filteredImages;
    }

    /**
     * @param $title string
     * @return trimmed (250) chars
     */
    private function __getImageCaption($title){
        //return $title;
        if(isset($title) && $title!=""){
            if(strlen($title) > 250){
                return substr($title,0,250);
            } else {
                return $title;
            }
        }
    }

    /**
     * Vote Action
     * Method to do the vote posting
     * for the content
     * Calls API from Admin
     */
    public function actionVote(){

        if ($_POST && $_POST['content_id'] && $_POST['name'] && $_POST['email']){

            $params['content'] = $_POST['content_id'];
            $params['name'] = $_POST['name'];
            $params['email'] = $_POST['email'];

            //call the api to vote;
            $response = Yii::app()->services->performRequest('/vote/',$params,"POST")->getResponseData(true);
            $this->_sendResponse(200,json_encode($response));
        } else {
            $this->_sendResponse(501,'Invalid details supplied');
        }
    }

    
    // Social Login - redirection login
    // If teh user is authenticated and verified, redirect to register page with prefiled info
    // else - Show error to user [Verify your account]
    public function actionCheckSocialLoginEmail($email, $media){
        
        $userInfo = array();
        $criteria = new CDbCriteria;
        $criteria->compare('email',$email);
        $userInfo = User::model()->findAll($criteria);
        
        if(!empty($userInfo) && !empty($userInfo[0])) {
            if($userInfo[0]['is_verified'] == true) {
                // force login the guy
                $username = $userInfo[0]['email'];
                $password = $userInfo[0]['password'];
                $identity = new UserIdentity($username,$password);
                $identity->authenticate('user', true);
                if($identity->errorCode === UserIdentity::ERROR_NONE) { 
                    $duration = 3600*24*60; // 60 days
                    Yii::app()->user->login($identity,$duration);
                    User::model()->updateByPk($identity->getId(),array('last_login_time' => new CDbExpression('NOW()')));
}
                //redirect to the submission page
                $this->redirect($this->createAbsoluteUrl("ugc/submission"));
            } else {
                
                // Show message to check the verification code
                $redirectParams = array('lang' => 'en', 'env' => 'base', 'phase' => 'submission' , 'not_verified' => true);
                $redirectUrl = Yii::app()->createAbsoluteUrl("user/login", $redirectParams);
                $this->redirect($redirectUrl);
            }
        } else  {
            // Redirect to regiter page with prefill
            $redirectParams = array('lang' => 'en', 'env' => 'base', 'phase' => 'submission' , 'prefill' => true, 'media' => $media);
            $redirectUrl = Yii::app()->createAbsoluteUrl("user/registration", $redirectParams);
            $this->redirect($redirectUrl);
        }
    }

}