<!-- Banner Starts Here -->
<div class="mainBanner">

</div>
<!-- Banner Ends Here -->

<!-- Container Starts Here -->
<div class="container">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'register-form',
        'enableClientValidation'=>false,
        'enableAjaxValidation'=> false,
        'clientOptions' => array(
            'validateOnSubmit' => true
        ),
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));
    print_r($form->errorSummary($model));
    print_r($form->errorSummary($modelUserProfile));
    ?>
        <div class="register-container">
            <div class="register-heading">Register Now</div>
            <div class="arrow"></div>
            <div class="register-description">We need some details from you first. <br/>Get your details from</div>
            <div class="register-socialIcon">
                <ul>
                    <li><a href="javascript:void(0)"><i class="facebook"></i></a></li>
                    <li><a href="javascript:void(0)"><i class="twitter"></i></a></li>
                    <li class="last"><a href="javascript:void(0)"><i class="google"></i></a></li>
                </ul>
                <div class="register-or"><span>OR</span></div>
            </div>
            <div class="register-description">Enter them below</div>
            <div class="upload-block transition">
                <div class="upload-img"></div>
                <input type="file" id="my-file" class="hide upload_image" name="UserProfiles[profile_image]" />
                <div id="select_file" class="hide"></div>
                <label>Change</label>
            </div>
            <div class="row">
                <div class="field">
                    <i class="name-icon"></i>
                    <input type="text" name="user[name]" id="name" placeholder="Name" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="email-icon"></i>
                    <input type="text" name="user[email]" id="email" placeholder="Email" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="city-icon"></i>
                    <input type="text" name="UserProfiles[city]" id="city" placeholder="City" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="phone-icon"></i>
                    <input type="text" name="UserProfiles[phone]" id="phone" placeholder="Phone" />
                </div>
            </div>
            <div class="note">Minimum 8-16 characters</div>
            <div class="row">
                <div class="field">
                    <i class="password-icon"></i>
                    <input type="password" name="user[password]" id="password" placeholder="Password" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="password-icon"></i>
                    <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <input type="text" name="captcha" id="captcha" placeholder="Verification Code" />
                    <div class="row rowCaptcha">
                        <div class="captchaBlock"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/captcha-img.jpg" alt="Captcha" title="Captcha" /></div>
                        <div class="termsBlock">
                            <ul>
                                <li><input type="checkbox" /> <span>Terms &amp; Conditions</span></li>
                                <li><input type="checkbox" /> <span>I would like to know more about SYNC and AppLink</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <input type="submit" name="submit" id="submit" value="Create an account" />
                    <span class="login-link">Already have an account? &nbsp;&nbsp; <?php echo CHtml::link('Login',
                            array('user/login',
                                'lang'=>$this->siteParams['lang'],
                                'env'=>$this->siteParams['env'],
                                'phase'=>$this->siteParams['phase'])
                        ); ?></span>
                </div>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>
<!-- Container Ends Here -->