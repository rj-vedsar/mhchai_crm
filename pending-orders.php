<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');

if (isset($roleview['Order Management']) && in_array('Create', $roleview['Order Management'])){}else{
    echo "<script>window.location.assign('../403.php')</script>";
    exit();
}
if ($action->db->validateGetData('id') && filter_var($action->db->validateGetData('id'), FILTER_VALIDATE_INT)) {
    $id = $action->db->validateGetData('id');
    $order_item = $action->db->sql("SELECT * FROM aimo_order WHERE id = $id");
}

$uploaded_items = [];
$upload_error = '';

if (isset($_FILES['order_upload']) && $_FILES['order_upload']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['order_upload']['tmp_name'];
    $fileName = $_FILES['order_upload']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt === 'csv') {
        if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            // Skip Header
            fgetcsv($handle);

            $allItems = $action->db->sql("SELECT id, item_name, item_qty, coupon_point, warehouseid, remark FROM aimo_item WHERE deleteStatus = 0");
            $itemMap = [];
            if ($allItems) {
                foreach ($allItems as $it) {
                    $itemMap[strtolower(trim($it['item_name']))] = $it;
                }
            }

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (empty($row[0])) continue;
                $nameRaw = trim($row[0]);
                $nameKey = strtolower($nameRaw);

                if (isset($itemMap[$nameKey])) {
                    $itemData = $itemMap[$nameKey];
                    
                    // Quantity: CSV me hai to lo, varna blank
                    $qtyRaw = isset($row[1]) ? trim($row[1]) : '';
                    $qty = $qtyRaw !== '' ? $qtyRaw : '';

                    // kg/Qty: CSV me hai to lo, varna blank (DB se prefill nahi karna)
                    $kgRaw = isset($row[2]) ? trim($row[2]) : '';
                    $kg = $kgRaw !== '' ? $kgRaw : '';

                    // Remarks: CSV me hai to lo, varna blank
                    $rem = isset($row[3]) && trim($row[3]) !== '' ? trim($row[3]) : '';

                    $coupon = floatval($itemData['coupon_point'] ?? 0);
                    $wh = $itemData['warehouseid'] ?? '';

                    $numQty = (is_numeric($qty) && floatval($qty) > 0) ? floatval($qty) : 0;
                    $numKg = (is_numeric($kg) && floatval($kg) > 0) ? floatval($kg) : 0;

                    $uploaded_items[] = [
                        'item_id' => $itemData['id'],
                        'item_qty' => $qty,
                        'item_kg' => $kg,
                        'item_coupon' => $coupon,
                        'warehouseid' => $wh,
                        'total_item_coupon' => $numQty * $coupon,
                        'total_item_weight' => $numQty * $numKg,
                        'remark' => base64_encode($rem)
                    ];
                }
            }
            fclose($handle);
        }
    } else {
        $upload_error = "Sirf CSV file upload karein.";
    }
}
?>

    <script>
    let rowCount = 1;

    function addRow() {
        const tableBody = document.querySelector("table tbody");

        const newRow = document.createElement("tr");

        newRow.innerHTML = `
            <td>${rowCount}</td>
            <td>
                    <select name="item_id[]" class="select2 form-select item_id">
                        <option value="">Select</option>
                        <?php
                        $fetchItemDropdown = $action->get_admin_function->fetchItemDropdown();
                        if ($fetchItemDropdown) {
                            foreach ($fetchItemDropdown as $item) { ?>
                                <option value="<?= $item['id']; ?>"><?= $item['item_name']; ?></option>
                            <?php }
                        } ?>
                    </select>
            </td>
            <td>
                    <input type="text" name="item_qty[]" class="form-control item_qty" placeholder="Enter quantity">
            </td>
            <td>
                    <input type="text" name="item_kg[]" class="form-control item_kg" placeholder="Enter weight">
                    <input type="hidden" name="item_coupon[]" class="form-control item_coupon">
                    <input type="hidden" name="warehouseid[]" class="form-control warehouseid">
                    <input type="hidden" name="total_item_coupon[]" class="form-control total_item_coupon">
                    <input type="hidden" name="total_item_weight[]" class="form-control total_item_weight">
            </td>
            <td>
                    <textarea name="remark[]" class="form-control remark" placeholder="Enter remark"></textarea>
            </td>
            <td class="">
                <a href="javascript:void(0);" class="barcode-delete-icon" onclick="deleteRow(this)">
                <i class="fa-solid fa-xmark text-danger" ></i>
                </a>
            </td>
        `;

        tableBody.appendChild(newRow);
        $(newRow).find('.select2').select2();
        rowCount++;

        updateSrNo();
    }

    function deleteRow(deleteButton) {
        const row = deleteButton.closest('tr');
        row.remove();
        warehouseUpdate();
        updateData();
        updateSrNo();
    }

    function updateSrNo() {
        const rows = document.querySelectorAll("table tbody tr");
        rows.forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector('#addRow').addEventListener('click', function (e) {
            e.preventDefault();
            addRow();
        });
    })
