<?php

/**
 * Twitter-API-PHP : Simple PHP wrapper for the v1.1 API
 * 
 * PHP version 5.3.10
 * 
 * @package  Twitter-API-PHP
 * @license  MIT License
 * @link     http://github.com/j7mbo/twitter-api-php
 */
class Twitter_oAuth extends CApplicationComponent
{

    private $postfields;

    private $getfield;

    protected $oauth;

    public $url;
    /**
     * @var Current class version
     */
    const TWITTER_CLASS_VERSION = '1.1.0';
    /**
     * @var Twitter API URL
     */
    const TWITTER_API_URL = 'http://twitter.com';
    /**
     * @var Twitter API Port
     */
    const TWITTER_API_PORT = 80;
    /**
     * @var Unique Identifier of the cached content
     */
    const CACHE_KEY = 'Yii.Twitter.Class.Cache.';

    //twitter oauth access token
    public $oauth_access_token;

    //twitter oauth access token secret
    public $oauth_access_token_secret;

    //twitter consumer key
    public $consumer_key;

    //twitter consumer_secret;
    public $consumer_secret;

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
    public $returnAsArray = true;
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
     * @var Response header codes. This is the codes returned from twitter
     * Just with the corresponding error message for each code for easier understanding of the
     * type of error that occurred. You should visit {@link http://apiwiki.twitter.com/HTTP-Response-Codes-and-Errors}
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
            throw new CException(Yii::t('Twitter', 'Sorry, Buy you need to have the CURL extension enabled in order to be able to use the twitter class. see: http://curl.haxx.se/docs/install.html'), 500);
        }

        /*
        if (!isset($settings['oauth_access_token'])
            || !isset($settings['oauth_access_token_secret'])
            || !isset($settings['consumer_key'])
            || !isset($settings['consumer_secret']))
        {
            throw new Exception('Make sure you are passing in the correct parameters');
        }

        $this->oauth_access_token = $settings['oauth_access_token'];
        $this->oauth_access_token_secret = $settings['oauth_access_token_secret'];
        $this->consumer_key = $settings['consumer_key'];
        $this->consumer_secret = $settings['consumer_secret']
        */

        // Run parent
        parent::init();
    }

    /**
     * Create the API access object. Requires an array of settings::
     * oauth access token, oauth access token secret, consumer key, consumer secret
     * These are all available by creating your own application on dev.twitter.com
     * Requires the cURL library
     * 
     * @param array $settings

    public function __construct(array $settings)
    {
        if (!in_array('curl', get_loaded_extensions())) 
        {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
        
        if (!isset($settings['oauth_access_token'])
            || !isset($settings['oauth_access_token_secret'])
            || !isset($settings['consumer_key'])
            || !isset($settings['consumer_secret']))
        {
            throw new Exception('Make sure you are passing in the correct parameters');
        }

        $this->oauth_access_token = $settings['oauth_access_token'];
        $this->oauth_access_token_secret = $settings['oauth_access_token_secret'];
        $this->consumer_key = $settings['consumer_key'];
        $this->consumer_secret = $settings['consumer_secret'];
    }*/
    
    /**
     * Set postfields array, example: array('screen_name' => 'J7mbo')
     * 
     * @param array $array Array of parameters to send to API
     * 
     * @return TwitterAPIExchange Instance of self for method chaining
     */

    /**
     * @param array $array
     * @return Twitter_oAuth
     * @throws CException
     */
    public function setPostfields(array $array)
    {
        if (!is_null($this->getGetfield())) 
        {
            throw new CException(Yii::t('Twitter', 'You can only choose GET OR POST fields.'), 500);
        }
        
        if (isset($array['status']) && substr($array['status'], 0, 1) === '@')
        {
            $array['status'] = sprintf("\0%s", $array['status']);
        }
        $this->postfields = $array;
        return $this;
    }

    /**
     * @param $string
     * @return Twitter_oAuth
     * @throws CException
     */
    public function setGetfield($string)
    {
        if (!is_null($this->getPostfields())) 
        {
            throw new CException(Yii::t('Twitter', 'You can only choose GET OR POST fields.'), 500);
        }
        
        $search = array('#', ',', '+', ':');
        $replace = array('%23', '%2C', '%2B', '%3A');
        $string = str_replace($search, $replace, $string);  
        
        $this->getfield = $string;
        return $this;
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
    
    /**
     * Build the Oauth object using params set in construct and additionals
     * passed to this method. For v1.1, see: https://dev.twitter.com/docs/api/1.1
     * 
     * @param string $url The API url to use. Example: https://api.twitter.com/1.1/search/tweets.json
     * @param string $requestMethod Either POST or GET
     * @return \TwitterAPIExchange Instance of self for method chaining
     */
    public function buildOauth($url, $requestMethod)
    {
        if (!in_array(strtolower($requestMethod), array('post', 'get')))
        {
            throw new CException(Yii::t('Twitter', 'Request method must be either POST or GET'), 500);
        }
        
        $consumer_key = $this->consumer_key;
        $consumer_secret = $this->consumer_secret;
        $oauth_access_token = $this->oauth_access_token;
        $oauth_access_token_secret = $this->oauth_access_token_secret;
        
        $oauth = array( 
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );
        
        $getfield = $this->getGetfield();
        
        if (!is_null($getfield))
        {
            $getfields = str_replace('?', '', explode('&', $getfield));
            foreach ($getfields as $g)
            {
                $split = explode('=', $g);
                $oauth[$split[0]] = $split[1];
            }
        }
        
        $base_info = $this->buildBaseString($url, $requestMethod, $oauth);
        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;
        
        $this->url = $url;
        $this->oauth = $oauth;
        return $this;
    }

    /**
     *   Perform the actual data retrieval from the API
     * @param bool $return  If true, returns data
     * @return string json If $return param is true, returns json data
     * @throws CException
     */
    public function performRequest($return = true)
    {
        if (!is_bool($return)) 
        {
            throw new CException(Yii::t('Twitter', 'performRequest parameter must be true or false'), 500);
        }
        
        $header = array($this->buildAuthorizationHeader($this->oauth), 'Expect:');
        
        $getfield = $this->getGetfield();
        $postfields = $this->getPostfields();

        $options = array( 
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true
        );

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

        if ($return) {
            if ($this->returnAsArray == true){
                $this->setResponseData(CJSON::decode($this->response));
            } else {
                $this->setResponseData($this->response);
            }
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
     * 
     * @param array $oauth Array of oauth data generated by buildOauth()
     * 
     * @return string $return Header used by cURL for request
     */    
    private function buildAuthorizationHeader($oauth) 
    {
        $return = 'Authorization: OAuth ';
        $values = array();
        
        foreach($oauth as $key => $value)
        {
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        }
        
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
    public function getResponseData()
    {
        return $this->responseData;
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


}
