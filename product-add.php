<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
if (isset($roleview['Item Management']) && in_array('Create', $roleview['Item Management'])){}else{
    echo "<script>window.location.assign('../403.php')</script>";
    exit();
}
if ($action->db->validateGetData('id')) {
    $id = $action->db->validateGetData('id');
    $items = $action->get_admin_function->fetchItem($id);
}
?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Item </h4>
                    <h6><?= @$id ? 'Update Item' : 'Add Item'; ?></h6>
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
                        <?php if (isset($roleview['Item Management']) && in_array('Import', $roleview['Item Management'])){ ?><a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import-file"><i
                                class="ti ti-upload me-1"></i>Import</a><?php } ?>
                    </div>
                    <div class="page-btn">
                        <?php if (isset($roleview['Item Management']) && in_array('View', $roleview['Item Management'])){ ?><a href="./product-list.php" class="btn btn-info"><i
                                class="fa-solid fa-list-ul me-1"></i></i>Item List</a><?php } ?>
                    </div>
                </div>
                <div class="page-btn goBack">
                    <a href="#" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                </div>
            </div>
        </div>
        <form class="add-product-form" id="save_item">
            <input type="hidden" name="id" value="<?= @$items[0]['id']; ?>">
            <div class="add-product">
                <div class="card mb-4">
                    <div id="SpacingOne" class="accordion-collapse collapse show" aria-labelledby="headingSpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-4 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control" placeholder="Enter item name"
                                            name="item_name" id="item_name" value="<?= @$items[0]['item_name']; ?>">
                                    </div>
                                    <span class="error-span text-danger item_name"></span>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="print_name">Print Name</label>
                                        <input type="text" id="print_name" name="print_name" class="form-control"
                                            placeholder="Enter print name" value="<?= @$items[0]['print_name']; ?>">
                                        <span class="error-span text-danger print_name"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="item_qty">Item Kgs/Qty</label>
                                        <input type="number" name="item_qty" id="item_qty" class="form-control"
                                            placeholder="Enter item in kgs/qty" value="<?= @$items[0]['item_qty']; ?>">
                                    </div>
                                    <span class="error-span text-danger item_qty"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="alt_qty">Alternate Qty</label>
                                        <input type="text" name="alt_qty" id="alt_qty" class="form-control"
                                            placeholder="Enter alternate quantity"
                                            value="<?= @$items[0]['alt_qty']; ?>">
                                    </div>
                                    <span class="error-span text-danger alt_qty"></span>
                                </div>


                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="coupon_point">Coupon Point</label>
                                        <select name="coupon_point" id="coupon_point" class="select2 am-select2">
                                            <option value="">Select </option>
                                            <option value="0" <?= @$items[0]['coupon_point'] == 0 ? 'Selected' : ''; ?>>0
                                            </option>
                                            <option value="1" <?= @$items[0]['coupon_point'] == 1 ? 'Selected' : ''; ?>>1
                                            </option>
                                            <option value="2" <?= @$items[0]['coupon_point'] == 2 ? 'Selected' : ''; ?>>2
                                            </option>
                                        </select>
                                    </div>
                                    <span class="error-span text-danger coupon_point"></span>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="warehouseid">Warehouse<span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="select2" name="warehouseid" id="warehouseid">
                                            <option value="">Select</option>
                                            <?php
                                            $warehouse = $action->get_admin_function->fetchWarehouse(null, 1, 0);
                                            if ($warehouse) {
                                                foreach ($warehouse as $w) { ?>
                                                    <option <?= @$items[0]['warehouseid'] == $w['id'] ? 'Selected' : ''; ?>
                                                        value="<?= @$w['id']; ?>"><?= @$w['wtitle']; ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <span class="error-span text-danger warehouseid"></span>
                                </div>

                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="blendid">Blend</label>
                                        <select class="select2" name="blendid" id="blendid">
                                            <option value="">Select</option>
                                            <?php
                                            $blend = $action->get_admin_function->fetchBlend(null, 1, 0);
                                            if ($blend) {
                                                foreach ($blend as $row) { ?>
                                                    <option <?= @$items[0]['blendid'] == $row['id'] ? 'Selected' : ''; ?>
                                                        value="<?= @$row['id']; ?>"> <?= @$row['btitle']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Editor -->
                            <div class="row">
                                <div class="col-sm-12 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="remark">Remark</label>
                                        <textarea name="remark" id="remark" class="form-control"
                                            placeholder="Enter remark"><?= @$items[0]['remark']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /Editor -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary flex" id="sub_btn">Save<div class="loader-f d-none"
                        id="loader-f"></div>
                </button>
            </div>
        </form>
    </div>

</div>

<div class="modal fade" id="import-file">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Import Items</h4>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="upload_csv_item">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Upload file <span class="text-danger">*</span></label>
                                <input type="file" name="csvfile" accept=".csv" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            Download sample click <a href="./sample-item-list.csv" class="text-primary"
                                download>here</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary flex" id="sub_btn1">Save<div class="loader-f d-none"
                            id="loader-f1"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once('./common/footer.php');
?>