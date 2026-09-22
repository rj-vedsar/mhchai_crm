<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');

if (isset($roleview['Order Management']) && in_array('View', $roleview['Order Management'])){}else{
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
                    <h4 class="fw-bold">Order List</h4>
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
                        <?php if (isset($roleview['Order Management']) && in_array('Create', $roleview['Order Management'])){ ?><a href="./pending-orders.php" class="btn btn-primary"><i class="ti ti-circle-plus fs-16 me-1"></i>Create New Order</a> <?php } ?>
                    </div>
                    <div class="page-btn">
                        <?php if ($recycle) { ?>
                            <a href="./pending-order-list.php" class="btn btn-secondary">
                                <i data-feather="eye-off" class="fs-16"></i>
                                Hide Recycle Bin
                            </a>
                        <?php } else { ?>
                            <a href="./pending-order-list.php?recycle=true" class="btn btn-danger">
                                <i data-feather="trash-2 fs-16"></i>
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
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Dispatched Date From</label>
                                        <div class="input-groupicon calender-input">
                                            <input type="date" name="dispatched_date_from"
                                                value="<?= @$_GET['dispatched_date_from']; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Dispatched Date To</label>
                                        <div class="input-groupicon calender-input">
                                            <input type="date" name="dispatched_date_to"
                                                value="<?= @$_GET['dispatched_date_to']; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Order Date From</label>
                                        <div class="input-groupicon calender-input">
                                            <input type="date" name="order_date_from"
                                                value="<?= @$_GET['order_date_from']; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Order Date To</label>
                                        <div class="input-groupicon calender-input">
                                            <input type="date" name="order_date_to"
                                                value="<?= @$_GET['order_date_to']; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Dispatched Status</label>
                                        <select name="dispatched_status" class="select2">
                                            <option value="">Select </option>
                                            <option <?= @$_GET['dispatched_status'] == 0 ? 'selected' : ""; ?> value="0">
                                                Pending</option>
                                            <option <?= @$_GET['dispatched_status'] == 1 ? 'selected' : ""; ?> value="1">
                                                Dispatched</option>
                                            <option <?= @$_GET['dispatched_status'] == 'all' ? 'selected' : ""; ?>
                                                value="all">All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Party</label>
                                        <select name="party" class="select2">
                                            <option value="">Select </option>
                                            <?php
                                            $partysql = "SELECT id, party_name, party_phone FROM aimo_party WHERE status = 1 AND deleteStatus = 0";
                                            $partydata = $action->db->sql($partysql);
                                            if ($partydata) {
                                                foreach ($partydata as $party) {
                                                    ?>
                                                    <option <?= @$_GET['party'] == $party['id'] ? 'selected' : ""; ?>
                                                        value="<?php echo $party['id']; ?>"><?php echo $party['party_name']; ?>
                                                        (<?php echo $party['party_phone']; ?>)</option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-md-3 col-12 flex">
                                    <button type="submit" class="btn btn-primary me-2">Apply</button>
                                    <a href="./pending-order-list.php" class="btn btn-secondary">Reset</a>
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
                                <th> Order Date<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                        class="am-sort-icon"></th>
                                <th> Party<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                        class="am-sort-icon"></th>
                                <th> Dispatched Status<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                        class="am-sort-icon"></th>
                                <th> Dispatched Date<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                        class="am-sort-icon"></th>
                                 <th>Image <img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                        class="am-sort-icon"></th>

                                <th class="no-sort">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = '';
                            $sql = "SELECT aimo_order.*,aimo_party.party_name,aimo_party.party_phone FROM aimo_order JOIN aimo_party ON aimo_party.id=aimo_order.pending_party WHERE 1";
                            $deleteStatus = $recycle ? 1 : 0;
                            $sql .= " AND aimo_order.deleteStatus = {$deleteStatus}";
                            if (!$recycle) {
                                if ($action->db->validateGetData('dispatched_date_from') && $action->db->validateGetData('dispatched_date_to')) {
                                    $dispatchedDateFrom = $action->db->validateGetData('dispatched_date_from');
                                    $dispatchedDateTo = $action->db->validateGetData('dispatched_date_to');
                                    $sql .= " AND aimo_order.dispatched_date BETWEEN '{$dispatchedDateFrom}' AND '{$dispatchedDateTo}'";
                                }

                                if ($action->db->validateGetData('order_date_from') && $action->db->validateGetData('order_date_to')) {
                                    $orderDateFrom = $action->db->validateGetData('order_date_from');
                                    $orderDateTo = $action->db->validateGetData('order_date_to');
                                    $sql .= " AND aimo_order.order_date BETWEEN '{$orderDateFrom}' AND '{$orderDateTo}'";
                                }
                                if (isset($_GET['dispatched_status'])) {
                                    $dispatched_status = $_GET['dispatched_status'];
                                    if ($dispatched_status == 0 || $dispatched_status == "") {
                                        $sql .= " AND (aimo_order.status = 0 )";
                                    }
                                    if ($dispatched_status == 1) {
                                        $sql .= " AND aimo_order.status =1";
                                    }
                                    if ($dispatched_status == "all") {
                                        $sql .= " AND (aimo_order.status= 1 || aimo_order.status=0)";
                                    }

                                } else {
                                    $sql .= " AND (aimo_order.status = 0)";
                                }
                                if ($action->db->validateGetData('party')) {
                                    $party = $action->db->validateGetData('party');
                                    $sql .= " AND aimo_order.pending_party = '{$party}'";
                                }
                            }
                            $sql .= " ORDER BY aimo_order.id DESC";
                            $orderlisting = $action->db->sql($sql);
                            if ($orderlisting) {

                                $sr = 1;
                                foreach ($orderlisting as $od) {
                                    ?>
                                    <tr>
                                        <td><?= $sr++; ?></td>
                                        <td class="text-gray-9"><a href="./print-order.php?oid=<?= $od['id']; ?>"><?= $action->db->indiandate($od['order_date']); ?></a></td>
                                        <td class="text-gray-9"> <a href="./print-order.php?oid=<?= $od['id']; ?>"><?= $od['party_name']; ?></a></td>
                                        <td>
                                            <?php
                                            if ($od['status'] == 0) {
                                                echo "<span class='text-warning'> Pending <span>";
                                            } else {
                                                echo "<span class='text-success'> Dispatched <span>";
                                            }
                                            if (!$recycle) {
                                                ?>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dispatchstatus" type="checkbox" <?php if ($od['status'] == 1) {
                                                        echo "checked";
                                                    } ?> data-checkid="<?= @$od['id']; ?>"
                                                        role="switch" id="checkedOrNot_<?= $sr; ?>">
                                                    <label class="form-check-label" for="checkedOrNot_<?= $sr; ?>"></label>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td class="text-gray-9"><?= $action->db->indiandate($od['dispatched_date']); ?></td>
                                        <td class="text-gray-9">
                                           <?php 
                                           if(!empty($od["p_file"])){
                                        ?>  
                                         <a href="./upload/<?= $od['p_file']; ?>" download>  
                                        <img src="./upload/<?= $od['p_file']; ?>" alt="user image" style="width: 50px; height: 50px; border-radius: 50px;"/>
                                         </a>
                                        <?php } ?>
                                        </td>
                                        <td class="action-table-data d-flex align-items-center">

                                            <div class="edit-delete-action">
                                                <?php if (!$recycle) { ?>
                                                    <a href="./print-order.php?oid=<?= $od['id']; ?>"
                                                        class="p-2 border-0 bg-transparent">
                                                        <i data-feather="eye" class="feather-eye text-warning"></i>
                                                    </a>
                                                    <?php if (isset($roleview['Order Management']) && in_array('Update', $roleview['Order Management'])){ ?><a href="./pending-orders.php?id=<?= $od['id']; ?>"
                                                        class="p-2 border-0 bg-transparent">
                                                        <i data-feather="edit" class="feather-edit text-warning"></i>
                                                    </a><?php } ?>
                                                <?php }
                                                if ($deleteStatus == 1) { ?>
                                                    <?php if (isset($roleview['Order Management']) && in_array('Recover', $roleview['Order Management'])){ ?><button type="button" class="p-2 border-0 bg-transparent recover-item-order"
                                                        data-delid="<?= @$od['id']; ?>">
                                                        <i data-feather="refresh-ccw" class="feather-refresh-ccw text-success"></i>
                                                    </button><?php } ?>
                                                    <?php if (isset($roleview['Order Management']) && in_array('Delete Permanently', $roleview['Order Management'])){ ?>
                                                    <button type="button" class="p-2 border-0 bg-transparent delete-item-order-permanently"
                                                        data-delid="<?= @$od['id']; ?>">
                                                        <i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
                                                    </button> <?php } ?>
                                                <?php } else { ?>
                                                    <?php if (isset($roleview['Order Management']) && in_array('Delete', $roleview['Order Management'])){ ?>
                                                    <button type="button" class="p-2 border-0 bg-transparent delete-item-order"
                                                        data-delid="<?= @$od['id']; ?>">
                                                        <i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
                                                    </button>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>
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