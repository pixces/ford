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
				<li><a href="javascript:void(0)" class="active"><i class="sortByall"></i> All <i class="arrow"></i></a></li>
				<li><a href="javascript:void(0)" class=""><i class="sortBycity"></i> city <i class="arrow"></i></a>
					<ul class="subMenu">
						<li>
							<label>Enter your city</label><input type="text" name="city" class="cityinput" id="autocomplete">
							<div id="cityinputValue"></div>
						</li>
					</ul>
				</li>
				<li><a href="javascript:void(0)" class=""><i class="sortBytext"></i> text <i class="arrow"></i></a></li>
				<li><a href="javascript:void(0)" class=""><i class="sortByvoice"></i> voice <i class="arrow"></i></a></li>
				<li class="sortCelebrity"><a href="javascript:void(0)" class=""><i class="sortBycelebrity"></i> celebrity <i class="arrow"></i></a>
					<ul class="subMenu">
						<li>Rocky &amp; Mayur</li>
						<li>Gaurav Kapoor</li>
						<li>Anushka Dandekar</li>
					</ul>
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
		
		<!-- Load More Starts Here -->
		<div class="load-more">
			<input type="button" value="Load more entries" />
		</div>
		<!-- Load More Ends Here -->
		
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
