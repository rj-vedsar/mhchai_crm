<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
if (isset($roleview['Blend'])) {
} else {
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
                    <h4>Blend</h4>
                </div>
            </div>
            <div class="page-btn">
                <?php if ($recycle) { ?>
                    <a href="./blend.php" class="btn btn-secondary">
                        <i data-feather="eye-off"></i>
                        Hide Recycle Bin
                    </a>
                <?php } else { ?>
                    <a href="./blend.php?recycle=true" class="btn btn-danger">
                        <i data-feather="trash-2"></i>
                        Show Recycle Bin
                    </a>
                <?php } ?>
            </div>
            <div class="page-btn">
                <?php if (isset($roleview['Blend']) && in_array('Create', $roleview['Blend'])){ ?><a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-blend"><i
                        class="ti ti-circle-plus me-1"></i>Add Blend</a><?php } ?>
            </div>
        </div>
        <!-- /product list -->
         <?php if (isset($roleview['Blend']) && in_array('View', $roleview['Blend'])){ ?>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-end flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
            <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Blend</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $deleteStatus = $recycle ? 1 : 0;
                            $blend = $action->get_admin_function->fetchBlend(null, null, $deleteStatus);
                            if ($blend) {
                                $sr = 1;
                                foreach ($blend as $row) { 
                                    ?>
                                    <tr>
                                        <td><?= $sr; ?></td>
                                        <td class="text-gray-9"><a href=""><?= $row['btitle']; ?></a></td>
                                        <td class="action-table-data d-flex align-items-center">
                                            <?php if (!$recycle) { ?>
                                                <?php if (isset($roleview['Blend']) && in_array('Status', $roleview['Blend'])){ ?><div class="form-check form-switch">
                                                    <input class="form-check-input off_blend" type="checkbox" <?php if ($row['status'] == 1) {
                                                        echo "checked";
                                                    } ?> data-checkid="<?= @$row['id']; ?>"
                                                        role="switch" id="checkedOrNot_<?= $sr; ?>">
                                                    <label class="form-check-label" for="checkedOrNot_<?= $sr; ?>"></label>
                                                </div><?php } ?>
                                            <?php } ?>

                                            <div class="edit-delete-action">
                                                <?php if (!$recycle) { ?>
                                                    <?php if (isset($roleview['Blend']) && in_array('Update', $roleview['Blend'])){ ?><button type="button" class="p-2 border-0 bg-transparent edit-blend"
                                                        data-id="<?= @$row['id']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#add-blend">
                                                        <i data-feather="edit" class="feather-edit text-warning"></i>
                                                    </button><?php } ?>
                                                <?php }
                                                if ($deleteStatus == 1) { ?>
                                                    <?php if (isset($roleview['Blend']) && in_array('Recover', $roleview['Blend'])){ ?><button type="button" class="p-2 border-0 bg-transparent recover-blend"
                                                        data-delid="<?= @$row['id']; ?>">
                                                        <i data-feather="refresh-ccw" class="feather-refresh-ccw text-success"></i>
                                                    </button><?php } ?>
                                                    <?php if (isset($roleview['Blend']) && in_array('Delete Permanently', $roleview['Blend'])){ ?><button type="button" class="p-2 border-0 bg-transparent delete-blend-permanently"
                                                        data-delid="<?= @$row['id']; ?>">
                                                        <i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
                                                    </button><?php } ?>
                                                <?php } else { ?>
                                                    <?php if (isset($roleview['Blend']) && in_array('Delete', $roleview['Blend'])){ ?><button type="button" class="p-2 border-0 bg-transparent delete-blend"
                                                        data-delid="<?= @$row['id']; ?>">
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
        <?php } ?>                    

        <!-- /product list -->
    </div>
</div>

<!-- Add Warehouse -->
<div class="modal fade" id="add-blend">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Blend</h4>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="save_blend">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="updateid" id="updateid">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="blend_name">Blend <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="blend_name" id="blend_name" class="form-control">
                            </div>
                            <span class="error-span text-danger blend_name"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex" id="sub_btn">Save<div class="loader-f d-none"
                            id="loader-f"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add Warehouse -->
<?php
require_once('./common/footer.php');
?>
