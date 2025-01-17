<?php $this->view("header", $data); ?>

<?php
	if (isset($errors) && count($errors) > 0) {
		
		echo "<div>";
		foreach ($errors as $error) {
			echo "<div class='alert alert-danger' style='padding: 5px; max-width: 500px; margin: auto; text-align: center;'>$error</div>";
		}
		echo "</div>";
	}
?>

<section id="cart_items">
	<div class="container">
		<div class="breadcrumbs">
			<ol class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">Check out</li>
			</ol>
		</div><!--/breadcrums-->

		<?php if (is_array($ROWS)): ?>

			<div class="register-req">
				<p>Please use Register And Checkout to easily get access to your order history, or use Checkout as Guest</p>
			</div><!--/register-req-->

			<form method="post">
				<div class="shopper-informations">
					<div class="row">

						<div class="col-sm-8 clearfix">
							<div class="bill-to">
								<p>Bill To</p>
								<div class="form-one">

									<input name="address1" class="form-control" type="text" placeholder="Address 1 *"
										autofocus="autofocus" required> <br>
									<input name="address2" class="form-control" type="text" placeholder="Address 2"> <br>
									<input name="postal_code" class="form-control" type="text"
										placeholder="Zip / Postal Code *" required> <br>

								</div>
								<div class="form-two">


									<select name="country" class="js-country form-control" oninput="get_states(this.value)"
										required>
										<option>-- Country --</option>
										<?php if (isset($countries) && $countries): ?>
											<?php foreach ($countries as $row): ?>

												<option value="<?= $row->id ?>"><?= $row->country ?></option>

											<?php endforeach; ?>
										<?php endif; ?>

									</select> <br>
									<select name="state" class="js-state form-control" required>
										<option>-- State / Province / Region --</option>
									</select> <br>

									<input name="home_phone" class="form-control" type="text" placeholder="Home Phone"> <br>
									<input name="mobile_phone" class="form-control" type="text" placeholder="Mobile Phone"
										required> <br>

								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="order-message">
								<p>Shipping Order</p>
								<textarea name="message" placeholder="Notes about your order, Special Notes for Delivery"
									rows="16"></textarea>
							</div>
						</div>
					</div>


					<input type="submit" class="btn btn-warning pull-right" value="Pay >" name="">



				</div>
			</form>

		<?php else: ?>
			<h3 style="text-align: center;">
				Please add some items in the cart first!
			</h3>
		<?php endif; ?>

		<a href="<?= ROOT ?>cart">
			<input type="button" class="btn btn-warning pull-left" value="< Back to cart" name="">
		</a>

	</div>
</section> <!--/#cart_items-->

<script>
	function get_states(id) {
		console.log('id', id);
		send_data({
			id: id.trim()
		}, "get_states");
	}

	function send_data(data = {}, data_type) {

		var ajax = new XMLHttpRequest();

		ajax.addEventListener('readystatechange', function () {
			if (ajax.readyState == 4 && ajax.status == 200) {
				handle_result(ajax.responseText);
			}
		});
		ajax.open("POST", "<?= ROOT ?>ajax_checkout/" + data_type + "/" + JSON.stringify(data), true);
		ajax.send();
	}

	function handle_result(result) {

		console.log("result", result);
		if (result != "") {

			var obj = JSON.parse(result);

			if (typeof obj.data_type != 'undefined') {

				if (obj.data_type == 'get_states') {

					var select_input = document.querySelector(".js-state");
					select_input.innerHTML = "<option>-- State / Province / Region --</option>";

					for (var i = 0; i < obj.data.length; i++) {
						select_input.innerHTML += "<option value='" + obj.data[i].id + "'>" + obj.data[i].state + "</option>";
					}
				}

			}
		}
	}
</script>

<?php $this->view("footer", $data); ?>