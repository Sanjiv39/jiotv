<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Admin Login | JioTV App - UsefulToolsHub</title>
<link rel="shortcut icon" href="../favicon.ico"/>
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"/>
<link rel="stylesheet" href="assets/login.css"/>
</head>
<body>
    
    <a href="index" class="logo">
        <img src="../assets/jiotv.png" alt="Application Logo">
    </a>

	<div class="section">
		<div class="container">
			<div class="row full-height justify-content-center">
				<div class="col-12 text-center align-self-center py-5">
					<div class="section pb-5 pt-5 pt-sm-2 text-center">
						<h6 class="mb-0 pb-3"></h6>
			          	
			          	<label for="reg-log"></label>
						<div class="card-3d-wrap mx-auto">
							<div class="card-3d-wrapper">
								<div class="card-front">
									<div class="center-wrap">
										<div class="section text-center">
                                            <h4 class="mb-3">Admin Login</h4>
											<div class="mb-2" id="iforalert"></div>
											<div class="form-group">
												<input type="text" class="form-style" placeholder="Username / User ID" id="userId" autocomplete="off"/>
												<i class="input-icon uil uil-at"></i>
											</div>	
											<div class="form-group mt-2">
												<input type="password" class="form-style" placeholder="Password" id="userPass" autocomplete="off"/>
												<i class="input-icon uil uil-lock-alt"></i>
											</div>
											<a onclick="do_login()" id="usrLoginBtn" class="btn mt-4">Login</a>
                            				
				      					</div>
			      					</div>
			      				</div>
								
			      			</div>
			      		</div>
			      	</div>
		      	</div>
	      	</div>
	    </div>
	</div>
<script src="../assets/jquery.js"></script>
<script src="assets/admin.js?v=<?php print(time()); ?>"></script>
<script>
$(document).ready(function(){
    check_login_session();
})
</script>
</body>
</html>