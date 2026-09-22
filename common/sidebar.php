<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<!-- Logo -->
	<div class="sidebar-logo active">
		<a href="index.php" class="logo logo-normal">
			<img src="assets/img/mh-logo.jpeg" alt="Img">
		</a>
		<a href="index.php" class="logo logo-white">
			<img src="assets/img/mh-logo.jpeg" alt="Img">
		</a>
		<a href="index.php" class="logo-small">
			<img src="assets/img/mh-logo.jpeg" alt="Img">
		</a>
		<a id="toggle_btn" class="toggle_btn_side" href="#">
			<i data-feather="chevrons-left" class="feather-16"></i>
		</a>
	</div>
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul>
				<li class="submenu-open">
					<ul>
						<li>
							<a href="index.php"><i data-feather="home"
									class="feather-16 me-2"></i><span>Dashboard</span></a>
						</li>
						<?php if (isset($roleview['Order Management'])) { ?>
						<li class="submenu">
							<a href="javascript:void(0);"><i data-feather="shopping-cart"
									class="feather-16 me-2"></i><span>Order Management</span><span
									class="menu-arrow"></span></a>
							<ul>
								<?php if (isset($roleview['Order Management']) && in_array('Create',$roleview['Order Management'])) { ?><li><a href="./pending-orders.php">Create New Order</a></li><?php } ?>
								<?php if (isset($roleview['Order Management']) && in_array('View',$roleview['Order Management'])) { ?><li><a href="./pending-order-list.php"> Order List</a></li><?php } ?>
							</ul>
						</li>
						<?php } ?>

					
						<?php if (isset($roleview['Production Plan (Wareh.)'])) { ?>
						<li><a href="./analyse.php"><i data-feather="bar-chart"
									class="feather-16 me-2"></i><span>Production Plan (Wareh.)</span></a></li> 
									<?php } ?>
									<?php if (isset($roleview['Production Plan (Blend)'])) { ?>
						<li><a href="./analyse-blend.php"><i data-feather="bar-chart"
									class="feather-16 me-2"></i><span>Production Plan (Blend)</span></a></li>
									<?php } ?>
					</ul>
					<ul>
						<?php if (isset($roleview['Item Management'])) { ?>
						<li class="submenu">
							<a href="javascript:void(0);"><i data-feather="box" class="feather-16 me-2"></i><span>Item
									Management</span><span class="menu-arrow"></span></a>
							<ul>
								<?php if (isset($roleview['Item Management']) && in_array('Create',$roleview['Item Management'])) { ?><li><a href="./product-add.php">Add Item</a></li><?php } ?>
								<?php if (isset($roleview['Item Management']) && in_array('View',$roleview['Item Management'])) { ?><li><a href="./product-list.php">Item List</a></li><?php } ?>
							</ul>
						</li>
						<?php } ?>
						<?php if (isset($roleview['Party Management'])) { ?>
						<li class="submenu">
							<a href="javascript:void(0);"><i data-feather="users"
									class="feather-16 me-2"></i><span>Party Management</span><span
									class="menu-arrow"></span></a>
							<ul>
								<?php if (isset($roleview['Party Management']) && in_array('Create',$roleview['Party Management'])) { ?><li><a href="./party-add.php">Add Party</a></li><?php } ?>
								<?php if (isset($roleview['Party Management']) && in_array('View',$roleview['Party Management'])) { ?><li><a href="./party-list.php">Party List</a></li><?php } ?>
							</ul>
						</li>

						<?php } ?>
						<?php if (isset($roleview['User Management'])) { ?>

						<li class="submenu">
							<a href="javascript:void(0);"><i data-feather="users"
									class="feather-16 me-2"></i><span>User Management</span><span
									class="menu-arrow"></span></a>
							<ul>
								<?php if (isset($roleview['User Management']) && in_array('Create',$roleview['User Management'])) { ?><li><a href="./user-add.php">Add User</a></li><?php } ?>
								<?php if (isset($roleview['User Management']) && in_array('View',$roleview['User Management'])) { ?><li><a href="./user-list.php">User List</a></li><?php } ?>
							</ul>
						</li>
						<?php } ?>
						<?php if (isset($roleview['Warehouse'])) { ?>
						<li>
							<a href="warehouse.php"><i data-feather="archive"
									class="feather-16 me-2"></i><span>Warehouse</span></a>
						</li>
						<?php } ?>
						<?php if (isset($roleview['Blend'])) { ?>
						<li>
							<a href="blend.php"> <i data-feather="archive"
									class="feather-16 me-2"></i><span>Blend</span></a>
						</li>
						<?php } ?>
						<?php if (isset($roleview['Role'])) { ?>
						<li>
							<a href="manage-role.php"> <i data-feather="box"
									class="feather-16 me-2"></i><span>Role</span></a>
						</li>
						<?php } ?>
						<?php if (isset($roleview['Permission'])) { ?>
						<li>
							<a href="manage-permission.php"> <i data-feather="box"
									class="feather-16 me-2"></i><span>Permission</span></a>
						</li>
						<?php } ?>
				</li>

			</ul>
		</div>
	</div>
</div>
<!-- /Sidebar -->