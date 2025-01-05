<?php $this->view("admin/header", $data); ?>

<?php $this->view("admin/sidebar", $data); ?>

<style>
    .add_new {
        width: 500px;
        height: 300px;
        background-color: #eae8e8;
        box-shadow: 0px 0px 10px #aaa;
        position: absolute;
        padding: 6px;
    }

    .edit_product {
        width: 500px;
        height: 300px;
        background-color: #eae8e8;
        box-shadow: 0px 0px 10px #aaa;
        position: absolute;
        padding: 6px;
    }


    .show {
        display: :block;
    }

    .hide {
        display: none;
    }
</style>

<div class="row mt">
    <div class="col-md-12">
        <div class="content-panel">

            <table class="table table-striped table-advance table-hover">

                <h4>
                    <i class="fa fa-angle-right"></i>
                    Products
                    <button class="btn btn-primary btn-xs" onclick="show_add_new(event)">
                        <i class="fa fa-plus"></i>
                        Add New
                    </button>
                </h4>

                <!-- add new product -->
                <div class="add_new hide">

                    <h4 class="mb"><i class="fa fa-angle-right"></i> Add New Product</h4>
                    <form class="form-horizontal style-form" method="post">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Product Name:</label>
                            <div class="col-sm-10">
                                <input id="product" name="product" type="text" class="form-control" autofocus>
                            </div>
                        </div>

                        <button type="button" class="btn btn-warning"
                            style="float: right; position: absolute; bottom: 10px; left: 10px;"
                            onclick="show_add_new(event)">Close</button>
                        <button type="button" class="btn btn-primary"
                            style="float: right; position: absolute; bottom: 10px; right: 10px;"
                            onclick="collect_data(event)">Save</button>

                    </form>

                    <br><br>

                </div>
                <!-- add new product end -->

                <!-- edit product -->
                <div class="edit_product hide">

                    <h4 class="mb"><i class="fa fa-angle-right"></i> Edit Product</h4>
                    <form class="form-horizontal style-form" method="post">

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Product Name:</label>
                            <div class="col-sm-10">
                                <input id="product_edit" name="product" type="text" class="form-control" autofocus>
                            </div>
                        </div>

                        <button type="button" class="btn btn-warning"
                            style="float: right; position: absolute; bottom: 10px; left: 10px;"
                            onclick="show_edit_product(0,'',event)">Cancel</button>
                        <button type="button" class="btn btn-primary"
                            style="float: right; position: absolute; bottom: 10px; right: 10px;"
                            onclick="collect_edit_data(event)">Save</button>

                    </form>

                    <br><br>

                </div>
                <!-- edit product end -->

                <hr>

                <thead>

                    <tr>
                        <th><i class="fa fa-bullhorn"></i> Product</th>
                        <th><i class=" fa fa-edit"></i> Status</th>
                        <th><i class=" fa fa-edit"></i> Action</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody id="table_body">

                    <?php echo $tbl_rows; ?>

                </tbody>

            </table>

        </div><!-- /content-panel -->
    </div><!-- /col-md-12 -->
</div><!-- /row -->

<script type="text/javascript">

    var EDIT_ID = 0;

    function show_add_new() {
        var show_add_box = document.querySelector(".add_new");
        var product_input = document.querySelector("#product");

        if (show_add_box.classList.contains("hide")) {
            show_add_box.classList.remove("hide");


            product_input.focus();
        } else {
            show_add_box.classList.add("hide");
            product_input.value = "";
        }
    }

    function show_edit_product(id, product, e) {

        EDIT_ID = id;
        var show_edit_box = document.querySelector(".edit_product");

        //show_edit_box.style.left = (e.clientX - 700) + "px";
        show_edit_box.style.top = (e.clientY - 100) + "px";

        var product_input = document.querySelector("#product_edit");
        product_input.value = product;

        if (show_edit_box.classList.contains("hide")) {
            show_edit_box.classList.remove("hide");
            product_input.focus();
        } else {
            show_edit_box.classList.add("hide");
            product_input.value = "";
        }
    }

    function collect_data(e) {
        var product_input = document.querySelector('#product');

        if (product_input.value.trim() == "" || !isNaN(product_input.value.trim())) {
            alert("Please enter a valid product name");
        }

        var data = product_input.value.trim();
        send_data({
            data: data,
            data_type: 'add_product'
        })
    }

    function collect_edit_data(e) {
        var product_input = document.querySelector('#product_edit');

        if (product_input.value.trim() == "" || !isNaN(product_input.value.trim())) {
            alert("Please enter a valid product name");
        }

        var data = product_input.value.trim();
        send_data({
            id: EDIT_ID,
            product: data,
            data_type: 'edit_product'
        })
    }

    function send_data(data = {}) {

        var ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                handle_result(ajax.responseText);
            }
        });
        ajax.open("POST", "<?= ROOT ?>ajax_product", true);
        ajax.send(JSON.stringify(data));
    }

    function handle_result(result) {

        console.log("result", result);
        if (result != "") {

            var obj = JSON.parse(result);

            if (typeof obj.data_type != 'undefined') {

                if (obj.data_type == "add_new") {

                    if (obj.message_type == 'info') {
                        show_add_new();

                        var table_body = document.querySelector("#table_body");
                        table_body.innerHTML = obj.data;
                    } else {
                        alert(obj.message);
                    }

                } else if (obj.data_type == "edit_product") {
                    show_edit_product(0, '', false);

                    var table_body = document.querySelector("#table_body");
                    table_body.innerHTML = obj.data;
                } else if (obj.data_type == "disable_row") {
                    var table_body = document.querySelector("#table_body");
                    table_body.innerHTML = obj.data;

                } else if (obj.data_type == "delete_row") {
                    var table_body = document.querySelector("#table_body");
                    table_body.innerHTML = obj.data;
                }


            } //end typeof obj.data_type

        }

    }

    function edit_row(id) {
        send_data({
            data_type: ""
        })
    }

    function delete_row(id) {
        if (!confirm("Are you sure you want to delete this row?")) {
            return;
        }

        send_data({
            data_type: "delete_row",
            id: id
        })
    }

    function disable_row(id, state) {
        send_data({
            data_type: "disable_row",
            id: id,
            current_state: state
        })
    }

    // function send_data_form_data(data) {

    //     var ajax = new XMLHttpRequest();

    //     var form = new FormData();
    //     form.append('data', data);

    //     ajax.addEventListener('readystatechange', function () {
    //         if (ajax.readyState == 4 && ajax.status == 200) {
    //             handle_result(ajax.responseText);
    //         }
    //     });
    //     ajax.open("POST", "<?= ROOT ?>ajax", true);
    //     ajax.send(form);
    // }

</script>

<?php $this->view("admin/footer", $data); ?>