<?php $this->view("header", $data); ?>

<section>
	<div class="container">
		<div class="row">

			<?php $this->view("sidebar.inc", $data); ?>

			<div class="col-sm-9">
				<?php if (isset($row) && is_object($row)): ?>
					<div class="blog-post-area">
						<h2 class="title text-center">Latest From our Blog</h2>
						<div class="single-blog-post">
						<h3> <?= htmlspecialchars($row->title) ?> </h3>
							<div class="post-meta">
								<ul>
									<li><i class="fa fa-user"></i><?= $row->user_data->name ?></li>
									<li><i class="fa fa-clock-o"></i> <?= date("H:i a", strtotime($row->date)) ?> </li>
									<li><i class="fa fa-calendar"></i> <?= date("M jS, Y", strtotime($row->date)) ?> </li>
								</ul>
								<span>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star-half-o"></i>
								</span>
							</div>
							<img src="<?= ROOT . $row->image ?>" style="width: 100%;" alt="<?= htmlspecialchars($row->title) ?>">
							<p> <?= nl2br(htmlspecialchars($row->post)) ?></p>
							<div class="pager-area">
								<ul class="pager pull-right">
									<li><a href="#">Pre</a></li>
									<li><a href="#">Next</a></li>
								</ul>
							</div>
						</div>
					</div><!--/blog-post-area-->

					<div class="rating-area">
						<ul class="ratings">
							<li class="rate-this">Rate this item:</li>
							<li>
								<i class="fa fa-star color"></i>
								<i class="fa fa-star color"></i>
								<i class="fa fa-star color"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
							</li>
							<li class="color">(6 votes)</li>
						</ul>
						<ul class="tag">
							<li>TAG:</li>
							<li><a class="color" href="">Pink <span>/</span></a></li>
							<li><a class="color" href="">T-Shirt <span>/</span></a></li>
							<li><a class="color" href="">Girls</a></li>
						</ul>
					</div><!--/rating-area-->

					<div class="socials-share">
						<a href=""><img src="<?= ASSETS . THEME ?>/images/blog/socials.png" alt=""></a>
					</div><!--/socials-share-->

				<?php else: ?>
					<div class="status alert alert-danger" style="font-size: 18px; text-align: center;">The selected post
						was not found</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php $this->view("footer", $data); ?>