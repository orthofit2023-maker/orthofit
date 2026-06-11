<!--Body Container-->
            <div id="page-content">   
                <!--Collection Banner-->
                <div class="collection-header">
                    <div class="collection-hero">
                        <div class="collection-hero__image"></div>
                        <div class="collection-hero__title-wrapper container">
                            <h1 class="collection-hero__title">Login | Create an account</h1>
                            <div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="/" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Login</span></div>
                        </div>
                    </div>
                </div>
                <!--End Collection Banner-->

                <!--Container-->
                <div class="container-fluid">
                    <!--Main Content-->
                    <div class="mainlogin-sliding my-5 py-0 py-lg-4">
                        <div class="row">
                            <div class="col-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 mx-auto">
								<?php if($_GET['errmsg']!=''){?>
								<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
									<i class="align-middle icon an an-user-expand icon-large me-2"></i><?php echo dbval($_GET['errmsg'])?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php }?>
                                <div class="row g-0 form-slider">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                        <!--Home slider-->
                                        <div class="slideshow slideshow-wrapper">
                                            <div class="home-slideshow">
                                                <div class="slide">
                                                    <div class="blur-up lazyload bg-size ratio ratio-16x9">
                                                        <img class="bg-img blur-up lazyload" data-src="assets/images/slideshow/demo15-banner1.jpg" src="assets/images/slideshow/demo15-banner1.jpg" alt="image" title="" />
                                                        <div class="container">
                                                            <div class="slideshow-content slideshow-overlay middle d-flex justify-content-center align-items-center">
                                                                <div class="slideshow-content-in text-center h-auto">
                                                                    <div class="wrap-caption animation style1 col-11 col-sm-8 p-4">
                                                                        <h3>Welcome to OrthofitMart</h3>
                                                                        <p>Orthofit has developed a unique approach where certified therapists conduct a detailed foot, ankle and lower limb biomechanics evaluation and scrutinize static and dynamic conditions.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="slide">
                                                    <div class="blur-up lazyload bg-size ratio ratio-16x9">
                                                        <img class="bg-img blur-up lazyload" data-src="assets/images/parallax/demo12-banner1.jpg" src="assets/images/parallax/demo12-banner1.jpg" alt="image" title="" />                 
                                                        <div class="container">
                                                            <div class="slideshow-content slideshow-overlay middle d-flex justify-content-center align-items-center">
                                                                <div class="slideshow-content-in text-center h-auto">
                                                                    <div class="wrap-caption animation style1 col-11 col-sm-8 p-4">
                                                                        <h3>Welcome to OrthofitMart</h3>
                                                                        <p>Orthofit has developed a unique approach where certified therapists conduct a detailed foot, ankle and lower limb biomechanics evaluation and scrutinize static and dynamic conditions.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--End Home slider-->
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                        <!-- Login Wrapper -->
                                        <div class="login-wrapper">
                                            <!-- Login Inner -->
                                            <div class="login-inner">
                                                <!-- Login Logo -->
                                                <a href="/" class="logo d-inline-block mb-4"><img src="assets/images/logo/dredge2026new.png" alt="logo" /></a>
                                                <!-- End Login Logo -->
                                                <!-- User Form -->
                                                <div class="user-loginforms">
                                                    <!-- Login Form -->
                                                    <form id="form-login" class="text-left user-form-login login-active" action="/login" method="post">
                                                        <h4>Login to your account</h4>
                                                        <div class="form-row">
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="email" placeholder="Email Address" name="email" required/>
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="password" placeholder="Password" name="passwd" required/>
                                                            </div>
                                                            <div class="form-group w-100 submit d-flex-center justify-content-between">
                                                                <button type="submit" class="btn btn-secondary rounded " name="login" id="login">Sign In</button><!-- signin-link -->
                                                                <a href="javascript:void(0);" class="btn-link fw-500 forgotpass-link">Forgot password?</a>
                                                            </div>
                                                            <div class="form-group w-100 text-center">
                                                                Not registered?<a href="/register" class="fw-500 ms-1 btn-link">Create an account</a>
                                                            </div>
                                                        </div>
                                                    </form> 
                                                    <!-- End Login Form -->
                                                    <!-- Forgot Password Form -->
                                                    <form id="form-forgot-password" class=" text-left user-form-forgot" action="setpassword" method="post">
                                                        <h4>Forgot password?</h4>
                                                        <p>Enter the email you're using for your account.</p>
                                                        <div class="form-row">
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="email" placeholder="Email Address" name="email" required/>
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <button type="submit" class="btn btn-primary rounded w-100">Reset password</button> <!--  forgoted-link -->
                                                            </div>
                                                            <div class="form-group w-100 text-center pt-3">
                                                                Go back to<a href="javascript:void(0);" class="fw-500 ms-1 btn-link back-to-login">Sign In</a>
                                                            </div>
                                                        </div>
                                                    </form> 
                                                    <!-- End Forgot Password Form -->
                                                    <!-- Sign Up Form -->
                                                    <!-- <form id="form-signup" class="text-left user-form-signup" action="register" method="post">
                                                        <h4>Register an Account</h4>
                                                        <div class="form-row">
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="text" placeholder="First Name" name="username" required />
                                                            </div>
															 <div class="form-group w-100">
                                                                <input class="form-control" type="text" placeholder="Last Name" name="lastname" required />
                                                            </div>
															<div class="form-group w-100">
                                                                <input class="form-control" type="number" placeholder="Contact Number" name="phone" required />
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="email" placeholder="Email Address" name="email" required />
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="password" placeholder="Password" name="passwd" required />
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <input class="form-control" type="password" placeholder="Confirm password" name="cpasswd" required />
                                                            </div>
															<div class="form-group w-100">
																<input type="text" class="form-control" name="captcha" placeholder="<?php $_SESSION['captchacode']=rand(0,9999); echo 'Enter Security Code: '.$_SESSION['captchacode'];?>" maxlength="4" required>
																<?php echo '&nbsp;&nbsp;Security Code: '.$_SESSION['captchacode'];?>
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <div class="customCheckbox cart_tearm">
                                                                    <input type="checkbox" class="form-check-input" id="agree" value="1" />
                                                                    <label for="agree">I agree the Terms and Conditions</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group w-100">
                                                                <button type="submit" class="btn btn-primary rounded w-100 register-link">Register</button>
                                                            </div>
                                                            <div class="form-group w-100 text-center pt-1">
                                                                Already have an account?<a href="javascript:void(0);" class="fw-500 ms-1 btn-link back-to-login">Sign In</a>
                                                            </div>
                                                        </div>
                                                    </form>  -->
                                                    <!-- End Sign Up Form -->
                                                    <!-- Registered -->
                                                    <div class="user-registered">
                                                        <svg class="check" width="150" height="150" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60"><path fill="#ffffff" d="M40.61,23.03L26.67,36.97L13.495,23.788c-1.146-1.147-1.359-2.936-0.504-4.314 c3.894-6.28,11.169-10.243,19.283-9.348c9.258,1.021,16.694,8.542,17.622,17.81c1.232,12.295-8.683,22.607-20.849,22.042 c-9.9-0.46-18.128-8.344-18.972-18.218c-0.292-3.416,0.276-6.673,1.51-9.578" /></svg>
                                                        <p class="successtext"><span class="fw-500">Thanks for signing up!</span> <br>Check your email for confirmation.</p>
                                                        <div class="form-group w-100 text-center pt-3">
                                                            Go back to<a href="javascript:void(0);" class="fw-500 ms-1 btn-link back-to-login">Sign In</a>
                                                        </div>
                                                    </div>
                                                    <!-- End Registered -->
                                                    <!-- Logined -->
                                                    <!-- <div class="use-logined">
                                                        <img class="profile-photo rounded-circle" src="assets/images/blog/recent-commnet.jpg" alt="profile" width="100" />
                                                        <h3 class="welcome text-capitalize mt-3 my-2">Welcome, Chris</h3>
                                                        <p class="successtext"><span class="fw-500">Login Successful!</span> <br>You have successfully signed into your account.</p>
                                                        Go back to<a href="javascript:void(0);" class="fw-500 ms-1 btn-link back-to-login">Sign In</a>
                                                    </div> -->
                                                    <!-- End Logined -->
                                                    <!-- Forgoted -->
                                                    <div class="use-forgoted">
                                                        <svg class="check" width="150" height="150" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60"><path fill="#ffffff" d="M40.61,23.03L26.67,36.97L13.495,23.788c-1.146-1.147-1.359-2.936-0.504-4.314 c3.894-6.28,11.169-10.243,19.283-9.348c9.258,1.021,16.694,8.542,17.622,17.81c1.232,12.295-8.683,22.607-20.849,22.042 c-9.9-0.46-18.128-8.344-18.972-18.218c-0.292-3.416,0.276-6.673,1.51-9.578" /></svg>
                                                        <p class="successtext"><span class="fw-500">Check your mailbox</span> <br>We've sent password reset instructions to your email address.</p>
                                                        <div class="form-group w-100 text-center pt-3">
                                                            Go back to<a href="javascript:void(0);" class="fw-500 ms-1 btn-link back-to-login">Sign In</a>
                                                        </div>
                                                    </div>
                                                    <!-- End Forgoted -->
                                                </div>
                                                <!-- End User Form -->
                                                <!-- Social Bottom -->
                                                <!-- <div class="socialbottom mt-4">
                                                    <h4 class="login-social mb-2 pb-1">Login with social media account</h4>
                                                    <div class="btn-social d-flex">
                                                        <button class="btn btn-facebook btn-block text-uppercase col" type="submit"><i class="icon an an-facebook"></i> <span class="d-none d-sm-flex">Facebook</span></button>
                                                        <button class="btn btn-google btn-block text-uppercase col" type="submit"><i class="icon an an-google"></i> <span class="d-none d-sm-flex">Google</span></button>
                                                        <button class="btn btn-twitter btn-block text-uppercase col" type="submit"><i class="icon an an-twitter"></i> <span class="d-none d-sm-flex">Twitter</span></button>
                                                    </div>
                                                </div> -->
                                                <!-- End Social Bottom -->
                                            </div>
                                            <!-- End Login Inner -->
                                        </div>
                                        <!-- End Login Wrapper -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Main Content-->
                </div>
                <!--End Container-->
            </div>
            <!--End Body Container-->
