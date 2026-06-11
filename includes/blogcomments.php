<!-- Article Comment -->
<div class="blog-comment">
	<h2 class="my-4">Comments (3)</h2>
	<ol class="comments-list comments-list--level--0 list-unstyled">
		<li class="comments-list__item">
			<div class="comment d-flex">
				<div class="comment__avatar flex-shrink-0"><img class="rounded" src="assets/images/avatar-img1.jpg" alt="image" /></div>
				<div class="comment__content flex-grow-1">
					<div class="comment__header"><div class="comment__author">Lorem Ipsum</div></div>
					<div class="comment__text">Aliquam ullamcorper elementum sagittis. Etiam lacus lacus, mollis in mattis in, vehicula eu nulla. Nulla nec tellus pellentesque.</div>
					<div class="d-flex-center mt-2">
						<div class="comment__date me-auto clr-555">November 30, 2021</div>
						<div class="comment__reply ms-auto"><button type="button" class="btn btn-xs btn-light"><i class="icon an an-reply me-2"></i>Reply</button></div>
					</div>
				</div>
			</div>
			<div class="comment-list__children">
				<ol class="comments-list comments-list--level--1 list-unstyled">
					<li class="comments-list__item">
						<div class="comment d-flex">
							<div class="comment__avatar flex-shrink-0"><img class="rounded" src="assets/images/avatar-img2.jpg" alt="image"></div>
							<div class="comment__content flex-grow-1">
								<div class="comment__header"><div class="comment__author">Admin</div></div>
								<div class="comment__text">Ut vitae finibus nisl, suscipit porttitor urna. Integer efficitur efficitur velit non pulvinar. Aliquam blandit volutpat arcu vel tristique. Integer commodo ligula id augue tincidunt faucibus.</div>
								<div class="d-flex-center mt-2">
									<div class="comment__date me-auto clr-555">November 30, 2021</div>
									<div class="comment__reply ms-auto"><button type="button" class="btn btn-xs btn-light"><i class="icon an an-reply me-2"></i>Reply</button></div>
								</div>
							</div>
						</div>
					</li>
				</ol>
			</div>
		</li>
		<li class="comments-list__item">
			<div class="comment d-flex">
				<div class="comment__avatar flex-shrink-0"><img class="rounded" src="assets/images/avatar-img3.jpg" alt="image"></div>
				<div class="comment__content flex-grow-1">
					<div class="comment__header"><div class="comment__author">Ryan Ford</div></div>
					<div class="comment__text">Nullam at varius sapien. Sed sit amet condimentum elit.</div>
					<div class="d-flex-center mt-2">
						<div class="comment__date me-auto clr-555">November 30, 2021</div>
						<div class="comment__reply ms-auto"><button type="button" class="btn btn-xs btn-light"><i class="icon an an-reply me-2"></i>Reply</button></div>
					</div>
				</div>
			</div>
		</li>
	</ol>
</div>
<!-- Article Form -->
<div class="formFeilds contact-form form-vertical">
	<form method="post" action="#" id="comment_form" accept-charset="UTF-8" class="comment-form">
		<h2 class="mb-3">Leave a comment</h2>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="ContactFormName">Name</label>
					<input type="text" id="ContactFormName" name="contact[name]" placeholder="" value="" required />
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="ContactFormEmail">Email</label>
					<input type="email" id="ContactFormEmail" name="contact[email]" placeholder="" value="" required />                        	
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="ContactFormMessage">Message</label>
					<textarea rows="10" id="ContactFormMessage" name="contact[body]" placeholder="" required></textarea>
				</div>
			</div>  
		</div>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<p class="fine-print mt-1 mb-4"><i>Please note, comments must be approved before they are published</i></p>
				<input type="submit" class="btn btn-lg rounded w-100" value="Post comment">
			</div>
		</div>
	</form>
</div>