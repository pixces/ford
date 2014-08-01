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
    <input type="hidden" name="social_profile_pic" id="social_profile_pic" value="">
    <input type="hidden" name="authenticated_from" id="authenticated_from" value="site">

        <div class="register-container">
            <div class="register-heading">View &amp; Edit Details</div>
            <div class="arrow"></div>
            <div class="profile-splitter"></div>

            <?php
            print_r($form->errorSummary($model));
            print_r($form->errorSummary($modelUserProfile));
            ?>

            <div class="upload-block transition">
                <div class="upload-img"><img src="<?php echo (isset($modelUserProfile->profile_image)) ? $modelUserProfile->profile_image : Yii::app()->request->baseUrl.'/images/user-icon.png'; ?>" id="preview" class="hide"></div>
                <input type="file" id="my-file" class="hide upload_image" name="UserProfiles[profile_image]" />
                <div id="select_file" class="hide"></div>
                <label id="upload-label">Change</label>
            </div>
            <div class="row">
                <div class="field">
                    <i class="name-icon"></i>
                    <input type="text" name="name" id="name" placeholder="Name" value="<?=$modelUserProfile->full_name; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="email-icon"></i>
                    <input type="text" name="email" id="email" placeholder="Email" value="<?=$model->email; ?>" disabled/>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <!--i class="city-icon"></i -->
                    <select id="city" name="city">
                        <option value="">Select City</option>
                        <option value="Bangalore" <?=($modelUserProfile->city == 'Bangalore') ? 'selected' : ''; ?> >Bangalore</option>
                        <option value="Chennai" <?=($modelUserProfile->city == 'Chennai') ? 'selected' : ''; ?>>Chennai</option>
                        <option value="Delhi" <?=($modelUserProfile->city == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                        <option value="Kolkota" <?=($modelUserProfile->city == 'Kolkata') ? 'selected' : ''; ?>>Kolkota</option>
                        <option value="Mumbai" <?=($modelUserProfile->city == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="phone-icon"></i>
                    <input type="text" name="phone" id="phone" placeholder="Phone" value="<?=$modelUserProfile->phone; ?>" />
                </div>
            </div>
            <!--
            <div class="note">Minimum XXX characters</div>
            <div class="row">
                <div class="field">
                    <i class="password-icon"></i>
                    <input type="password" name="password" id="password" placeholder="Password"/>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="password-icon"></i>
                    <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password"/>
                </div>
            </div>
            -->
            <div class="row">
                <div class="field">
                    <input type="submit" name="submit" id="submit" value="Save changes"/>
                </div>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>
<!-- Container Ends Here -->
<script type="text/javascript">
    $("#register-form").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            city: {
                required: true
            },
            phone: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Enter your name"
            },
            email: {
                required: "Enter your email",
                email: "Enter a valied email address"
            },
            city: {
                required: "Select a city"
            },
            phone: {
                required: "Enter your phone number"
            }
        },
        errorPlacement: function (error, element) {
            element.after(error);
        },
        submitHandler: function (form) {
            $(form).ajaxSubmit();
        }
    });
</script>