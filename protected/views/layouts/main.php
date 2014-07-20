<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Ford</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/normalize.css" type='text/css' />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font.css" type='text/css' />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/common.css" type='text/css' />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/media-queries.css" type='text/css' />
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" type='text/css' />
    <![endif]-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery.validate.min.js"></script>
    <script>
        var API_HOST = '<?=Yii::app()->params['API_URL']; ?>';
        var SOCIAL_HOST = '<?=Yii::app()->params['SOCIAL_URL']; ?>';
        var UGC_GALLERY_ID = '<?=Yii::app()->params['ugcGalleryId']; ?>';
        var CAPTCHA_URL = '<?=Yii::app()->params['CAPTCHA_URL']; ?>';
        var SITE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
    </script>

</head>
<body class="<?=$this->page_name; ?>">
<!-- Overlay Starts Here -->
<div class="overlay"></div>
<!-- Overlay Ends Here -->
<div class="wrapper">
<!-- Header Starts Here -->
<div class="header">
    <!-- Logo Starts Here -->
    <div class="logo">
        <div class="ford-logo"><a href="<?=$this->nav['home']['url']; ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ford-logo.png" alt="Ford Logo" title="Ford Logo" /></div>
        <div class="logo-splitter"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo-splitter.png" alt="Logo Splitter" title="Logo Splitter" /></div>
        <div class="sync-link-logo"><a href="<?=$this->nav['sync-app']['url']; ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/sync-link-logo.png" alt="Sync Link Logo" title="Sync Link Logo" /></a></div>
    </div>
    <!-- Logo Ends Here -->
    <!-- Navigation Starts Here -->
    <div class="mainmenu">
        <ul>
            <li class="">
                <?php echo CHtml::link('<i class="entries"></i> <span>entries</span>',
                    array('gallery/index',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'])
                ); ?>
            </li>
            <li class="second">
                <?php echo CHtml::link('<i class="how-it-works"></i> <span>how it works</span>',
                    array('pages/display',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'],
                        'view'=>$this->nav['how-it-works']['page_name'])
                ); ?>
            </li>
            <li class="">
                <?php echo CHtml::link('<i class="our-celebs"></i> <span>our celebs</span>',
                    array('pages/display',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'],
                        'view'=>$this->nav['our-celebs']['page_name'])
                ); ?>
            </li>
            <li class="last">
                <?php echo CHtml::link('<i class="our-cars"></i> <span>our cars</span>',
                    array('pages/display',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'],
                        'view'=>$this->nav['our-cars']['page_name'])
                ); ?>
            </li>
        </ul>
    </div>
    <!-- Navigation Ends Here -->
    <!-- Mobile Menu Starts Here -->
    <div class="navigationIcon hide"></div>
    <!-- Mobile Menu Ends Here -->
    <!-- Login / Register Starts Here -->
    <div class="navUser">
        <?php if (Yii::app()->user->isGuest) { ?>
        <i class="loginUser"></i>
        <?php echo CHtml::link('Login',
            array('user/login',
                'lang'=>$this->siteParams['lang'],
                'env'=>$this->siteParams['env'],
                'phase'=>$this->siteParams['phase'])
        ); ?>
        /
        <?php echo CHtml::link('Register',
            array('user/register',
                'lang'=>$this->siteParams['lang'],
                'env'=>$this->siteParams['env'],
                'phase'=>$this->siteParams['phase'])
        ); ?>
        <?php } else { ?>
        <i class="loginUser"></i>
        <?php echo CHtml::link('MyProfile',
            array('user/profile',
                'lang'=>$this->siteParams['lang'],
                'env'=>$this->siteParams['env'],
                'phase'=>$this->siteParams['phase'])
        ); ?>
        /
        <?php echo CHtml::link('Logout',
            array('user/logout',
                'lang'=>$this->siteParams['lang'],
                'env'=>$this->siteParams['env'],
                'phase'=>$this->siteParams['phase'])
        ); ?>
        <?php } ?>
    </div>
    <!-- Login / Register Ends Here -->
    <!-- SYN AppLink Starts Here -->
    <div class="synApplink">
        <a href="javascript:void(0)"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/sync-applink-text.png" alt="SYN AppLink" title="SYN AppLink" /></a>
    </div>
    <!-- SYN AppLink Ends Here -->
</div>
<!-- Header Ends Here -->

<!-- page content gets added here -->
<?php echo $content; ?>
<!-- end of pahe content -->

<!-- Footer Block Starts Here -->
<div class="footer-wrapper">

    <!-- Footer Social Icon Starts Here -->
    <div class="socialIcon">
        <div class="socialMediaIcon">
            <ul>
                <li><i class="facebook"></i></li>
                <li><i class="twitter"></i></li>
                <li><i class="tube"></i></li>
                <li><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/social-links.png" alt="Social Icon" title="Social Icon" /></li>
            </ul>
        </div>
        <div class="img">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/footer-car-image.png" alt="Fiesta/EcoSport" title="Fiesta/EcoSport" /> <i class="doubleArrow"></i>
        </div>
    </div>
    <!-- Footer Social Icon Ends Here -->

    <!-- Footer Links Starts Here -->
    <div class="footer-links">
        <div class="ford-go-further"></div>
        <ul>
            <li class="first">
                <?php echo CHtml::link('About',
                    array('pages/display',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'],
                        'view'=>$this->nav['about']['page_name'])
                ); ?>
            </li>
            <li>
                <?php echo CHtml::link('Privacy',
                    array('pages/display',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'],
                        'view'=>$this->nav['privacy']['page_name'])
                ); ?>
            </li>
            <li>
                <?php echo CHtml::link('Terms',
                    array('pages/display',
                        'lang'=>$this->siteParams['lang'],
                        'env'=>$this->siteParams['env'],
                        'phase'=>$this->siteParams['phase'],
                        'view'=>$this->nav['terms-conditions']['page_name'])
                ); ?>
            </li>
        </ul>
        <p class="copyright">2014 Ford India Private Limited.All rights reserved</p>
    </div>
    <!-- Footer Links Ends Here -->

</div>
<!-- Footer Block Ends Here -->
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery.autocomplete.min.js"></script>
</body>
</html>