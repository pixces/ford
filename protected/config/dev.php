<?php
/**
 * Created by IntelliJ IDEA.
 * User: zainulabdeen
 * Date: 29/01/14
 * Time: 9:08 PM
 * To change this template use File | Settings | File Templates.
 */

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'components' => array(
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=ford',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
                'enableParamLogging'=>true,
            ),
        ),
        'params'=>array(
            //this is used in contact page
            'API_URL'           => 'http://localhost:8888/fordAdmin/api/v1/',
            'SOCIAL_URL'        => 'http://localhost:8888/ford/social/',
            'APP_CALLBACK'      => "http://localhost:8888/ford/social/authenticate",
            'CAPTCHA_URL'       => 'http://localhost:8888/ford/user/checkCaptcha',
            'FB_APP_ID'         => '279339632155776',
            'FB_SECRET_KEY'     => 'e3069bc4b7f408376b6ffabe6c11d6d3',
            'TW_ACCESS_KEY'     => 'FYPI4SVSLTqgfH3lJSgKJg',
            'TW_ACCESS_SECRET'  => 'FVSWLSJYFYM65LQtl96FESqqZVwetfrLxDcYqKTU',
            'TW_CONSUMER_KEY'   => '23915432-1FRWXttHEqU7kjm9ffRZyrttkM7zKdcGfFQfBu5Ri',
            'TW_CONSUMER_SECRET'=> 'Gn6NcmK9u7GfhVYE1har4yfbaLgB5rFMJIuBZ9CWy51Tc',
            'GG_CLIENT_ID'      => '546631074723.apps.googleusercontent.com',
            'GG_CLIENT_SECRET'  => '6Oce1gVzCcI5j32zbY7xZ6Tx',
            'ugcGalleryId'      => 3,
        ),
        'modules'=>array(
            // uncomment the following to enable the Gii tool
            'gii'=>array(
                'class'=>'system.gii.GiiModule',
                'password'=>'p0s!t!0n2',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters'=>array('127.0.0.1','::1'),
            ),
        ),

    )
);