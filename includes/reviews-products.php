<section class="section product-slider pb-0"><a name="reviews"></a>
	<div class="container">
		<div class="row">
			<div class="section-header col-12">
				<h2 class="text-transform-none">Reviews</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="spr-header clearfix d-flex-center justify-content-between">
					<div class="product-review d-flex-center me-auto">
						<a class="reviewLink" href="#"><?php echo showrating($prodrating)?></a>
						<span class="spr-summary-actions-togglereviews ms-2">Based on <?php echo $numreviews?> reviews</span>
					</div>
					<div class="spr-summary-actions mt-3 mt-sm-0">
						<a href="#" class="spr-summary-actions-newreview write-review-btn btn rounded"><i class="icon an-1x an an-pencil-alt me-2"></i>Write a review</a>
					</div>
				</div>

				<form method="post" action="writereview" name="frmreview" class="product-review-form new-review-form mb-4" onsubmit="callrevsub()">
				<input type="hidden" name="prodid" value="<?php echo $prodid?>">
					<h4 class="spr-form-title text-uppercase">Write A Review</h4>
					<fieldset class="spr-form-contact">
						<div class="spr-form-contact-name form-group">
							<label class="spr-form-label" for="nickname">Name <span class="required">*</span></label>
							<input class="spr-form-input spr-form-input-text" id="nickname" type="text" name="username" placeholder="John smith" required value="<?php echo ($_SESSION['compid']>0 ? $_SESSION['name'] : '')?>" <?php echo ($_SESSION['compid']>0 ? 'readonly' : '')?>  maxlength="60"/>
						</div>
						<div class="spr-form-contact-email form-group">
							<label class="spr-form-label" for="email">Email <span class="required">*</span></label>
							<input class="spr-form-input spr-form-input-email " id="email" type="email" name="revemail" placeholder="info@example.com" required  value="<?php echo ($_SESSION['compid']>0 ? $_SESSION['email'] : '')?>" <?php echo ($_SESSION['compid']>0 ? 'readonly' : '')?> maxlength="120"/>
						</div>
						<div class="spr-form-review-rating form-group">
							<label class="spr-form-label">Rating</label>
							<div class="product-review pt-1">
								<div class="review-rating">
									<input type="radio" name="rating" id="rating-5" value="5" checked><label for="rating-5"></label>
									<input type="radio" name="rating" id="rating-4" value="4"><label for="rating-4"></label>
									<input type="radio" name="rating" id="rating-3" value="3"><label for="rating-3"></label>
									<input type="radio" name="rating" id="rating-2" value="2"><label for="rating-2"></label>
									<input type="radio" name="rating" id="rating-1" value="1"><label for="rating-1"></label>
								</div>
								<a class="reviewLink d-none" href="#"><i class="icon an an-star-o"></i><i class="icon an an-star-o mx-1"></i><i class="icon an an-star-o"></i><i class="icon an an-star-o mx-1"></i><i class="icon an an-star-o"></i></a>
							</div>
						</div>
						<div class="spr-form-review-title form-group">
							<label class="spr-form-label" for="review">Review Title </label>
							<input class="spr-form-input spr-form-input-text " id="review" type="text" name="revtitle" placeholder="Give your review a title" required  maxlength="120"/>
						</div>
						<div class="spr-form-review-body form-group">
							<label class="spr-form-label" for="message">Your Review <!-- <span class="spr-form-review-body-charactersremaining">(1500) characters remaining</span> --></label>
							<div class="spr-form-input">
								<textarea class="spr-form-input spr-form-input-textarea " id="message" name="review" rows="5" placeholder="Write your comments here" required  maxlength="200"></textarea>
							</div>
						</div>
					</fieldset>
					<div class="spr-form-actions clearfix">
						<input type="submit" class="btn btn-primary rounded spr-button spr-button-primary" value="Submit Review">
					</div>
				</form>
			</div>
			<?php
			if($numreviews>0){?>
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="spr-reviews">
					<h4 class="spr-form-title text-uppercase mb-3">Customer Reviews</h4>
					<div class="review-inner">
						<?php
						while($revrow=$revresult->fetch_array()){
						?>
						<div class="spr-review">
							<div class="spr-review-header">
								<span class="product-review spr-starratings"><span class="reviewLink"><?php showrating($revrow['rating']);?></span></span>
								<h5 class="spr-review-header-title mt-1"><?php echo dbval($revrow['revtitle']);?></h5>
								<span class="spr-review-header-byline">By <strong><?php echo dbval($revrow['username']);?></strong> <!-- on <strong><?php echo dbval($revrow['revdt']);?></strong> --></span>
							</div>
							<div class="spr-review-content">
								<p class="spr-review-content-body"><?php echo dbval($revrow['review']);?></p>
							</div>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
		</div>
	</div>
</section>
