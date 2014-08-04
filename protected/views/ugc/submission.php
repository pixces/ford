
        <!-- Banner Starts Here -->
		<div class="mainBanner">
			<div class="bannerContainer">
				<div class="bannerLogo transition"></div>
				<div class="playVideo transition" data-video-src="ky7oL1tWJ1U">
					<span>play <i class="arrow"></i></span>
				</div>
				<div class="arrowDown transition"></div>
			</div>
			
			<div class="videoContainer"></div>			
		</div>
		<!-- Banner Ends Here -->
		
		
		
		<!-- Participate Block Starts Here -->
		<div class="participateBlock">
			<div class="selection-congrats-message">
				<div class="congrats-message">Congrats! Your account’s been created. <br> Make your appeal.</div>
                
                <div class="choose-your-celebrity">
					<div class="choose-your-celebrity-heading">CHOOSE YOUR CELEBRITY</div>
					<div class="arrow"></div>
					<div class="choose-your-celebrity-description">You can make an appeal to <strong>all 3 celebs</strong> or just the ones you fancy.</div>
				</div>
                <div class="arrowDown"></div>
			</div>
			
		</div>
		<!-- Participate Block Starts Here -->
		
		<!-- Hot Spots Starts Here -->
		<div class="hotspots transition chooseCelebrity">
            <?php
                $placement = array('first','second','third');
                $t=0;
                foreach($content as $celeb => $data ) {
                ?>
			<div id="celeb-<?=$celeb; ?>" class="hotspot col-<?=$placement[$t]; ?>">
				<div class="img <?=isset($data['content']['title']) ? 'selected can-edit' : ''; ?>" data-submit-box="<?=$celeb; ?>-submit" id="<?=$celeb; ?>-celeb">
				<div class="edit-tick"><i></i><a href="javascript:void(0);">Edit</a></div>
				<div class="select-celeb"><a href="javascript:void(0);">Click to Select</a></div>
				<div class="black-transparent-hover"></div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/<?=$data['img']; ?>" alt="<?=$data['name']; ?>" title="<?=$data['name']; ?>" /><div class="make-an-appeal-txt">Make an Appeal</div></div>
				<div class="icon"></div>
				<div class="head"><?=$data['name']; ?></div>
				<div class="splitter"></div>
				<div class="body"><?=$data['signupText']; ?></div>
			</div>
            <?php $t++; } ?>
		</div>
		<!-- Hot Spots Ends Here -->
       
        <!-- hotspots comments section -->
        <?php
            $placement = array('first','second','third');
            $t=0;
            foreach($content as $celeb => $data ) { ?>
        <div class="Celebrity-Comments-Container" id="<?=$celeb; ?>-submit" style="display:none">
            <div class="col-width-80 <?=$data['color']; ?>-comments-container">
                <div class="col-width-80 textarea-ct">
                    <textarea id="<?=$celeb; ?>-comment" class="textarea"><?=(isset($data['description']) && !empty($data['content']['description']) ) ? $data['content']['description'] : 'Make an appeal to '.$data['name'].'...'; ?></textarea>
                </div>
                <div class="col-width-20 btn-ct"><div class="arrow-right"></div>
                	<button class="button big-btn-submit" data-submit-for="<?=$celeb; ?>-celeb" data-celeb-icon="<?=$placement[$t]; ?>-celeb-icon" data-celeb="<?=$celeb; ?>" data-user="<?=Yii::app()->user->getId(); ?>">Submit</button>
            	</div>
                </div>
            <div class="col-width-20">
                <div class="sayit-voice"><i></i><div class="sayit-voice-text"><span>Don’t feel like writing?</span> Say it with your voice. Call  1800000000</div></div>
                </div>
        </div>
        <?php $t++; } ?>

        <?php $display = ($userSubmissions == 3) ? 'block' : 'none'; ?>

        <div id="confirm-appeal-section" class="confirm-appeal-section" style="display:<?=$display; ?>">
        	<div class="arrowDown"></div>

            <?php if ($display != 'block') { ?>
        	<!-- make another apppeal starts -->
        	<div id="makeAnotherAppeal" class="make-another-appeal">
        			<div class="tick-image"></div>
        			<div class="text-info">
        				<div>Thank You for your submission!</div>
						<div>Remember, you can make upto 3 appeals(one appeal per celeb).</div>
        			</div>

        			<div class="celeb-select-box">
        					<div class="celeb-icons">
        						<a href="javascript:void(0)" id="first-celeb-icon" data-celeb = "first-celeb" class="celeb-icon"></a>
        						<a href="javascript:void(0)" id="second-celeb-icon" data-celeb = "second-celeb" class="celeb-icon"></a>
        						<a href="javascript:void(0)" id="third-celeb-icon" data-celeb = "third-celeb" class="celeb-icon"></a>
        					</div>
							<div>
        						<strong>Click on the remaining celebs.</strong>
        					</div>

        					<div class="or-divider">
        						<div class="or-line"></div>
        						<div class="or-text">OR</div>
        					</div>
        			</div>

        			<div class="btn-box">
        				<a href="javascript:void(0)" class="confirm-btn" id="done-btn" data-user="<?=Yii::app()->user->getId(); ?>">No, I'm Done</a>
        			</div>
        	</div>
        	<!-- make another apppeal ends -->
            <?php } ?>

        	<!-- confirm appeal starts  -->
        	<div id="confirmAppeal" class="confirm-appeal">
        			<div class="tick-image"></div>
        			<div class="text-info">
        				<strong>Thank you.</strong>
						<div>Confirm your appeal(s) by clicking on the button below.</div>
        			</div>

        			<div class="btn-box">
                        <a href="javascript:void(0)" class="confirm-btn" id="confirm-btn" data-user="<?=Yii::app()->user->getId(); ?>">Confirm</a>
                        <?php
                        /*echo CHtml::link('Confirm',
                            array(
                                'ugc/confirm',
                                'lang'=>$siteParams['lang'],
                                'env'=>$siteParams['env'],
                                'phase'=>$siteParams['phase'],
                            ),
                            array('id'=>'confirm-btn','class'=>'confirm-btn','data-user'=>Yii::app()->user->getId())
                        );*/
                        ?>
        				<div class="confirm-info">
        					Once you click confirm you cannot edit your appeals.
        				</div>
        			</div>
        	</div>
        	<!-- confirm appeal ends -->
        </div>
        <!-- hotspots comments section -->

		<!-- SYNC AppLink Starts Here -->
		<div class="SYNCBanner">
			<div class="SYNCRight transition">
				<div class="img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/footer-sync-applink-text.png" alt="SYNC AppLink" title="SYNC AppLink" /></div>
				<div class="content">See all the great things you can do when SYNC™ and your phone get together.</div>
				<div class="boxLink"><a href="javascript:void(0)">Know More</a></div>
			</div>
		</div>
		<!-- SYNC AppLink Ends Here -->

<script>
	$(function(){
		CelebSubmit();
	});
</script>
