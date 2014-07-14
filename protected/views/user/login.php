<!-- Banner Starts Here -->
<div class="mainBanner">

</div>
<!-- Banner Ends Here -->

<!-- Container Starts Here -->
<div class="container">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>false,
        'enableAjaxValidation'=> false,
        'clientOptions' => array(
            'validateOnSubmit' => true
        ),
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));  ?>
        <div class="login-left transition">
            <div class="login-heading">SIGN IN</div>
            <div class="arrow"></div>
            <div class="login-description">You must log in to participate.</div>
            <div class="row">
                <div class="field">
                    <i class="login-icon"></i>
                    <input type="text" name="username" id="username" placeholder="Username" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="password-icon"></i>
                    <input type="password" name="password" id="password" placeholder="Password" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <input type="submit" name="submit" id="submit" value="Login" />
                    <span class="forgot-link">
                        <?php echo CHtml::link('<i class="how-it-works"></i> <span>Forgot password?</span>',
                            array('user/reset',
                                'lang'=>$this->siteParams['lang'],
                                'env'=>$this->siteParams['env'],
                                'phase'=>$this->siteParams['phase'])
                        ); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="login-splitter">
            <span class="arrow"></span>
        </div>
        <div class="login-right transition">
            <span>No account? No problem.</span>
            <span class="create-account">
                <?php echo CHtml::link('create an account',
                    array(
                        'user/register',
                        'lang'=>$siteParams['lang'],
                        'env'=>$siteParams['env'],
                        'phase'=>$siteParams['phase'],
                    ),
                    array('id'=>'create-account')
                ); ?>
            </span>
        </div>
    <?php $this->endWidget(); ?>
</div>
<!-- Container Ends Here -->