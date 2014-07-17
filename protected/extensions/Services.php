<?php
/**
 * Created by IntelliJ IDEA.
 * User: zainulabdeen
 * Date: 02/03/14
 * Time: 3:40 PM
 * To change this template use File | Settings | File Templates.
 *
 * SERVICES Class V1.0
 * The service class to consume apis from Emporia Admin
 *
 * PHP version 5.4.10
 *
 * @package  SERVICES
 * @license  MIT License
 */
class Services extends CApplicationComponent
{

    /**
     * @var Current class version
     */
    const SERVICE_CLASS_VERSION = '1.0';

    /**
     * @var Unique Identifier of the cached content
     */
    const CACHE_KEY = 'Yii.Service.Class.Cache.';

    /**
     * @var Current api version
     */
    const API_VERSION = 'v1';

    /**
     * @var App Id for the current site
     */
    public $appId;

    /**
     * @var Site Id of the Application
     */
    public $siteId;

    /**
     * @var Site Name of the app;
     */
    public $site;

    /**
     * @var array of all Params for POST Method
     */
    private $postfields;

    /**
     * @var array of all Params for Get Method
     */
    private $getfield;

    /**
     * @var API Base URL
     */
    public $api_url;

    /**
     * @var Actial Call url
     */
    private $url;

    /**
     * @var string the ID of the cache application component that is used to cache the parsed response data.
     * Defaults to 'cache' which refers to the primary cache application component.
     * Set this property to false if you want to disable caching URL rules.
     * @since 1.0.0
     */
    public $cacheID = 'cache';

    /**
     * @var integer the time in seconds that the messages can remain valid in cache.
     * Defaults to 60 seconds valid in cache.
     */
    public $cachingDuration = 60;

    /**
     * @var Set true/false if you want to throw exceptions when error
     * Occurs. If you set this to false you can still know the error
     * Returned by accessing the methods getErrorNumber() and getErrorMessage()
     * And you can access the headers returned to see if there is an error there as well
     * By using the method getHeaders().
     */
    public $throwExceptions = true;

    /**
     * @var Use post request or get request? Default is GET
     */
    public $usePost = false;

    /**
     * @var boolean - Set this property to true if you want to return the JSON response
     * As a PHP array instead of a JSON string. This is here just for the people who like to use JSON
     * Since the returned data will be much smaller and then use it in a PHP array fashion.
     */
    public $returnAsArray = false;

    /**
     * @var int - Timeout in seconds
     */
    public $timeOut = 10;

    /**
     * @var Allowd formats for the calls. Note that not all API calls allow each of those
     * Formats. Some support them all while others not.
     */
    protected $allowedFormats = array( 'xml', 'json', 'rss', 'atom' );

    /**
     * @var Returned response before being parsed
     */
    protected $response = array();

    /**
     * @var returned response after being parsed
     */
    protected $responseData = array();

    /**
     * @var The returned response headers array
     */
    protected $headers = array();

    /**
     * @var Error number if any. By default this is set to 0, meaning there is no error.
     */
    protected $errorNumber = 0;

    /**
     * @var Error message if any. By default this is empty, meaning there is no error.
     */
    protected $errorMessage = '';

    /**
     * @var Response header codes. This is the codes returned from API
     * Just with the corresponding error message for each code for easier understanding.
     * To full understand what type of error returned and what should be done in order to fix it.
     */
    public $aStatusCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        301 => 'Status code is received in response to a request other than GET or HEAD, the user agent MUST NOT automatically redirect the request unless it can be confirmed by the user, since this might change the conditions under which the request was issued.',
        302 => 'Found',
        302 => 'Status code is received in response to a request other than GET or HEAD, the user agent MUST NOT automatically redirect the request unless it can be confirmed by the user, since this might change the conditions under which the request was issued.',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    /**
     * Component initializer
     *
     * @throws CException on missing CURL PHP Extension
     */
    public function init()
    {
        // Make sure we have CURL enabled
        if( !function_exists('curl_init') )
        {
            throw new CException(Yii::t('Service', 'Sorry, Buy you need to have the CURL extension enabled in order to be able to use the Service class. see: http://curl.haxx.se/docs/install.html'), 500);
        }

        //initialize the default params
        //all getting from the config prarms
        $this->api_url = Yii::app()->params['API_URL'];
        $this->site = Yii::app()->params['SITE'];
        $this->siteId = Yii::app()->params['SITE_ID'];
        $this->siteId = Yii::app()->params['APP_ID'];

        // Run parent
        parent::init();
    }


    /**
     * @param array $array
     * @return Services
     * @throws CException
     */
    public function setPostfields(array $array)
    {
        if (!is_null($this->getGetfield()))
        {
            throw new CException(Yii::t('Service', 'POST: You can only choose GET OR POST fields.'), 500);
        }

        if (isset($array['status']) && substr($array['status'], 0, 1) === '@')
        {
            $array['status'] = sprintf("\0%s", $array['status']);
        }
        $this->postfields = $array;
        return $this;
    }

