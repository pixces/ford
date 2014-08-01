<!-- Banner Starts Here -->
<div class="mainBanner">

</div>
<!-- Banner Ends Here -->

<!-- Container Starts Here -->
<div class="container">
    <!-- form id="register-form" method="post" action="" name="register-form" -->
    <div class="register-container">
        <div class="upload-block user-image transition">
            <div class="upload-img"><img src="<?php echo (isset($profile->profile_image)) ? $profile->profile_image : Yii::app()->request->baseUrl.'/images/user-icon.png'; ?>" /></div>
            <input type="file" id="my-file" class="hide" />
            <div id="select_file" class="hide"></div>
        </div>

        <div class="my-profile-fields-container">
            <div class="row">
                <div class="field">
                    <i class="name-icon"></i>
                    <input type="text" name="name" id="name" placeholder="<?=$profile->full_name; ?>" disabled />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="email-icon"></i>
                    <input type="text" name="email" id="email" placeholder="<?=$model->email; ?>" disabled />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="city-icon"></i>
                    <input type="text" name="city" id="city" placeholder="<?=$profile->city; ?>" disabled />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <i class="phone-icon"></i>
                    <input type="text" name="phone" id="phone" placeholder="<?=$profile->phone; ?>" disabled />
                </div>
            </div>
            <div class="row">
                <div class="field edit-details">
                    <?php echo CHtml::link('Edit Details',
                        array(
                            'user/edit',
                            'lang'=>$siteParams['lang'],
                            'env'=>$siteParams['env'],
                            'phase'=>$siteParams['phase'],
                        ),
                        array('id'=>'edit-details','class'=>'edit-details','data-user'=>Yii::app()->user->getId())
                    ); ?>
                </div>
            </div>
        </div>
    </div>
    <!--/form -->
</div>
<div class="arrow-down"></div>
<!-- Container Ends Here -->

<!-- message -->
<div class="my-profile-messages">
    <div class="message-blue">We have received your appeal and are waiting for our moderators to approve.</div>
</div>
<!-- message ends here -->

<!-- view your entries section -->
<div class="view-your-entries-container">
    <h3 class="view-your-entry-title">View Your Entries</h3>
    <div class="entries-container">
        <ul class="entries-ul">
            <?php foreach ($content as $celeb => $cont) { ?>
            <li class="entries-li" data-celeb="<?=$celeb; ?>" data-user="<?=$model->id; ?>">
                <!-- profile image -->
                <div class="profile-photo column <?=$celeb; ?>">
                    <div class="img">
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/<?=$cont['img']; ?>" alt="<?=$cont['name']; ?>" title="<?=$cont['name']; ?>">
                    </div>
                    <div class="head"><?=$cont['name']; ?></div>
                </div>
                <!-- profile image ends -->
                <!-- appeal content are starts -->
                <?php if (isset($cont['id']) && !empty($cont['id'])){ ?>
                    <?php if ($cont['status'] != 'pending'){ ?>
                        <?php if ($cont['status'] == 'rejected') { ?>
                            <!-- content rejected -->
                            <div class="rejected-appeal-text">
                                <div class="col-full-8">
                                    <h6>Sorry but your appeal has been rejected. Moderator’s message is as follows:</h6>
                                    <p><?=$cont['description']; ?></p>
                                </div>
                                <div class="col-full-4 rejected">
                                    <div class="img">
                                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rejected-icon.png" alt="Approved" title="Rejected">
                                    </div>
                                    Rejected
                                </div>
                            </div>
                            <button class="button orange make-an-appeal pull-right">Make Another Appeal</button>
                        <?php } else { ?>
                            <!-- content either approved / under moderation //-->
                            <div class="content two-third-ct column <?=$celeb; ?>-appeal-content-area">
                                <div class="content column">
                                    <p><?=$cont['description']; ?></p>
                                </div>
                                <?php
                                    $status_text = ($cont['status'] == 'under_review') ? 'under-moderation' : 'approved';
                                ?>
                                <div class="action-updates <?=$status_text; ?> column">
                                    <div class="img">
                                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/<?=$status_text; ?>.png" alt="<?=str_replace("-"," ", $status_text); ?>" title="<?=$cont['name']; ?>">
                                    </div>
                                    <?=str_replace("-"," ", $status_text); ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <!-- content not submitted for moderation -->
                        <div class="content two-third-ct column confirm-appeal-box">
                            <div class="confirm-appeal-text">
                                <div class="col-full-8"><?=$cont['description']; ?></div>
                                <div class="col-full-2">
                                    <div class="btn-ct">
                                        <div class="arrow-right"></div>
                                        <p>ONCE YOU SUBMIT YOUR ENTRY YOU CANNOT CHANGE IT.</p>
                                        <button class="button orange confirm-btn">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <!-- still awaiting submission -->
                    <div class="content two-third-ct column submit-appeal-box">
                        <div class="content column first-column">
                            <p class="make-appeal-text">You have not made an appeal yet.</p>
                        </div>
                        <div class="action-updates column second-column">
                            <button class="button orange make-an-appeal">Make An Appeal</button>
                        </div>
                    </div>
                <?php } ?>
                <!-- appeal content are ends -->
            </li>
            <?php } ?>
        </ul>
        <div class="view-other-entries-container">
            <button class="button Col25a6a0 view-other-entries-btn">View Other Entries</button>
        </div>
    </div>
</div>
<!-- view your entries section ends here -->

<!-- SYNC AppLink Starts Here -->
<?php $this->widget('SyncBanner'); ?>
<!-- SYNC AppLink Ends Here -->

<script>
    $(function () {
        var PROFILE_APPEAL = ProfileSubmitAppeal();
        PROFILE_APPEAL.init();
    });
</script>