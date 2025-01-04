<!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
              	  <p class="centered"><a href="profile.html"><img src="<?= ASSETS . THEME?>/admin/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
              	  <h5 class="centered"><?= $data['user_data']->name ?></h5>
              	  <h5 class="centered" style="font-size: 11px;"><?= $data['user_data']->email ?></h5>
              	  	
                  <li class="sub-menu">
                      <a href="javascript:;" >
                      <i class="fa fa-dashboard"></i>
                      <span>Dashboard</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="<?= ROOT ?>admin/products" >
                      <i class="fa fa-barcode"></i>
                      <span>Products</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?= ROOT ?>admin/products/add">Add New Product</a></li>
                          <li><a  href="<?= ROOT ?>admin/products/edit">Edit Product</a></li>
                          <li><a  href="<?= ROOT ?>admin/products/delete">Delete Product</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="<?= ROOT ?>admin/categories" >
                      <i class="fa fa-list-alt"></i>
                      <span>Categories</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?= ROOT ?>admin/categories/add">Add New Category</a></li>
                          <li><a  href="<?= ROOT ?>admin/categories/edit">Edit Category</a></li>
                          <li><a  href="<?= ROOT ?>admin/categories/delete">Delete Category</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="<?= ROOT ?>admin/orders" >
                      <i class="fa fa-reorder"></i>
                      <span>Order</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="<?= ROOT ?>admin/settings" >
                      <i class="fa fa-cogs"></i>
                      <span>Settings</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?= ROOT ?>admin/settings/slider_images">Slider Images</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="<?= ROOT ?>admin/users" >
                      <i class="fa fa-cogs"></i>
                      <span>Users</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="<?= ROOT ?>admin/users/customers">Customers</a></li>
                          <li><a  href="<?= ROOT ?>admin/users/admins">Admins</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="<?= ROOT ?>admin/backup" >
                      <i class="fa fa-hdd-o"></i>
                      <span>Website Backup</span>
                      </a>
                  </li>

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->