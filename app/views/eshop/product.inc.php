<!-- One Product -->
<div class="col-sm-4">
    <div class="product-image-wrapper">
        <div class="single-products">
            <div class="productinfo text-center">

                <a href="<?= ROOT ?>product_details/<?= $data->slag ?>">
                    <div style="overflow: hidden;"><img class="product-image" src="<?= ROOT . $data->image ?>" alt="" />
                    </div>
                </a>
                <h2>$<?= $data->price ?></h2>
                <p><?= $data->description ?></p>
                <a href="<?= ROOT ?>add_to_cart/<?= $data->id ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
            </div>

        </div>
        <div class="choose">
            <ul class="nav nav-pills nav-justified">
                <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
                <li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- End One Product -->