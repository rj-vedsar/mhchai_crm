<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
if (isset($roleview['Role'])) {
} else {
    echo "<script>window.location.assign('../403.php')</script>";
    exit();
}
$recycle = $action->db->validateGetData('recycle') ?: null;
if($recycle){
    $delete= 1;
}
else{
    $delete=0;
}
?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Role Manage</h4>
                </div>
            </div>
            <div class="page-btn">
                <?php if ($recycle) { ?>
                    <a href="./manage-role.php" class="btn btn-secondary">
                        <i data-feather="eye-off"></i>
                        Hide Recycle Bin
                    </a>
                <?php } else { ?>
                    <a href="./manage-role.php?recycle=true" class="btn btn-danger">
                        <i data-feather="trash-2"></i>
                        Show Recycle Bin
                    </a>
                <?php } ?>
            </div>
            <div class="page-btn">
                <?php if (isset($roleview['Role']) && in_array('Create', $roleview['Role'])){ ?><a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-role-modal"><i
                        class="ti ti-circle-plus me-1"></i>Add Role</a><?php } ?>
            </div>
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-end flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Role & Permission</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="load_role_data">
                            <?php
                            $action->get_admin_function->load_role_data($delete);
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- /product list -->
    </div>
</div>
<div class="modal fade" id="update_role">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update_roleLabelLabel"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="anticon anticon-close"></i>
                </button>
            </div>
            <div class="modal-body" style="max-height: 580px;overflow-y:auto;">
                <form id="update_role_form">

                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Warehouse -->
<div class="modal fade" id="add-role-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addroleLabelLabel">Role & Permission</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="anticon anticon-close"></i>
                </button>
            </div>
            <form id="add_new_role" autocomplete="off">
                <div class="modal-body" style="max-height: 580px;overflow-y:auto;">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="font-weight-semibold" for="new_role_name">Role Name:</label>
                            <input type="text" class="form-control" name="new_role_name" id="new_role_name" value="<?php echo @$data['f_name']; ?>">
                            <span class="mt-1 new_role_name"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <label class="font-weight-semibold">Role Permission</label>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $permissions = $action->get_admin_function->getRoleArray();
                        $sr = 0;
                        foreach ($permissions as $section) : ?>
                            <div class="col-12 col-md-12 mt-3 mb-2">
                                <h6 class="custom"><span class="custo-head"><?php echo $section["title"]; ?></span></h6>
                            </div>
                            <?php foreach ($section["items"] as $item) : $sr++;
                            ?>
                                <div class="col-12 col-md-12 my-2">
                                    <h6 class="card-title font-12"><?php echo $item["label"]; ?></h6>
                                </div>
                                <?php
                                if (isset($item['crud'])) :
                                    foreach ($item['crud'] as $item2) : ?>

                                        <?php if ($item2) : ?>
                                            <input type="hidden" name="roleName[]" value="<?php echo $item['label']; ?>">
                                            <?php
                                            $checked = ''; // Initialize $checked
                                            // print_r($decodeData[$item['label']]);
                                            $label = $item['label'];
                                            if (isset($decodeData[$item['label']]) && in_array($item2, $decodeData[$item['label']])) {
                                                $checked = 'checked';
                                            }
                                            ?>
                                            <div class="col-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" name="role[<?php echo $label; ?>][]" id="<?php echo $item2 . $sr; ?>" value="<?php echo $item2; ?>" class="form-check-input role_checkbox" <?php echo $checked; ?>>
                                                    <label for="<?php echo $item2 . $sr; ?>"><?php echo $item2; ?></label>
                                                </div>
                                            </div>

                                        <?php endif; ?>

                                <?php endforeach;
                                endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                    <button class="btn btn-dark"> Save </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /Add Warehouse -->


<?php
require_once('./common/footer.php');
?>