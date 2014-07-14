		<!-- Banner Starts Here -->
		<div class="mainBanner">
					
		</div>
		<!-- Banner Ends Here -->
		
		<!-- Container Starts Here -->
		<div class="container">
			<form id="register-form" method="post" action="" name="register-form">
				<div class="register-container">
					<div class="upload-block user-image transition">
						<div class="upload-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" /></div>
						<input type="file" id="my-file" class="hide" />
						<div id="select_file" class="hide"></div>
					</div>
                    
                <div class="my-profile-fields-container">
					<div class="row">
						<div class="field">
							<i class="name-icon"></i>
							<input type="text" name="name" id="name" placeholder="Name" />
						</div>
					</div>
					<div class="row">
						<div class="field">
							<i class="email-icon"></i>
							<input type="text" name="email" id="email" placeholder="Email" />
						</div>
					</div>
					<div class="row">
						<div class="field">
							<i class="city-icon"></i>
							<input type="text" name="city" id="city" placeholder="City" />
						</div>
					</div>
					<div class="row">
						<div class="field">
							<i class="phone-icon"></i>
							<input type="text" name="phone" id="phone" placeholder="Phone" />
						</div>
					</div>
					<div class="row">
						<div class="field edit-details">
							<a href="edit-details.html" class="edit-details">Edit Details</a>
						</div>
					</div>
                 </div>   
                    
				</div>
			</form>
            
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
                <li class="entries-li">
                <div class="profile-photo column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rocky-mayur.png" alt="Rocky &amp; Mayur" title="Rocky &amp; Mayur"></div><div class="head">Rocky &amp; Mayur</div></div>
                <div class="content column"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</p></div>
                <div class="action-updates under-moderation column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/under-moderation.png" alt="Under Moderation" title="Rocky &amp; Mayur"></div> Under Moderation</div>
                </li>
                <li class="entries-li">
                <div class="profile-photo column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rocky-mayur.png" alt="Rocky &amp; Mayur" title="Rocky &amp; Mayur"></div><div class="head">Rocky &amp; Mayur</div></div>
                <div class="content column"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</p></div>
                <div class="action-updates approved column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/approved.png" alt="Approved" title="Approved"></div> Approved</div>
                </li>
                <li class="entries-li">
                <div class="profile-photo column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/gaurav-kapoor.png" alt="Gaurav Kapur" title="Gaurav Kapur"></div><div class="head">Gaurav Kapur</div></div>
                <div class="content column"><p class="make-appeal-text">You have not made an appeal yet.</p></div>
                <div class="action-updates column"><button class="button orange make-an-appeal">Make An Appeal</button></div>
                </li>
                <li class="entries-li">
                <div class="profile-photo column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/gaurav-kapoor.png" alt="Gaurav Kapur" title="Gaurav Kapur"></div><div class="head">Gaurav Kapur</div></div>
                <div class="content two-third-ct column"><div class="rejected-appeal-text"><div class="col-full-8"><h6>Sorry but your appeal has been rejected. Moderator’s message is as follows:</h6><p>“Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum,   massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus.”</p></div>
                    <div class="col-full-4 rejected"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rejected-icon.png" alt="Approved" title="Rejected"></div> Rejected</div>   </div>
                     <button class="button orange make-an-appeal pull-right">Make Another Appeal</button>
                    </div>
                   
                   
                </li>
                <li class="entries-li">
                <div class="profile-photo column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/anushka-dandekar.png" alt="Anushka Dandekar" title="Anushka Dandekar"></div><div class="head">Anushka Dandekar</div></div>
                <div class="content two-third-ct column"><textarea class="make-appeal-text-area">Make an appeal to Anushka Dandekar</textarea><div class="btn-ct"><div class="arrow-right"></div><button class="button orange make-an-appeal-submit">Submit</button></div></div>
                <div class="action-updates hide"><button class="button orange make-an-appeal">Make An Appeal</button></div>
                </li>
                <li class="entries-li">
                <div class="profile-photo column"><div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/anushka-dandekar.png" alt="Anushka Dandekar" title="Anushka Dandekar"></div><div class="head">Anushka Dandekar</div></div>
                <div class="content two-third-ct column">
                    <div class="confirm-appeal-text">
                    <div class="col-full-8">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget leo erat. Nam rutrum, massa ut mattis iaculis, ipsum felis tempus mauris, quis pulvinar purus ligula ac lectus. Pellentesque nisl ligula, suscipit laoreet leo ut, luctus pellentesque risus. Ut suscipit elit vitae.</div>
                    <div class="col-full-2">
                    <div class="btn-ct"><div class="arrow-right"></div><p>ONCE YOU SUBMIT YOUR ENTRY YOU CANNOT CHANGE IT.</p><button class="button orange">Confirm</button></div></div>  
                    </div>
                    </div>
                </li>
                </ul>
                <div class="view-other-entries-container"><button class="button Col25a6a0 view-other-entries-btn">View Other Entries</button></div>
            </div>
            
            </div>
        
        <!-- view your entries section ends here -->
        
        <!-- SYNC AppLink Starts Here -->
        <?php $this->widget('SyncBanner'); ?>
		<!-- SYNC AppLink Ends Here -->