</script>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Orders</h4>
                    <h6><?= @$id ? 'Update Order' : 'Create New Order'; ?></h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            class="ti ti-chevron-up"></i></a>
                </li>
            </ul>

            <div class="am-page-upr-btn">
                <a href="./export-items.php" class="btn btn-dark">Download Items</a>
                <form class="bg-white p-2 ms-2 d-inline-flex align-items-center" method="post" enctype="multipart/form-data">
                    <input type="file" name="order_upload" id="order_upload" accept=".csv" required class="form-control form-control-sm me-2">
                    <button type="submit" name="btn_order_upload" class="btn btn-primary btn-sm">Upload</button>
                </form>
                <div class="left-btn">
                    <div class="page-btn">
                        <?php if (isset($roleview['Order Management']) && in_array('View', $roleview['Order Management'])){ ?><a href="./pending-order-list.php" class="btn btn-info"><i class="fa-solid fa-list-ul me-1"></i>
                            Order List</a> <?php } ?>
                    </div>
                </div>
                <div>
                    <div class="page-btn goBack">
                        <a href="#" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                    </div>
                </div>

            </div>

        </div>
        <form class="add-product-form" id="pending_order_save">
            <input type="hidden" name="updateid" value="<?= @$order_item[0]['id'];?>">
            <div class="add-product">
                <div class="card mb-4">
                    <div id="SpacingOne" class="accordion-collapse collapse show" aria-labelledby="headingSpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-2 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Order Date<span class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon calender-input">
                                            <input type="date" name="order_date" class="form-control"
                                                value="<?= @$order_item[0]['order_date']; ?>">
                                        </div>
                                    </div>
                                </div>
                                  <div class="col-sm-3 col-12 d-flex justify-content-between align-items-center">
                                    <div class="mb-3 w-75">
                                        <label class="form-label">Dispatched Date</label>
                                        <div class="input-groupicon calender-input">
                                            <input type="date" name="dispatched_date" id="dispatched_date" class="form-control"
                                                value="<?= @$order_item[0]['dispatched_date']; ?>">
                                        </div>
                                    </div>
                                    <button type="button" onclick="document.getElementById('dispatched_date').value = ''" title="Reset Dispatched Date" class="btn btn-warning">
    <i class="fas fa-times"></i>
