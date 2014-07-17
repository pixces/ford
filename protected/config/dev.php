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
            'API_URL'           => 'http://localhost:8888/Local/FRD/fordAdmin/api/v1/',
            'SOCIAL_URL'        => 'http://localhost:8888/Local/FRD/ford/social/',
            'APP_CALLBACK'      => "http://localhost:8888/Local/FRD/ford/social/authenticate",
            'CAPTCHA_URL'       => 'http://localhost:8888/Local/FRD/ford/user/checkCaptcha',
            'FB_APP_ID'         => '886363378059413',
            'FB_SECRET_KEY'     => '66d781ef45e8ee850efb01c789e26f8a',
            'TW_ACCESS_KEY'     => '6pGOfG9OShV8mx9vid69NDOpA',
            'TW_ACCESS_SECRET'  => 'n2EgTlwPe6Fvu0a7H6R8mAOEiEYrOa08qpB7Fw9EsD1Psn7c04',
            'TW_CONSUMER_KEY'   => '23915432-1FRWXttHEqU7kjm9ffRZyrttkM7zKdcGfFQfBu5Ri',
            'TW_CONSUMER_SECRET'=> 'Gn6NcmK9u7GfhVYE1har4yfbaLgB5rFMJIuBZ9CWy51Tc',
            'GG_CLIENT_ID'      => '167886537206-lorn40ojlllt9ssiumkqu2dh5k4cebgd.apps.googleusercontent.com',
            'GG_CLIENT_SECRET'  => 'UbOi9nP8yB3dZ_Wrix5Ob2FI',
            'ugcGalleryId'      => 1,
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