    /**
     *
     * @param $string
     * @return Services
     * @throws CException
     */
    public function setGetfield($string)
    {
        if (!is_null($this->getPostfields()))
        {
            throw new CException(Yii::t('Service', 'GET: You can only choose GET OR POST fields.'), 500);
        }

        $search = array('#', ',', '+', ':');
        $replace = array('%23', '%2C', '%2B', '%3A');
        $string = str_replace($search, $replace, $string);

        $this->getfield = $string;
        return $this;
    }

    public function createApiUrl($path)
    {
        //build the base url
        $this->url = rtrim($this->api_url, '/').$path;
        return $this;
    }

    public function prepareParams($params, $requestMethod){

        switch($requestMethod){
            case 'GET':
                if (is_array($params)){
                    $getFields = "?".http_build_query($params);
                    $this->setGetfield($getFields);
                }
                break;
            case 'POST':
                $this->setPostfields($params);
                break;
        }
        return $this;
    }

    /**
     * Perform the actual data retrieval from the API
     * @param $path
     * @param array $params
     * @param string $requestMethod
     * @param bool $return
     * @return Services
     * @throws CException
     */
    public function performRequest($path, array $params, $requestMethod='GET', $return = true)
    {
        if (!in_array(strtolower($requestMethod), array('post', 'get')))
        {
            throw new CException(Yii::t('Service', 'Request method must be either POST or GET'), 500);
        }

        if (!is_bool($return))
        {
            throw new CException(Yii::t('Service', 'performRequest parameter must be true or false'), 500);
        }

        //prepare the api url
        $this->createApiUrl($path);

        //log the call url
        Yii::log("Call url: ".$this->url, 'info');


        //build curl headers
        $header = array($this->buildAuthorizationHeader(), 'Expect:');

        //prepare the params
        //params array found
        if ($params){
            $this->prepareParams($params,$requestMethod);
        }

        $getfield = $this->getGetfield();
        $postfields = $this->getPostfields();

        //create curl options
        $options = array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true
        );

        //add params
        if (!is_null($postfields))
        {
            $options[CURLOPT_POSTFIELDS] = $postfields;
        }
        else
        {
            if ($getfield !== '')
            {
                $options[CURLOPT_URL] .= $getfield;
            }
        }

        //create calls
        $feed = curl_init();
        curl_setopt_array($feed, $options);

        // execute
        $this->response = curl_exec($feed);
        $this->headers = curl_getinfo($feed);

        // fetch errors
        $this->errorNumber = curl_errno($feed);
        $this->errorMessage = curl_error($feed);

        //close
        curl_close($feed);

        //log call details
        //Yii::log($requestMethod." ".$this->url." Params: ".json_encode($params).' Response: '.$this->response,'info');
        //yii::log($this->getGetfield());
        Yii::log($requestMethod." ".$this->url." Params: ".json_encode($params),'info');

        if ($return) {

            //clear the post and get fields
            $this->getfield = null;
            $this->postfields = null;

            $this->setResponseData($this->response);
        }

        // invalid headers
        if(!in_array($this->headers['http_code'], array(0, 200)))
        {
            // throw error
            if( $this->throwExceptions )
            {
                throw new CException($this->headers['http_code']);
            }
        }

        // error?
        if( ($this->errorNumber != '' ) && ( $this->throwExceptions ) )
        {
            throw new CException($this->errorMessage, $this->errorNumber);
        }

        // return
        return $this;
    }

    /**
     * Private method to generate the base string used by cURL
     *
     * @param string $baseURI
     * @param string $method
     * @param array $params
     *
     * @return string Built base string
     */
    private function buildBaseString($baseURI, $method, $params)
    {
        $return = array();
        ksort($params);

        foreach($params as $key=>$value)
        {
            $return[] = "$key=" . $value;
        }

        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $return));
    }

    /**
     * Private method to generate authorization header used by cURL
     * @return string $return Header used by cURL for request
     */
    private function buildAuthorizationHeader()
    {
        $return = 'Authorization: Basic ';
        $values = array(
            'appId' => rawurlencode($this->appId) ,
            'siteId' => rawurlencode($this->siteId),
            'site' => rawurlencode($this->site),
            'service' => rawurlencode(self::SERVICE_CLASS_VERSION),
        );
        $return .= implode(', ', $values);
        return $return;
    }

    /**
     * Set the response data property
     *
     * @param mixed - the data to store in the responseData property
     * @return void
     */
    public function setResponseData( $data )
    {
        $this->responseData = $data;
    }

    /**
     * @return mixed - Return the default CURL response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed - Return the response code after being parsed
     */
    public function getResponseData($returnAsArray = false)
    {
        if ($returnAsArray == true){
            return (CJSON::decode($this->responseData));
        } else {
            return $this->responseData;
        }
    }

    /**
     * @return array - Return the CURL HTTP headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return int - If error occurs while performing the CURL
     * Request then the error code will be retrieved by this method
     */
    public function getErrorNumber()
    {
        return $this->errorNumber;
    }

    /**
     * @return string - If error occurs while performing the CURL
     * Request then the error code will be retrieved by this method
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Get getfield string (simple getter)
     * @return string $this->getfields
     */
    public function getGetfield()
    {
        return $this->getfield;
    }

    /**
     * Get postfields array (simple getter)
     * @return array $this->postfields
     */
    public function getPostfields()
    {
        return $this->postfields;
    }

}