</button>
                                </div>
                                <div class="col-sm-3 col-12" style="overflow: hidden;">
                                    <div class="mb-3">
                                        <label class="form-label"> Party<span class="text-danger ms-1">*</span></label>
                                        <select class="select2" name="pending_party" id="pending_party">
                                            <option>Select</option>
                                            <?php
                                            $fetchParty = $action->get_admin_function->fetchParty(null, 1, 0);
                                            if ($fetchParty) {
                                                foreach ($fetchParty as $ptd) { ?>
                                                    <option <?= @$order_item[0]['pending_party'] == $ptd['id'] ? 'selected' : ''; ?>
                                                        value="<?= $ptd['id']; ?>"><?= $ptd['party_name']; ?>
                                                        (<?= $ptd['party_phone']; ?>)</option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-12 d-flex align-items-center mb-3">
                                    <div class="btn">
                                        <?php if (isset($roleview['Party Management']) && in_array('Create', $roleview['Party Management'])){ ?><a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                             data-bs-target="#add-order"><i class="ti ti-circle-plus me-1"></i>Add </a><?php } ?>

                                    </div>
                                </div>

                                <div class="col-sm-3 col-12">
                                    <div class="mb-3 w-75">
                                        <label class="form-label" for="p_file">File</label>
                                        <div class="input-groupicon calender-input text-center">
                                            <input type="file" name="p_file" id="p_file" class="form-control">
                                             <?php if (!empty($order_item[0]['p_file'])){ ?>
                                            <img src="./upload/<?= @$order_item[0]['p_file']; ?>" alt="update image" style="max-height: 80px;"/>
                                             <?php } ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-3 bg-light rounded border mb-3">
                                        <div class="table-responsive rounded border">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. no.</th>
                                                        <th>Item Name</th>
                                                        <th>Quantity</th>
                                                        <th>Kg/Qty</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $render_rows = [];
                                                    if (!empty($uploaded_items)) {
                                                        $render_rows = $uploaded_items;
                                                    } elseif (isset($order_item) && !empty($order_item[0]['item'])) {
                                                        $render_rows = json_decode($order_item[0]['item'], true) ?: [];
                                                    }

                                                    if (!empty($render_rows)) {
                                                        $sr = 1;
                                                        $fetchItemDropdown = $action->get_admin_function->fetchItemDropdown();
                                                        foreach ($render_rows as $d) { ?>
                                                        <tr>
                                                            <td><?= $sr++; ?></td>
                                                            <td>
                                                                <select name="item_id[]" class="select2 form-select item_id">
                                                                    <option value="">Select</option>
                                                                    <?php if ($fetchItemDropdown) {
                                                                        foreach ($fetchItemDropdown as $item) { ?>
                                                                            <option <?= $item['id'] == $d['item_id'] ? 'selected' : ''; ?> value="<?= $item['id']; ?>"><?= $item['item_name']; ?></option>
                                                                        <?php }
                                                                    } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="item_qty[]" class="form-control item_qty" placeholder="Enter quantity" value="<?= @$d['item_qty']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="item_kg[]" class="form-control item_kg" placeholder="Enter weight" value="<?= @$d['item_kg']; ?>">
                                                                <input type="hidden" name="item_coupon[]" class="form-control item_coupon" value="<?= @$d['item_coupon']; ?>">
                                                                <input type="hidden" name="warehouseid[]" class="form-control warehouseid" value="<?= @$d['warehouseid']; ?>">
                                                                <input type="hidden" name="total_item_coupon[]" class="form-control total_item_coupon" value="<?= @$d['total_item_coupon']; ?>">
                                                                <input type="hidden" name="total_item_weight[]" class="form-control total_item_weight" value="<?= @$d['total_item_weight']; ?>">
                                                            </td>
                                                            <td>
                                                                <textarea name="remark[]" class="form-control remark" placeholder="Enter remark"><?= base64_decode(@$d['remark'] ?? ''); ?></textarea>
                                                            </td>
                                                            <td class="">
                                                                <a href="javascript:void(0);" class="barcode-delete-icon" onclick="deleteRow(this)">
                                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-2 col-12 mb-3 mt-2">
                                            <div>
                                                <a href="#" class="btn btn-primary" id="addRow"><i class="ti ti-circle-plus me-1"></i>Add Item</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-7 col-12 mb-3">
                                    <label class="form-label" for="note">Note</span></label>
                                    <textarea id="note" name="note" class="form-control" placeholder="Enter note"
                                        style="height:70%;"><?= @$order_item[0]['note']; ?></textarea>
                                </div>

                                <div class="col-sm-5 col-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="no_of_bags"> Total number of bags <span
                                                        class="text-danger ms-1">*</span></label>
                                                <input type="text" name="no_of_bags" id="no_of_bags"
                                                    class="form-control" placeholder="Enter total number of bags" value="<?= @$order_item[0]['no_of_bags'];?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="no_of_coupon"> Total number of
                                                    coupons<span class="text-danger ms-1">*</span></label>
                                                <input type="text" name="no_of_coupon" id="no_of_coupon"
                                                    class="form-control" placeholder="Enter total number of coupons" value="<?= @$order_item[0]['no_of_coupon']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="t_weight"> Total weight (kgs)<span
                                                        class="text-danger ms-1">*</span></label>
                                                <input type="text" name="t_weight" id="t_weight" class="form-control"
                                                    placeholder="Enter total weight in kg" value="<?= @$order_item[0]['t_weight']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="vehicle_no"> Transport / Lorry
                                                    Number</label>
                                                <input type="text" name="vehicle_no" id="vehicle_no"
                                                    class="form-control" placeholder="Enter transport / lorry number" value="<?= @$order_item[0]['vehicle_no']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12" id="shop_show">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-12">
                <div class="d-flex align-items-center justify-content-start mb-4">
                    <button type="submit" class="btn btn-primary me-1">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="add-order">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Add New Party</h4>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="save_party">
                <input type="hidden" name="updateid" value="<?= @$party[0]['id']; ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-12">
                            <div class="mb-2">
                                <label class="form-label" for="party_name"> Name<span
                                        class="text-danger ms-1">*</span></label>
                                <input type="text" name="party_name" id="party_name" class="form-control"
                                    value="<?= @$party[0]['party_name']; ?>" placeholder="Enter party name">
                            </div>
                            <span class="error-span text-danger party_name"></span>
                        </div>
                        <div class="col-sm-12 col-12">
                            <div class="mb-2">
                                <label class="form-label" for="party_phone"> Phone No.</label>
                                <input type="number" name="party_phone" id="party_phone" class="form-control"
                                    value="<?= @$party[0]['party_phone']; ?>" placeholder="Enter phone number">
                            </div>
                        </div>
                    </div>
                    <!-- Editor -->
                    <div class="row">
                        <div class="col-sm-12 col-12">
                            <div class="mb-2">
                                <label class="form-label" for="address">Address</span></label>
                                <textarea name="address" id="address" class="form-control"
                                    placeholder="Enter address"><?= @$party[0]['address']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary dismiss-party"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary flex" id="sub_btn1">Save<div
                                class="loader-f d-none" id="loader-f"></div>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>


 <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Global keydown listener for Enter key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Block Enter key globally
                    // console.log("Enter key pressed — action prevented.");
                }
            });
        });
