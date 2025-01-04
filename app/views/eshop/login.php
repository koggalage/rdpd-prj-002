<?php $this->view("header",$data); ?>

	<section id="form" style="margin-top: 5px;"><!--form-->
		<div class="container">
			<div class="row"  style="text-align: center;">
				
				<div class="col-sm-4 col-sm-offset-1" style="float: none; display: inline-block">
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<form method="post">
							<input name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>" type="email" placeholder="Email" />
							<input name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>" type="password" placeholder="Password" />
							<span>
								<input type="checkbox" class="checkbox"> 
								Keep me signed in
							</span>
							<button type="submit" class="btn btn-default">Login</button>
						</form>

						<br>
								<a href="<?=ROOT?>signup">Don't have an account? Signup here</a>
					</div><!--/login form-->
				</div>
				
			</div>
		</div>
	</section><!--/form-->
	
	<?php $this->view("footer"); ?>