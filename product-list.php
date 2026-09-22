<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
if (isset($roleview['Item Management']) && in_array('View', $roleview['Item Management'])){}else{
    echo "<script>window.location.assign('../403.php')</script>";
    exit();
}
$recycle = $action->db->validateGetData('recycle') ?: null;
?>
<div class="page-wrapper">
	<div class="content">
		<div class="page-header">
			<div class="add-item d-flex">
				<div class="page-title">
					<h4 class="fw-bold">Item List</h4>
					<h6>Manage your items</h6>
				</div>
			</div>
			<ul class="table-top-head">
				<li>
					<a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
							class="ti ti-chevron-up"></i></a>
				</li>
			</ul>
			<div class="am-page-upr-btn">
				<div class="left-btn">
					<div class="page-btn">
						<?php if (isset($roleview['Item Management']) && in_array('Create', $roleview['Item Management'])){ ?><a href="./product-add.php" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>Add
							Item</a><?php } ?>
					</div>
					<div class="page-btn">
						<?php if ($recycle) { ?>
							<a href="./product-list.php" class="btn btn-secondary">
								<i data-feather="eye-off"></i>
								Hide Recycle Bin
							</a>
						<?php } else { ?>
							<a href="./product-list.php?recycle=true" class="btn btn-danger">
								<i data-feather="trash-2"></i>
								Show Recycle Bin
							</a>
						<?php } ?>
					</div>
				</div>
				<div class="page-btn goBack">
					<a href="#" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
				</div>
			</div>
		</div>
		<div class="accordion accordion-primary mb-2" id="accordionPrimaryExample">
			<div class="accordion-item border-0">
				<h2 class="accordion-header" id="headingPrimaryOne">
					<button class="accordion-button	 fw-bold fs-16 <?= @$_GET['filter'] ? '' : 'collapsed'; ?>"
						type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrimaryOne"
						aria-expanded="false" aria-controls="collapsePrimaryOne">
						<i class="ti ti-filter me-2"></i> Filter
					</button>
				</h2>
				<div id="collapsePrimaryOne" class="accordion-collapse collapse <?= @$_GET['filter'] ? 'show' : ''; ?>"
					aria-labelledby="headingPrimaryOne" data-bs-parent="#accordionPrimaryExample">
					<div class="accordion-body">
						<form method="get">
							<input type="hidden" name="filter" value="true">
							<div class="row">
								<div class="col-sm-3 col-md-3 col-12">
									<div class="mb-3">
										<label class="form-label" for="coupon_point">Coupon Point</label>
										<select name="coupon_point" id="coupon_point" class="select2 am-select2">
											<option value="">Select </option>
											<option value="0" <?= isset($_GET['coupon_point']) && $_GET['coupon_point'] == 0 ? 'selected' : ''; ?>>0</option>
											<option value="1" <?= isset($_GET['coupon_point']) && $_GET['coupon_point'] == 1 ? 'selected' : ''; ?>>1</option>
											<option value="2" <?= isset($_GET['coupon_point']) && $_GET['coupon_point'] == 2 ? 'selected' : ''; ?>>2</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-12">
									<div class="mb-3">
										<label class="form-label" for="warehouseid">Warehouse</label>
										<select class="select2" name="warehouseid" id="warehouseid">
											<option value="">Select</option>
											<?php
											$warehouse = $action->get_admin_function->fetchWarehouse(null, 1, 0);
											if ($warehouse) {
												foreach ($warehouse as $w) { ?>
													<option value="<?= @$w['id']; ?>"
														<?= $action->db->validateGetData('warehouseid') && $action->db->validateGetData('warehouseid') == $w['id'] ? 'selected' : ''; ?>>
														<?= @$w['wtitle']; ?>
													</option>
												<?php }
											} ?>
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-12 flex">
									<button type="submit" class="btn btn-primary me-2">Apply</button>
									<a href="./product-list.php" class="btn btn-secondary">Reset</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /product list -->
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
				<div class="search-set">
					<div class="search-input">
						<span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
					</div>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table datatable">
						<thead class="thead-light">
							<tr>
								<th>#<img src="./assets/img/svg_icons/right-left-arrow.svg" alt="" class="am-sort-icon">
								</th>
								<th>Item Name <img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon">
								</th>
								<th>Print Name<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								<th>Kgs/Qty<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								<th>Alt Qty<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								<th>Coupon Point<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								<th>Warehouse<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								<th>Blend <img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								<th>Remark</th>
								<th class="no-sort">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$deleteStatus = $recycle ? 1 : 0;
							$sql = "SELECT aimo_item.id, aimo_blend.btitle, aimo_item.item_name, aimo_item.print_name, aimo_item.item_qty, aimo_item.alt_qty, aimo_item.coupon_point, aimo_item.remark, aimo_item.status, aimo_warehouse.wtitle FROM aimo_item LEFT JOIN aimo_warehouse ON aimo_item.warehouseid = aimo_warehouse.id LEFT JOIN aimo_blend ON aimo_item.blendid = aimo_blend.id WHERE 1";
							$sql .= " AND aimo_item.deleteStatus = {$deleteStatus}";
							if ($action->db->validateGetData('warehouseid')) {
								$warehouseid = $action->db->validateGetData('warehouseid');
								$sql .= " AND aimo_item.warehouseid = {$warehouseid}";
							}
							if (isset($_GET['coupon_point']) && $_GET['coupon_point'] != '') {
								$coupon_point = $_GET['coupon_point'];
								$sql .= " AND aimo_item.coupon_point = {$coupon_point}";
							}
							$warehouse = $action->db->sql($sql);
							if ($warehouse) {
								$sr = 1;
								foreach ($warehouse as $wd) {
									?>
									<tr>
										<td><?= $sr; ?></td>
										<td class="text-gray-9"><?= $wd['item_name']; ?></td>
										<td class="text-gray-9"><?= $wd['print_name']; ?></td>
										<td class="text-gray-9"><?= $wd['item_qty']; ?></td>
										<td class="text-gray-9"><?= $wd['alt_qty']; ?></td>
										<td class="text-gray-9"><?= $wd['coupon_point']; ?></td>
										<td class="text-gray-9"><?= $wd['wtitle']; ?></td>
										<td class="text-gray-9"><?= $wd['btitle']; ?></td>
										<td>
											<div class="am-text-container">
												<?= $wd['remark']; ?>
											</div>
											<button class="am-see-more-btn" onclick="toggleText(this)">See More</button>
										</td>
										<td class="action-table-data d-flex align-items-center">
											<?php if (!$recycle) { ?>
												<?php if (isset($roleview['Item Management']) && in_array('status', $roleview['Item Management'])){ ?><div class="form-check form-switch">
													<input class="form-check-input off_item" type="checkbox" <?php if ($wd['status'] == 1) {
														echo "checked";
													} ?> data-checkid="<?= @$wd['id']; ?>"
														role="switch" id="checkedOrNot_<?= $sr; ?>">
													<label class="form-check-label" for="checkedOrNot_<?= $sr; ?>"></label>
												</div>
												<?php } ?>
											<?php } ?>

											<div class="edit-delete-action">
												<?php if (!$recycle) { ?>
													<?php if (isset($roleview['Item Management']) && in_array('Update', $roleview['Item Management'])){ ?><a href="./product-add.php?id=<?= $wd['id']; ?>"
														class="p-2 border-0 bg-transparent">
														<i data-feather="edit" class="feather-edit text-warning"></i>
													</a><?php } ?>
												<?php }
												if ($deleteStatus == 1) { ?>
													<?php if (isset($roleview['Item Management']) && in_array('Recover', $roleview['Item Management'])){ ?><button type="button" class="p-2 border-0 bg-transparent recover-item"
														data-delid="<?= @$wd['id']; ?>">
														<i data-feather="refresh-ccw" class="feather-refresh-ccw text-success"></i>
													</button><?php } ?>
													<?php if (isset($roleview['Item Management']) && in_array('Delete Permanently', $roleview['Item Management'])){ ?>
													<button type="button"
														class="p-2 border-0 bg-transparent delete-item-permanently"
														data-delid="<?= @$wd['id']; ?>">
														<i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
													</button><?php } ?>
												<?php } else { ?>
													<?php if (isset($roleview['Item Management']) && in_array('Delete', $roleview['Item Management'])){ ?>
													<button type="button" class="p-2 border-0 bg-transparent delete-item"
														data-delid="<?= @$wd['id']; ?>">
														<i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
													</button>
													<?php } ?>
												<?php } ?>
											</div>
										</td>
									</tr>
									<?php $sr++;
								}
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /product list -->
	</div>

</div>

<?php
require_once('./common/footer.php');
?>
<script>
	$(document).ready(function () {
		setTimeout(() => {
			$("#toggle_btn").trigger('click');
		}, 200);
	});
</script>