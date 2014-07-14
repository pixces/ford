<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /*
     * class variables
     */

    /**
     * Phase name
     * Defaults to current active phase
     * @var
     */
    protected static $phase = null;

    /**
     * Navigation Object
     * created from the phase module
     * @var
     */
    protected static $nav = null;

    /**
     * Id of the Current Page
     * @var null
     */
    protected $currPageId = null;

    /**
     * name of the Site called
     * defaults to config params
     * @var
     */
    protected $site = null;

    /**
     * SiteId if already defined
     * defaults to config params
     * @var
     */
    protected $siteId = null;

    /**
     * Language if already defined
     * defaults to config params
     * @var
     */
    protected $lang = null;

    /**
     * Environment if already defined
     * defaults to config params
     * @var
     */
    protected $env = null;

    /**
     * Site Preivew
     * disabled by default
     * @var
     */
    protected $preview = false;

    /**
     * The default page name
     * should be identical to the view name
     * @var
     */
    protected $page_name = null;

    /**
     * Name of the current phase
     * which is getting rendered
     * based on either the phase name passed
     * or else phase name from the phase details
     * @var
     */
    protected $phaseName = null;

    /**
     * Set the variable to define
     * if the submission is allowed
     * in the selected phase or not
     * @var int
     */
    protected $is_submission = 0;

    /**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';

    /**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();

    /**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();


    public function isAdmin(){
        if(Yii::app()->user->getName()=="admin@ford.com")
           return true;
        else
            return false;
    }

    /**
     * Initialize all the default application
     * constructs
     */
    public function init(){
        $siteParams = $this->sanitizeData($this->getActionParams());

        //set the preview
        if (isset($siteParams['preview']) && $siteParams['preview'] == true){
            $this->preview = true;
        }

        //sanitize all the input variables excepted from the url
        if (isset($siteParams['view'])){
            $this->page_name = strip_tags($siteParams['view']);
        }

        //set the default values for all other siteParams
        $this->setLang(isset($siteParams['lang']) ? strip_tags($siteParams['lang']) : null);
        $this->setEnv(isset($siteParams['env']) ? strip_tags($siteParams['env']) : null);

        if ($this->preview){
            $this->setPhase(isset($siteParams['phase']) ? strip_tags($siteParams['phase']) : null);
        } else {
            $this->setPhase(null);
        }

        $this->setSiteId(isset($siteParams['siteId']) ? $siteParams['siteId'] : null);

        return;
    }


    /**
     * Set Default Language
     * @param null $lang
     */
    protected function setLang($lang=null){
        if ( !isset($this->lang)){
            if (isset($lang) &&  in_array($lang, array('en'))){
                $this->lang = $lang;
            } else {
                $this->lang = Yii::app()->params['LANG'];
            }
        }
    }

    /**
     * Set Site Environment
     * @param null $env
     */
    protected function setEnv($env=null){
        if (!isset($this->env)){
            if (isset($env) && in_array($env,array('base','facebook','youtube'))){
                $this->env = $env;
            } else {
                $this->env = Yii::app()->params['ENV'];
            }
        }
    }

    /**
     * Set Site Id
     * @param null $id
     */
    protected function setSiteId($id=null){
        if (!isset($this->siteId)){
            if (isset($id)){
                $this->siteId = $id;
            } else {
                $this->siteId = Yii::app()->params['SITE_ID'];
            }
        }
    }

    /**
     * get the details of the phase provided
     * or else get current active phase details
     * @param null $phase
     * @return array
     */
    protected function setPhase($phase=null){

        $params = array();

        if (!isset(self::$phase)){
            if (isset($phase)){
                $params = array(
                    'phase_name' => $phase,
                );
            } else {
                $params = array(
                    'status' => 'active',
                );
            }

            //get the phase details based on the phase
            $details = $this->getPhaseDetails($params);

            if ($details){
                $this->phaseName = $details[0]['phase_name'];
                self::$phase = $details;

                //also set the navigation details
                $this->createNavigation($details);
            }
        }
    }

    /**
     * method to create the navigation
     * based on the phase details
     * @param array $array
     */
    protected function createNavigation($array){

        if (!isset(self::$nav)){
            $nav = array();

            //get the params array to be passed to the create url methods
            $params = $this->getSiteParams();

            foreach($array as $phase){
                $is_active = false;
                $key = ($phase['link_type'] == 'landing') ? 'home' : $phase['page_name'];

                if( $this->getPageName() != null ){
                    if( $this->getPageName() == $phase['page_name'] ){
                        $is_active = true;
                        $this->is_submission = $phase['submission'];
                    }
                } else {
                    if ($phase['link_type'] == 'landing'){
                        $is_active = true;
                        $this->is_submission = $phase['submission'];
                    }
                }
                //set the view name
                $params['view'] = $phase['page_name'];
                $nav[$key] = array(
                    'label'     => ucwords(strtolower($phase['page_label'])),
                    'url'       => Yii::app()->createAbsoluteUrl("pages/display",$params),
                    'id'        => $phase['page_id'],
                    'is_active' => $is_active,
                    'page_name' => $phase['page_name'],
                );
            }
            self::$nav = $nav;
        }
    }

    /*
     * Getters
     */
    protected function getSite(){
        return $this->site;
    }

    protected function getSiteId(){
        return $this->siteId;
    }

    protected function getLang(){
        return $this->lang;
    }

    protected function getEnv(){
        return $this->env;
    }

    protected function getPhase(){
        return self::$phase;
    }

    protected function getNav(){
        return self::$nav;
    }

    protected function getPageName(){
        return $this->page_name;
    }

    protected function getPhaseName(){
        return $this->phaseName;
    }

    protected function getNavigation(){
        return $this->nav;
    }

    protected function getIsSubmission(){
        return $this->is_submission;
    }

    protected function getSiteParams(){
        $preview = array();
        $phaseDetails = $this->getPhase();
        $base =  array(
            'env' => $this->getEnv(),
            'lang'=> $this->getLang(),
            'phase'=>$this->getPhaseName(),
            //'is_submission' =>1
        );

        if ($this->preview == true){
            $preview = array('preview' => true);
        }

        $base = array_merge($base,$preview);
        return $base;
    }

    protected function getPageDetails($url,$params=array()){
        $pageObj = Yii::app()->services->performRequest($url,$params)->getResponseData(true);
        return $pageObj;
    }

    protected function getWidgetTypes(){
        $widgetTypes = Yii::app()->services->performRequest('/widgetType/',array())->getResponseData(true);
        return $widgetTypes;
    }

    protected function getWidget($url,$params=array()){
        $widgetDetails = Yii::app()->services->performRequest($url,$params,'GET')->getResponseData(true);
        return $widgetDetails;
    }

    protected function getPhaseDetails($params){
        $phase = array();

        if ($params) {
            $phase = Yii::app()->services->performRequest('/phase',$params,'GET')->getResponseData(true);
        }
        return $phase;
    }

    protected function getUGCGallery(){
        return Yii::app()->params['ugcGalleryId'];
    }

    protected function getSiteFooter(){
        $footerWidgetType = 2;
        $params = array(
            'widget_type_id'=>$footerWidgetType
        );

        $widget = $this->getWidget('/widget',$params);

        if ($widget){
            return $widget['data'];
        }
    }

    protected function getSitePartners(){
        $partnersWidgetTypeId = 3;
        $params = array(
            'widget_type_id'=>$partnersWidgetTypeId
        );

        $widget = $this->getWidget('/widget',$params);

        if ($widget){
            return $widget['data'];
        }
    }
    
    /**
     * Override of Base CreateAbsoluteURl;
     * Common method to create and return the
     * absolute urls appending the common site params
     */
    public function createAbsoluteUrl($route, $params=array( ), $schema='', $ampersand='&'){

        $baseParams = $this->getSiteParams();

        if ($params){
            $baseParams = array_merge($baseParams,$params);
        }
        return Yii::app()->createAbsoluteUrl($route,$baseParams);
    }

    public function validateUserData() {
        //strip tags
        $_POST = array_map('strip_tags', $_POST);
        $flag = true;
        if (isset($_POST['social_photo']) && (!empty($_POST['social_photo']))) {

            if (!filter_var($_POST['social_photo'], FILTER_VALIDATE_URL)) {
                $flag = false;
            }
        }
        return $flag;
    }

    public static function sanitize($string) {
        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<alert[^>]*?>.*?</alert>@siU', // Strip alert tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
        );

        $string = trim($string);
        $string = strip_tags($string);
        $string = preg_replace($search, '', $string);

        return $string;
    }

    protected function clean_all($arr)
    {
        foreach($arr as $key=>$value)
        {
            if(is_array($value)) $arr[$key] = $this->clean_all($value);
            else  $arr[$key] = self::sanitize($value);
        }
        return $arr;
    }

    protected function sanitizeData($mixed){
        if (!is_array($mixed)){
            return self::sanitize($mixed);
        } else {
            return $this->clean_all($mixed);
        }
    }

}