<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');

if (isset($roleview['User Management']) && in_array('View', $roleview['User Management'])){}else{
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
					<h4 class="fw-bold">User List</h4>
					<h6>Manage Users</h6>
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
						<?php if (isset($roleview['User Management']) && in_array('Create', $roleview['User Management'])){ ?><a href="./user-add.php" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>Add
							User</a><?php } ?>
					</div>
					<div class="page-btn">
						<?php if ($recycle) { ?>
							<a href="./user-list.php" class="btn btn-secondary">
								<i data-feather="eye-off"></i>
								Hide Recycle Bin
							</a>
						<?php } else { ?>
							<a href="./user-list.php?recycle=true" class="btn btn-danger">
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
								<th> Name <img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon">
								</th>
								<th> User Email<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
										class="am-sort-icon"></th>
								
								<th class="no-sort">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$deleteStatus = $recycle ? 1 : 0;
							$userList = $action->db->sql("SELECT * FROM `aimo_users` WHERE deleteStatus= '$deleteStatus'");
							if ($userList) {
								$sr = 1;
								foreach ($userList as $wd) {
									?>
									<tr>
										<td><?= $sr; ?></td>
										<td class="text-gray-9"><?= $wd['u_name']; ?></td>
										<td class="text-gray-9"><?= $wd['u_email']; ?></td>
										
										<td class="action-table-data d-flex align-items-center">
											

											<div class="edit-delete-action">
												<?php if (!$recycle) { ?>
													<?php if (isset($roleview['User Management']) && in_array('Update', $roleview['User Management'])){ ?><a href="./user-add.php?id=<?= $wd['id']; ?>"
														class="p-2 border-0 bg-transparent">
														<i data-feather="edit" class="feather-edit text-warning"></i>
													</a><?php } ?>
												<?php }
												if ($deleteStatus == 1) { ?>
													<?php if (isset($roleview['User Management']) && in_array('Recover', $roleview['User Management'])){ ?><button type="button" class="p-2 border-0 bg-transparent recover-user"
														data-delid="<?= @$wd['id']; ?>">
														<i data-feather="refresh-ccw" class="feather-refresh-ccw text-success"></i>
													</button><?php } ?>
													<?php if (isset($roleview['User Management']) && in_array('Delete Permanently', $roleview['User Management'])){ ?><button type="button" class="p-2 border-0 bg-transparent delete-user-permanently"
														data-delid="<?= @$wd['id']; ?>">
														<i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
													</button><?php } ?>
												<?php } else { ?>
													<?php if (isset($roleview['User Management']) && in_array('Delete', $roleview['User Management'])){ ?><button type="button" class="p-2 border-0 bg-transparent delete-user"
														data-delid="<?= @$wd['id']; ?>">
														<i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
													</button><?php } ?>
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