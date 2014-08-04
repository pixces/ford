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

    <!-- Join The FACE-OFF Starts Here -->
    <div class="joinUs transition">JOIN THE FACE-OFF</div>
    <!-- Join The FACE-OFF Starts Here -->

    <!-- Participate Block Starts Here -->
    <div class="participateBlock">
        <div class="description">
            <div class="row-fluid">
				<div class="whiteBG transition">
					<div class="how-it-works-flow transition">
						<div class="image"></div>
						<div class="text">
							<ul>
								<li class="col-one">Choose one celeb, <br/>or pick all three</li>
								<li class="col-two">Be their <br/>co-passenger</li>
								<li class="col-three">Teach them a new skill <br/>with SYNC<sup>&reg;</sup> AppLink<sup>&trade;</sup></li>
								<li class="col-four">Win a Ford Fiesta <br/>or EcoSport</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
            <div class="heading transition">Familiar faces.Unfamiliar  territory.</div>
            <div class="sub-heading">3 subject matter experts are out to master a genre completely alien to them. They need help. Yours to be precise. Play the expert and help our celebs master a new game. If you succeed, you get to win the 2014 Ford Fiesta or Ecosport.  Consider it your guru dakshina.</div>
            <div class="boxLink">
                <?php echo CHtml::link('Participate now <i></i>',
                    array(
                        'user/login',
                        'lang'=>$siteParams['lang'],
                        'env'=>$siteParams['env'],
                        'phase'=>$siteParams['phase'],
                    )
                ); ?>
            </div>
            <div class="link">
                <?php echo CHtml::link('How it works?',
                    array(
                        'pages/display',
                        'lang'=>$siteParams['lang'],
                        'env'=>$siteParams['env'],
                        'phase'=>$siteParams['phase'],
                        'view'=>$this->nav['how-it-works']['page_name']
                    )
                ); ?>
			</div>
		</div>
        <div class="carImage">
			<div class="carImage-heading">Show them the way with MOBILE APPS <br/>ON SYNC<sup>&reg;</sup> APPLINK<sup>&trade;</sup>.</div>
			<div class="carImage-icon"></div>
            <div class="carImage-mobile hide"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/footer-car-image.png" alt="Fiesta/EcoSport" title="Fiesta/EcoSport" /></div>
            <div class="ford-ecosport">
                <span class="icon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ford-ecosport.png" alt="FORD ECOSPORT" title="FORD ECOSPORT" /></span>
                <span class="text"><a href="javascript:void(0)"><i></i> FORD ECOSPORT</a></span>
            </div>
			<div class="appSync"><a href="javascript:void(0)">SYNC<sup>&reg;</sup> with AppLink<sup>&trade;</sup> <i></i></a></div>
            <div class="ford-fiesta">
                <span class="icon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ford-fiesta.png" alt="FORD FIESTA" title="FORD FIESTA" /></span>
                <span class="text"><a href="javascript:void(0)">FORD FIESTA <i></i></a></span>
            </div>
        </div>
        <div class="arrowDown"></div>
    </div>
    <!-- Participate Block Starts Here -->

    <!-- Hot Spots Starts Here -->
    <div class="hotspots transition">
        <div class="hotspot col-first">
            <div class="img"><a rel="j2pF4Cx2COo" href="javascript:void(0)" class="playCelebVideo"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rocky-mayur_play.png" alt="Rocky &amp; Mayur" title="Rocky &amp; Mayur" /></a></div>
            <div class="icon"></div>
            <div class="head">Rocky &amp; Mayur</div>
            <div class="splitter"></div>
            <div class="body">Food experts they may be but can they carry a tune?  Give them a couple of lessons in singing. </div>
            <div class="foot"><a href="javascript:void(0)">NEED MUSICIANS</a></div>
        </div>
        <div class="hotspot col-second">
            <div class="img"><a rel="Byqp30gxWjo" href="javascript:void(0)" class="playCelebVideo"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/gaurav-kapoor_play.png" alt="Gaurav Kapoor" title="Gaurav Kapoor" /></a></div>
            <div class="icon"></div>
            <div class="head">Gaurav Kapoor</div>
            <div class="splitter"></div>
            <div class="body">Food renders this suave commentator speechless.  Can you help him cook up a storm?</div>
            <div class="foot"><a href="javascript:void(0)">NEEDs FOODIES</a></div>
        </div>
        <div class="hotspot col-third">
            <div class="img"><a rel="BgmxTLwIZwk" href="javascript:void(0)" class="playCelebVideo"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/anushka-dandekar_play.png" alt="Anushka Dandekar" title="Anushka Dandekar" /></a></div>
            <div class="icon"></div>
            <div class="head">Anushka Dandekar</div>
            <div class="splitter"></div>
            <div class="body">Can this beautiful diva take a wicket? Show her how to spin a ball.</div>
            <div class="foot"><a href="javascript:void(0)">NEEDS CRICKETERS</a></div>
        </div>
        <div class="arrowDown"></div>
    </div>
    <!-- Hot Spots Ends Here -->

    <!-- Slider Starts Here -->
    <div class="hotspotTabs">
        <div class="tabsHeading">
            <div class="head">TOP ENTRIES</div>
            <div class="readmore"><a href="javascript:void(0)">Browse all entries <i></i></a></div>
        </div>
        <div class="tabContaier">
            <div class="tabDetails">
                <div id="tab1" class="tabContents">
                    <div class="slider">
                        <span class="button-prev"></span>

                        <div class="slider-mask">
                            <div class="slider-content">
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
											<audio controls="controls">
												<source src="../images/horse.mp3" type="audio/mpeg" />
												<source src="../images/horse.ogg" type="audio/ogg" />
												Your browser does not support the audio element.
											</audio> 
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span class="button-next"></span>
                    </div>
                </div><!-- //tab1 -->
                <div id="tab2" class="tabContents">
                    <div class="slider">
                        <span class="button-prev"></span>

                        <div class="slider-mask">
                            <div class="slider-content">
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span class="button-next"></span>
                    </div>
                </div><!-- //tab1 -->
                <div id="tab3" class="tabContents">
                    <div class="slider">
                        <span class="button-prev"></span>

                        <div class="slider-mask">
                            <div class="slider-content">
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-block">
                                    <div class="slider-img"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-icon.png" alt="User Icon" title="User Icon" /></div>
                                    <div class="slider-splitter"></div>
                                    <div class="slider-description">
                                        <div class="the-title">
                                            <span class="name">Robin Tomar</span>
                                            <span class="location">Delhi</span>
                                        </div>
                                        <div class="the-content">
                                            Amazing place here in Pacific Grove, CA. I am coming back here again soob for sure. A little to romantic driving
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="button-next"></span>
                    </div>
                </div><!-- //tab1 -->
            </div>
            <ul>
                <li class="tab1"><a class="active" href="#tab1">Rocky &amp; Mayur</a></li>
                <li class="tab2"><a href="#tab2">Gaurav kapoor</a></li>
                <li class="tab3"><a href="#tab3">Anushka Dandekar</a></li>
            </ul><!-- //Tab buttons -->
        </div>
    </div>
    <!-- Slider Ends Here -->

    <!-- Participate And How it Works Starts Here -->
    <div class="hotspotLinks">
        <?php echo CHtml::link('Participate now',
            array(
                'user/login',
                'lang'=>$siteParams['lang'],
                'env'=>$siteParams['env'],
                'phase'=>$siteParams['phase'],
            ),
            array('class'=>'orangeboxLink')
        ); ?>
        <?php echo CHtml::link('How it works?',
            array(
                'pages/display',
                'lang'=>$siteParams['lang'],
                'env'=>$siteParams['env'],
                'phase'=>$siteParams['phase'],
                'view'=>$this->nav['how-it-works']['page_name']
            ),
            array('class'=>'blueboxLink')
        ); ?>
    </div>
    <!-- Participate And How it Works Ends Here -->

    <!-- SYNC AppLink Starts Here -->
    <?php $this->widget('SyncBanner'); ?>
    <!-- SYNC AppLink Ends Here -->