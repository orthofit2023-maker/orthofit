<?php
if($_GET['phone']!=''){
	$country=inpval($_GET['country']);
	$state=inpval($_GET['state']);
	$city=inpval($_GET['city']);
	$zipcode=inpval($_GET['zipcode']);
	$countryid=inpval($_GET['countryid']);
	$phone=inpval($_GET['phone']);
}else{
	//$posturl="https://geolocation-db.com/json/d802faa0-10bd-11ec-b2fe-47a0872c6708/".$_SERVER['REMOTE_ADDR'];

	$posturl="https://api.ipdata.co/".$_SERVER['REMOTE_ADDR']."?api-key=$ipdataapi&fields=city,region,country_name,country_code,latitude,longitude,postal,calling_code";

	$ch = curl_init($posturl);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$response = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($response);
	//print_r($data);
	$countrycode=inpval($data->country_code);
	$country=inpval($data->country_name);
	$state=inpval($data->region);
	$city=inpval($data->city);
	$zipcode=inpval($data->postal);
	$phone="+".$data->calling_code;
	if($countrycode!=""){
		$rsdata= query_first("select countryid from ccd9country where countrycode='$countrycode'");
		if ($rsdata['countryid']>0){
			$countryid =$rsdata['countryid'];
		}
	}
}

?>
<!--Body Container-->
<div id="page-content">
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h1 class="collection-hero__title">Create An Account</h1>
				<div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="/" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Create An Account</span></div>
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<!--Container-->
	<div class="container">
		<!--Main Content-->
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 box mt-2 mt-lg-5">	
				<h3 class="h4 text-uppercase mb-3">Personal Information</h3>
				<form method="post" action="/register" accept-charset="UTF-8" class="customer-form">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerFirstName" class="d-none">First Name <span class="required">*</span></label>
								<input id="CustomerFirstName" value="<?php echo $username?>" type="text" name="username" placeholder="First Name" required />
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerLastName" class="d-none">Last Name <span class="required">*</span></label>
								<input id="CustomerLastName" type="text" value="<?php echo $lastname?>" name="lastname" placeholder="Last Name" required />                       	
							</div>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerEmail" class="d-none">Contact Number <span class="required">*</span></label>
								<input id="CustomerEmail" type="number" value="<?php echo $phone?>" name="phone" placeholder="Contact Number" required maxlength="10"/>                        	
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerEmail" class="d-none">Email Address <span class="required">*</span></label>
								<input id="CustomerEmail" type="email" value="<?php echo $email?>" name="email" placeholder="Email" required />                        	
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerAddress1" class="d-none">Address 1 <span class="required">*</span></label>
								<input id="CustomerAddress1" type="text" value="<?php echo $address?>"  name="address" placeholder="Address 1" required />
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerAddress2" class="d-none">Address 2 </label>
								<input id="CustomerAddress2" type="text" value="<?php echo $address1?>" name="address1" placeholder="Address 2" />                       	
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerCity" class="d-none">City <span class="required">*</span></label>
								<input id="CustomerCity" type="text" value="<?php echo $city?>" name="city" placeholder="City" required />
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerZip" class="d-none">Zip/postal code </label>
								<input id="CustomerZip" type="text" value="<?php echo $zipcode?>" name="zipcode" placeholder="Zip/postal code" />                       	
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerState" class="d-none">State <span class="required">*</span></label>
								<select id="CustomerState" name="state" data-default="Maharashtra">
									<?php echo getstatemenu($state)?>
								</select>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerCountry" class="d-none">Country </label>
								<select id="CustomerCountry" name="country" data-default="India">
									<?php echo getcountrymenu($countryid)?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group form-check col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="customCheckbox clearfix">
								<input id="newsletter" name="newsletter" type="checkbox" />
								<label for="newsletter">Sign Up for Newsletter</label>
							</div>
						</div>
						<div class="form-group form-check col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="customCheckbox clearfix">
								<input type="checkbox" class="form-check-input" id="agree" name="terms" value="1" checked required />
                                <label for="agree"><a href="terms-conditions" target="_blank">I agree the Terms and Conditions</a></label>
							</div>
						</div>
						
						
					</div>
					<h3 class="h4 text-uppercase mb-3">Login Information</h3>
					
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">                                	
							<div class="form-group">
								<label for="CustomerPassword" class="d-none">Password <span class="required">*</span></label>
								<input id="CustomerPassword" type="password" name="passwd" placeholder="Password" required />
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerConfirmPassword" class="d-none">Confirm Password <span class="required">*</span></label>
								<input id="CustomerConfirmPassword" type="Password" name="cpasswd" placeholder="Confirm Password" required />                        	
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-6">
							<div class="form-group">
								<label for="CustomerCode" class="d-none">Security Code <span class="required">*</span></label>
								<input id="CustomerCode" type="text" name="captcha" placeholder="<?php $_SESSION['captchacode']=rand(0,9999); echo 'Enter Security Code: '.$_SESSION['captchacode'];?>" maxlength="4" required />    <br>      
								<?php echo '&nbsp;&nbsp;Security Code: '.$_SESSION['captchacode'];?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="text-left col-12 col-sm-12 col-md-6 col-lg-6">
							<input type="submit" class="btn rounded mb-3" value="Submit">
						</div>
						<div class="text-right col-12 col-sm-12 col-md-6 col-lg-6">
							<a href="/login"><i class="align-middle icon an an-an-double-left me-2"></i>Back To Login</a>
						</div>
					</div>
				</form>                       
			</div>
		</div>
		<!--End Main Content-->
	</div>
	<!--End Container-->
</div>
<!--End Body Container-->