</script>


<?php
require_once('./common/footer.php');
?>
<?php 
if (isset($id) || !empty($uploaded_items)) { } else { ?> 
  <script>
    const button = document.getElementById('addRow');

    button.addEventListener('click', function () {
      console.log('Button clicked!');
    });

    window.addEventListener('load', function () {
      for (let i = 0; i < 10; i++) {
        button.click();
      }
    });
  </script>
<?php } ?>


<script>
    setTimeout(() => {
        $("#toggle_btn").trigger('click');
    }, 200);
    const warehouse = {};
    $(document).on('change', '.item_id', function (e) {
        e.preventDefault();
        const item_id = $(this).val();
        const current = $(this).closest('tr');
        if (!item_id) {
            current.find('.item_kg').val('');
            current.find('.remark').val('');
            current.find('.item_coupon').val('');
            current.find('.warehouseid').val('');
            current.find('.total_item_coupon').val(0);
            current.find('.total_item_weight').val(0);
            updateData();
            return;
        }
        $.ajax({
            url: path_set,
            type: "POST",
            data: {
                item_id,
                data: 'fetchItemByid'
            },
            success: function (res) {
                if (res) {
                    const json = JSON.parse(res);
                    current.find('.item_kg').val(json.item_qty);
                    current.find('.remark').val(json.remark || '');
                    current.find('.item_coupon').val(json.coupon_point);
                    current.find('.warehouseid').val(json.warehouseid);
                    const qty = parseFloat(current.find('.item_qty').val()) || 0;
                    const kg = parseFloat(json.item_qty) || 0;
                    const coupon = parseFloat(json.coupon_point) || 0;
                    current.find('.total_item_coupon').val(qty * coupon);
                    current.find('.total_item_weight').val(qty * kg);
                    updateData();
                }
            }
        });
    });
    $(document).on('input', '.item_qty, .item_kg', function () {
        const item_id = $(this).closest('tr');
        const item_qty = parseFloat(item_id.find('.item_qty').val()) || 0;
        const item_kg = parseFloat(item_id.find('.item_kg').val()) || 0;
        const item_coupon = parseFloat(item_id.find('.item_coupon').val()) || 0;
        item_id.find('.total_item_coupon').val(item_qty * item_coupon);
        item_id.find('.total_item_weight').val(item_qty * item_kg);
        updateData();
    });
    function updateData() {
        const total_item_coupon = document.querySelectorAll('.total_item_coupon');
        const total_item_weight = document.querySelectorAll('.total_item_weight');
        let total_item_weight_all = 0;
        let total_item_coupon_all = 0;
        total_item_coupon.forEach((item) => {
            total_item_coupon_all += parseFloat(item.value) || 0;
        });
        total_item_weight.forEach((item) => {
            total_item_weight_all += parseFloat(item.value) || 0;
        });
        $("#no_of_coupon").val(total_item_coupon_all);
        $("#t_weight").val(total_item_weight_all);
        warehouseUpdate();
    }
    function warehouseUpdate() {
        const warehouseidElements = document.querySelectorAll('.warehouseid');
        const item_qty = document.querySelectorAll('.item_qty');
        let bags_count = 0;
        item_qty.forEach((item) => {
            if (item.value > 0){
                bags_count += parseFloat(item.value);
            }
        });
        $("#no_of_bags").val(bags_count);
        for (let key in warehouse) {
            warehouse[key] = 0;
        }
        warehouseidElements.forEach((item) => {
            const warehouseId = item.value;
            if (!warehouseId || warehouseId === '0') return;
            const weightElement = item.closest('tr').querySelector('.total_item_weight');
            const weight = parseFloat(weightElement ? weightElement.value : 0) || 0;

            if (warehouse[warehouseId]) {
                warehouse[warehouseId] += weight;
            } else {
                warehouse[warehouseId] = weight;
            }
        });

        let data = '';
        $.ajax({
            url: path_set,
            type: "POST",
            data: { data: 'fetchWareHouseName', ids: JSON.stringify(warehouse) },
            success: function (res) {
                if (res) {
                    const json = JSON.parse(res);
                    console.log(json)
                    json.forEach((item) => {
                        data += `
                            <div class="d-flex w-100 justify-content-between">
                                                <strong>${item.wtitle}</strong>
                                                <p>${warehouse[item.id]}</p>
                                            </div>
                            `;
                    })
                    $("#shop_show").html(data);
                }
            }
        });
    }
    <?php if ((isset($order_item) && $order_item) || !empty($uploaded_items)) { ?>
        setTimeout(() => {
            updateData();
            warehouseUpdate();
        }, 500);
    <?php } ?>
</script>
