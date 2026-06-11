<?php 
//-------------
?>
<div id="productInquiry" class="mfpbox mfp-with-anim mfp-hide">
	<div class="contact-form form-vertical p-lg-1">
		<div class="page-title"><h3>Product Inquiry</h3></div>
		<form method="post" action="writequestion" id="contact_form" class="contact-form">
			<input type="hidden" name="prodid" value="<?php echo $prodid?>">
			<div class="formFeilds">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<input type="text" id="ContactFormName" name="username" placeholder="Name" value="" required maxlength="120"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group">
							<input type="email" id="ContactFormEmail" name="email" placeholder="Email" value="" required maxlength="120" />
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group">
							<input type="tel" id="ContactFormPhone" name="phone" pattern="[0-9\-]*" placeholder="Phone Number" value="" required maxlength="10" />
						</div>
					</div>
				</div>
				<!-- <div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<input type="text" id="ContactFormSubject" name="contact[subject]" placeholder="Subject" value="" required />
						</div>
					</div>
				</div> -->
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<textarea rows="5" id="ContactFormMessage" name="question" placeholder="Message" required maxlength="200"></textarea>
						</div>
					</div>  
				</div>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<input type="text" id="ContactFormCode" name="captcha" placeholder="<?php $_SESSION['captchacode']=rand(0,9999); echo 'Enter Security Code: '.$_SESSION['captchacode'];?>" value="" required maxlength="10" /><br>
							<?php echo '&nbsp;&nbsp;Security Code: '.$_SESSION['captchacode'];?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<input type="submit" class="btn rounded w-100" value="Send Message" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>