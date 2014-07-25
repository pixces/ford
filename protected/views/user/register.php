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
    ?>
        <input type="hidden" name="authenticated_from" id="authenticated_from" value="site">
        <input type="hidden" name="social_profile_pic" id="social_profile_pic" value="">

        <div class="register-container">
            <div class="register-heading">Register Now</div>
            <div class="arrow"></div>
            <div class="register-description">We need some details from you first. <br/>Get your details from</div>
            <div class="register-socialIcon">
                <ul>
                    <li><a href="javascript:socialPopup('facebook');"><i class="facebook"></i></a></li>
                    <li><a href="javascript:socialPopup('twitter');"><i class="twitter"></i></a></li>
                    <li class="last"><a href="javascript:socialPopup('google');"><i class="google"></i></a></li>
                </ul>
                <div class="register-or"><span>OR</span></div>
            </div>
            <div class="register-description">Enter them below <br/>
                <?php
                    print_r($form->errorSummary($model));
                    print_r($form->errorSummary($modelUserProfile));
                ?>
            </div>
            <div class="upload-block transition">
                <div class="upload-img"><img src="" id="preview" class="hide"></div>
                <input type="file" id="my-file" class="hide upload_image" name="UserProfiles[profile_image]" />
                <div id="select_file" class="hide"></div>
                <label id="upload-label">upload</label>
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
                    <!--i class="city-icon"></i -->
                            <select name="city" id="city" placeholder="City">
                                <option value="">Select City</option>
                                <option value="Bangalore">Bangalore</option>
                                <option value="Chennai">Chennai</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Kolkota">Kolkota</option>
                                <option value="Mumbai">Mumbai</option>
                            </select>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="phone-icon"></i>
                    <input type="text" name="UserProfile[phone]" id="phone" placeholder="Phone" />
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
                        <div class="captchaBlock">
				<img id="imgCaptcha" src="<?php echo Yii::app()->baseUrl."/user/createcaptcha"; ?>" alt="Captcha" />
			</div>
                        <div class="termsBlock">
                            <ul>
                                <li><input type="checkbox" id="agreement" class="agreement" checked="checked" name="termsAndCondition" /> <span>Terms &amp; Conditions</span></li>
                                <li><input type="checkbox" id="news_letter" class="news_letter" checked="checked" name="moreAboutSync" /> <span>I would like to know more about SYNC and AppLink</span></li>
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
<script type="text/javascript" src="js/vendor/jquery-validation.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript">
     $("#register-form").validate({
            rules: {
                name: {
                    required: true
                },
                email : {
                    required: true,
                    email: true
                },
                city : {
                  required: true    
                },
                phone : {
                  required: true      
                },
                password : {
                  required: true
                },
                cpassword : {
                  required: true,
                  equalTo : "#password"      
                },
                captcha : {
                  required: true      
                },
                termsAndCondition : {
                  required: true      
                }

            },
            messages: {
                name: {
                    required: "Enter your name"
                },
                email : {
                    required: "Enter your email",
                    email : "Enter a valied email address"
                },
                city : {
                  required: "Select a city"    
                },
                phone : {
                  required: "Enter your phone number"      
                },
                password : {
                  required: "Enter a password"       
                },
                cpassword : {
                  required: "Confirm your password"       
                },
                captcha : {
                  required: "Enter the verification code"       
                },
                termsAndCondition : {
                  required: "Please check the terms and conditions"      
                }
            },
            errorPlacement: function (error, element) {
                element.after( error );
            },
            submitHandler: function (form) {
                return false;
            }
        });
</script>

<script>
    $(function(){
        $("#refresh_captcha").click(function() {
            $("#imgCaptcha").attr("src", "<?=Yii::app()->baseUrl."/user/createcaptcha?"?>"+Math.random());
        });
    });
    function socialPopup(media){
        var baseUrl='<?=Yii::app()->baseUrl?>';
        var href= baseUrl+"/social/authenticate?media="+media+"&type=register&debug";
        window.open(href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400');

        setTimeout(function() {
            getUserInfo(media)
        }, 1500);
    }


    jQuery.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z]+$/);
    },"Only Characters Allowed.");

//    $("#register-form").validate({
//        rules: {
//            name: {
//                alpha: true,
//                required: true
//            },
//            email: {
//                required: true
//            },
//            city: {
//                required: true
//            },
//            phone: {
//                required: true,
//                number: true,
//                minlength: 10,
//                maxlength: 10
//            },
//            password: {
//                required: true,
//                minlength: 8,
//                maxlength: 16
//            },
//            cpassword: {
//                required: true ,
//                minlength: 8,
//                maxlength: 16,
//                equalTo: "#password"
//            },
//            captcha_code: {
//                required: true
//            },
//            agreement: {
//                required: true
//            }
//        },
//        messages: {
//            name: {
//                required: "Missing Name"
//            },
//            email: {
//                required: "Missing Email ID"
//            },
//            password: {
//                required: "Missing Password"
//            },
//            confirm_password: {
//                required: "Re-Enter Password"
//            },
//            phone: {
//                required: "Missing Mobile Number"
//            },
//            city: {
//                required: "Missing City"
//            },
//            captcha: {
//                required: "Required"
//            },
//            agreement: {
//                required: "Please accept terms and conditions "
//            }
//        },
//        errorPlacement: function (error, element) {
//            element.after( error );
//        },
//        submitHandler: function (form) {
//            console.info(form);
//            $.ajax({
//                url: CAPTCHA_URL + "?security_code=" + $.trim($("#captcha").val())
//            }).done(function(data) {
//                    if(data=='success'){
//                        form.submit();
//                    }else{
//                        $("#captcha_code").addClass('error');
//                        $('<label for="captcha_code" class="error" id="captcha-error">Invalid captcha</label>').insertAfter("#captcha_code");
//                    }
//                });
//        }
        /*
         submitHandler: function (form) {
         $.mobile.changePage('#success', {
         transition: "slide"
         });
         return false;
         } */
//    });

</script>
