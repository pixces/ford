		<!-- Banner Starts Here -->
		<div class="mainBanner">
					
		</div>
		<!-- Banner Ends Here -->
		
		<!-- View All Entries Heading Starts Here -->
		<div class="view-entries-heading">
			VIEW ALL ENTRIES
		</div>
		<!-- View All Entries Heading Ends Here -->
		
		<!-- Sort View All Entries Starts Here -->
		<div class="sort-view-entries">
			<ul class="transition">
				<li data-type="all"><a href="javascript:void(0)" class="active"><i class="sortByall"></i> All <i class="arrow"></i></a></li>
				<li data-type="city"><a href="javascript:void(0)" class=""><i class="sortBycity"></i> city <i class="arrow"></i></a>
					<ul class="subMenu">
						<li>
							<label>Enter your city</label><input type="text" name="city" class="cityinput" id="autocomplete">
							<div id="cityinputValue"></div>
						</li>
					</ul>
				</li>
				<li data-type="text"><a href="javascript:void(0)" class=""><i class="sortBytext"></i> text <i class="arrow"></i></a></li>
				<li data-type="audio"><a href="javascript:void(0)" class=""><i class="sortByvoice"></i> voice <i class="arrow"></i></a></li>
				<li data-type="celebrity" class="sortCelebrity"><a href="javascript:void(0)" class=""><i class="sortBycelebrity"></i> celebrity <i class="arrow"></i></a>
					<ul class="subMenu">
						<li data-celeb="rocky">Rocky &amp; Mayur</li>
						<li data-celeb="gaurav">Gaurav Kapoor</li>
						<li data-celeb="anushka">Anushka Dandekar</li>
					</ul>
					<div id="celebrityinputValue"></div>
				</li>
			</ul>
		</div>
		<!-- Sort View All Entries Ends Here -->
		
		<!-- View All Entries Starts Here -->
		<div class="view-entries-blk transition">
			<div id="entries" class="divCenter">
			</div>	
		</div>
		<!-- View All Entries Ends Here -->

        <?php if ($viewMore){ ?>
		<!-- Load More Starts Here -->
		<div class="load-more">
			<input class="btn-load-more" type="button" value="Load more entries" data-offset="<?=$offset; ?>" data-limit="<?=$limit; ?>"/>
		</div>
		<!-- Load More Ends Here -->
        <?php } ?>
		
		<!-- SYNC AppLink Starts Here -->
        <?php $this->widget('SyncBanner'); ?>
		<!-- SYNC AppLink Ends Here -->

        <script type="text/javascript">
            // setup autocomplete function pulling from cities[] array
            var cities = [
                "Kolkata",
                "Chandigarh",
                "Delhi and NCR",
                "Ludhiana",
                "Jaipur",
                "Hyderabad",
                "Bangalore",
                "Cochin",
                "Chennai",
                "Ahmedabad",
                "Pune",
                "Mumbai",
                "Kathmandu ( New)"
            ];

            $('#autocomplete').autocomplete({
                lookup: cities
            });

        </script>
        <script>
            var galleryData = <?=$entries; ?>;
            var aCelebs = <?=json_encode(Yii::app()->params['celebrity']); ?>;
            $(function(){
                GALLERY.buildGallery();
            });
        </script>
