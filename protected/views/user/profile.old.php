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
							<input type="text" name="name" id="name" placeholder="<?=$profile->full_name; ?>" />
						</div>
					</div>
					<div class="row">
						<div class="field">
							<i class="email-icon"></i>
							<input type="text" name="email" id="email" placeholder="<?=$model->email; ?>" />
						</div>
					</div>
					<div class="row">
						<div class="field">
							<i class="city-icon"></i>
							<input type="text" name="city" id="city" placeholder="<?=$profile->city; ?>" />
						</div>
					</div>
					<div class="row">
						<div class="field">
							<i class="phone-icon"></i>
							<input type="text" name="phone" id="phone" placeholder="<?=$profile->phone; ?>" />
						</div>
					</div>
					<div class="row">
						<div class="field edit-details">
							<a href="edit-details.html" class="edit-details">Edit Details</a>
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
            <div class="message-blue">We have received your appeal and are waiting for our moderators to approve. </div>
        </div>
        <!-- message ends here -->
        
        <!-- view your entries section --> 
        <div class="view-your-entries-container">
            <h3 class="view-your-entry-title">View Your Entries</h3>
            <div class="entries-container">
            <ul class="entries-ul">
                <?php foreach ($content as $celeb => $cont) { ?>
                <li class="entries-li">
                    <!-- profile image -->
                    <div class="profile-photo column <?=$celeb; ?>">
                        <div class="img">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/<?=$cont['img']; ?>" alt="<?=$cont['name']; ?>" title="<?=$cont['name']; ?>">
                        </div>
                        <div class="head"><?=$cont['name']; ?></div>
                    </div>
                    <div class="<?=$celeb; ?>-appeal-content-area">
                        <!-- no content -->
                        <?php if (!isset($cont['content-text'])) { ?>
                            <div class="<?=$celeb; ?>-no-appeal-action" data-name="<?=$celeb; ?>">
                                <div class="content column"><p class="make-appeal-text">You have not made an appeal yet.</p></div>
                                <div class="action-updates column"><button class="button <?=$cont['color']; ?> make-an-appeal" data-name="<?=$celeb; ?>" data-color="<?=$cont['color']; ?>">Make An Appeal</button></div>
                            </div>
                        <?php } else { ?>
                            <!-- submission but pending submission for approval -->
                            <div class="appeal-confirm-action content two-third-ct column">
                                <div class="confirm-appeal-text">
                                    <div class="col-full-8">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</div>
                                    <div class="col-full-2">
                                        <div class="btn-ct">
                                            <div class="arrow-right"></div>
                                            <p>ONCE YOU SUBMIT YOUR ENTRY YOU CANNOT CHANGE IT.</p>
                                            <button class="button orange">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- submitted for approval &/or moderated -->
                            <!-- under moderation -->
                            <div class="content column"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</p></div>
                            <div class="action-updates under-moderation column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/under-moderation.png" alt="Under Moderation" title="Rocky &amp; Mayur"></div> Under Moderation</div>

                            <!-- approved -->
                            <div class="content column"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</p></div>
                            <div class="action-updates approved column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/approved.png" alt="Approved" title="Approved"></div> Approved</div>

                            <!-- rejected -->
                            <div class="content two-third-ct column"><div class="rejected-appeal-text"><div class="col-full-8"><h6>Sorry but your appeal has been rejected. Moderator’s message is as follows:</h6><p>“Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum,   massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus.”</p></div>
                                <div class="col-full-4 rejected"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rejected-icon.png" alt="Approved" title="Rejected"></div> Rejected</div></div>
                                <button class="button orange make-an-appeal pull-right">Make Another Appeal</button>
                            </div>
                        <?php } ?>
                    </div>
                </li>
                <?php } ?>
                </ul>
                <div class="view-other-entries-container"><button class="button Col25a6a0 view-other-entries-btn">View Other Entries</button></div>
            </div>
            </div>
        <!-- view your entries section ends here -->
        
        <!-- SYNC AppLink Starts Here -->
        <?php $this->widget('SyncBanner'); ?>
		<!-- SYNC AppLink Ends Here -->

        <!-- templates to populate display based on actions -->
        <div class="template hide">
        <div class="appeal-confirm-action content two-third-ct column">
            <div class="confirm-appeal-text">
                <div class="col-full-8">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</div>
                <div class="col-full-2">
                    <div class="btn-ct">
                        <div class="arrow-right"></div>
                        <p>ONCE YOU SUBMIT YOUR ENTRY YOU CANNOT CHANGE IT.</p>
                        <button class="button orange">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- submission form -->
        <div id="appeal-form" class="appeal-form-action content two-third-ct column">
            <form id="" class="appeal-form" name="appealForm" action="">
            <textarea id="appealComment" name="comment" class="make-appeal-text-area" data-name="" placeholder="Make an appeal to"></textarea>
            <div class="btn-ct">
                <div class="arrow-right"></div>
                <input type="submit" class="button make-an-appeal-submit" data-name="" name="submit" value="Submit">
            </div>
            </form>
        </div>
        </div>
        <!-- templates end -->