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
                'connectionString' => 'mysql:host=emporiadb3-1am.clunjnqjscmn.ap-southeast-1.rds.amazonaws.com;dbname=staging',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'e9Fh4ynBqV3U6Kd',
                'charset' => 'utf8',
            ),
        ),
        'params'=>array(
            //this is used in contact page
            'API_URL'           => 'https://cnkstgadmin.position2.com/api/v1/',
            'SITE_URL'          => 'http://cnk.position2.com/',
            'SOCIAL_URL'        => 'http://cnk.position2.com/social/',
            'APP_CALLBACK'      => "http://cnk.position2.com/social/authenticate",
            'CAPTCHA_URL'       => 'http://cnk.position2.com/user/checkcaptcha',
            // Facebook
            //'FB_APP_ID'         => '230578270399769',
            //'FB_SECRET_KEY'     => '064254a344eab45cc67774e3e42383f2',
            'FB_APP_ID'         => '230578270399769',
            'FB_SECRET_KEY'     => '064254a344eab45cc67774e3e42383f2',
            // Twitter
            'TW_ACCESS_KEY'     => 'xvuelGDNGX67CwOkVYO00A',
            'TW_ACCESS_SECRET'  => 'voOfiaxlZvj690c72jRnN6uPJJpWucKKsw1XIpo',
            'TW_CONSUMER_KEY'   => "23915432-atNjQgsqPjl3kPnvdcmNqW7yy4P0L3DrZs4PgoWc9",
            'TW_CONSUMER_SECRET'=> "mNRK05J4LesZINZX0LVL6ljJFx0b3m6TqrLV86V9VAO2o",
            // Google (Youtube/Plus/ OR any services from Google)
            'GG_CLIENT_ID'      => "598803086625-djn90os33s99kcfa9dva8gq75p8vbc1o.apps.googleusercontent.com",
            'GG_CLIENT_SECRET'  => "mGE6w0az-Yo2Pvjl6t0Mo1w_",
            // Instagram
            'IG_CLIENT_ID'      => "9bda24a5fb15417980ec2bdd3d0fc1f3",
            'IG_CLIENT_SECRET'  => "c3f4350208f24b21b7cc23cdba5aa80c",
            // Flickr
            'FR_CLIENT_ID'      => "08ddf325e321662eb8a194edc468dffa",
            'FR_CLIENT_SECRET'  => "7642fa868e4743fa",
            'ugcGalleryId'      => 4,
        ),
    )
);