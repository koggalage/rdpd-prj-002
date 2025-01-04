<?php $this->view("header", $data); ?>

<section id="main-content">
	<section class="wrapper">

		<div style="min-height: 300px; max-width: 1000px; margin: auto; ">

		<style type="text/css">

			.col-md-6
			{
				color: #293444;
			}

			#user_text{
				color: #6e93ce;
			}

			#value_text{
				color: #6e93ce;
			}
		</style>

			<div class="col-md-4 mb" style="background-color: #eee; text-align: center; box-shadow: 0px 0px 20px #aaa; border: solid thin #ddd;">
				<!-- WHITE PANEL - TOP USER -->
				<div class="white-panel pn">
					<div class="white-header" style="color:grey">
						<h5>MY ACCOUNT</h5>
					</div>
					<p><img src="<?= ASSETS . THEME ?>/admin/img/ui-zac.jpg" class="img-circle" width="80"></p>
					<p id="value_text"><b><?= $data['user_data']->name ?></b></p>
					
					<div class="row">
						<div class="col-md-6">
							<p id="user_text" class="small mt">MEMBER SINCE</p>
							<p id="value_text"><?= date("jS M Y", strtotime($data['user_data']->date)) ?></p>
						</div>
						<div class="col-md-6">
							<p id="user_text" class="small mt">TOTAL SPEND</p>
							<p id="value_text">$ 47,60</p>
						</div>
					</div>

					<hr style="color: #888;">
					<div class="row">
						<div class="col-md-6">
							<p class="small mt" style="cursor: pointer; color: #00aaff;"><i class="fa fa-edit"></i> EDIT</p>
						</div>
						<div class="col-md-6">
							<p class="small mt" style="cursor: pointer; color: #b22222;"><i class="fa fa-trash-o"></i> DELETE</p>
						</div>
					</div>

				</div>
			</div><!-- /col-md-4 -->

		</div>
	</section>
</section>

<?php $this->view("footer", $data); ?>