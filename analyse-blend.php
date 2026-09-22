<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
if (isset($roleview['Production Plan (Blend)']) && in_array('View', $roleview['Production Plan (Blend)'])){}else{
    echo "<script>window.location.assign('../403.php')</script>";
    exit();
}
?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Production Plan (Blend)</h4>
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
                </div>
                <div>
                    <div class="page-btn goBack">
                        <a href="#" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Dispatched Date <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-groupicon calender-input">
                                                <input type="date" name="dispatched_date" required class="form-control"
                                                    value="<?= @$_GET['dispatched_date']; ?>">
                                            </div>
                                        </div>
                                        <div class=" col-md-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="blendid">Blend</label>
                                                <select class="select2" name="blendid" id="blendid">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $blendList = $action->get_admin_function->fetchBlend(null, 1, 0);
                                                    if ($blendList) {
                                                        foreach ($blendList as $w) { ?>
                                                            <option value="<?= @$w['id']; ?>"
                                                                <?= $action->db->validateGetData('blendid') && $action->db->validateGetData('blendid') == $w['id'] ? 'selected' : ''; ?>>
                                                                <?= @$w['btitle']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary mt-4">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <div class="search-set">
                                    <div class="search-input">
                                        <span class="btn-searchset"><i
                                                class="ti ti-search fs-14 feather-search"></i></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable2">
                                <thead class="thead-light">
                                    <tr>

                                        <th>#<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                                class="am-sort-icon">
                                        </th>
                                        <th> Blend <img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                                class="am-sort-icon">
                                        </th>

                                        <th> Quantity<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                                class="am-sort-icon"></th>
                                        <th> Total Kgs<img src="./assets/img/svg_icons/right-left-arrow.svg" alt=""
                                                class="am-sort-icon"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($action->db->validateGetData('dispatched_date')) {
                                        $dispatched_date = $action->db->validateGetData('dispatched_date');

                                        $ordersql = "SELECT item FROM aimo_order WHERE dispatched_date = '{$dispatched_date}' AND deleteStatus = 0";

                                        $blendid = $action->db->validateGetData('blendid') ?: '';

                                        $order = $action->db->sql($ordersql);


                                        if ($order) {
                                            $wg = 0;
                                            $items = [];
                                            $items_kg = [];
                                            $items_total = [];
                                            $items_qty = [];
                                            $totalbags = $grandtotal = 0;
                                            foreach ($order as $item) {
                                                // Decode JSON correctly
                                                $decoded = json_decode($item['item'], true);
                                                if ($decoded) {
                                                    foreach ($decoded as $d) {
                                                        $item_id = $d['item_id'];
                                                        $item_qty = $d['item_qty'];
                                                        $kg_qty = $d['item_kg'];
                                                        $item_blend = $action->db->sql("SELECT blendid FROM aimo_item WHERE id = {$item_id}")[0]['blendid'];
                                                        $items_kg[$item_blend] = $kg_qty;
                                                        $shw = true;
                                                        if (!empty($blendid) && $blendid == $item_blend) {
                                                            $shw = true;
                                                        } elseif (!empty($blendid) && $blendid != $item_blend) {
                                                            $shw = false;
                                                        }
                                                        if ($shw) {
                                                            // Initialize if not exists
                                                            if (!isset($items[$item_blend])) {
                                                                $items[$item_blend] = 0;
                                                                $items_qty[$item_blend] = 0;
                                                                $items_total[$item_blend] = 0;
                                                                if (!isset($items_kg[$item_blend])) {
                                                                    $items_kg[$item_blend] = $kg_qty; // Set only once
                                                                }
                                                            }

                                                            $items[$item_blend] += $item_qty;
                                                            $items_qty[$item_blend] += $item_qty;
                                                            $items_total[$item_blend] += $item_qty * $items_kg[$item_blend];
                                                            $wg += $item_qty * $items_kg[$item_blend];
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    }
                                    // die;
                                    if (isset($items) && $items) {
                                        $sr = 1;
                                        foreach ($items as $t => $v) {
                                            $itemName = $action->db->sql("SELECT btitle FROM aimo_blend WHERE id = {$t}");
                                            ?>
                                            <tr>
                                                <td><?= $sr++; ?></td>
                                                <td><?= @$itemName[0]['btitle']; ?></td>
                                                <td> <?= @$items_qty[$t] ?></td>
                                                <td> <?= $items_total[$t]; ?></td>
                                            </tr>
                                            <?php
                                            $totalbags += $items_qty[$t];
                                        }
                                    }

                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-end">Total No of Bags:</th>
                                        <th><?= $totalbags; ?></th>
                                        <th class="text-end">Grand Total:</th>
                                        <th><?= @$wg ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once('./common/footer.php');
?>


<!-- DataTables Buttons JS -->
<script type="text/javascript" charset="utf-8"
    src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

<!-- Print Button JS -->
<script type="text/javascript" charset="utf-8"
    src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<!-- Excel Export JS (Required) -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<!-- JSZip (Required for export functionality like Excel, CSV, etc.) -->
<script type="text/javascript" charset="utf-8"
    src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script>
$(document).ready(function () {
    var table = $('.datatable2').DataTable({
        "bFilter": true,
        "sDom": 'Bfrtip',
        "ordering": true,
        "order": [[1, "asc"]], // Sort only by 2nd column (Item)
        "columnDefs": [
            { "orderable": false, "targets": 0 } // Disable sort for Sr. No
        ],
        "pageLength": -1,
        "lengthMenu": [50, 100, 200, -1],
        "language": {
            search: ' ',
            sLengthMenu: '_MENU_',
            searchPlaceholder: "Search",
            sLengthMenu: 'Row Per Page _MENU_ Entries',
            info: "_START_ - _END_ of _TOTAL_ items",
            paginate: {
                next: ' <i class="fa fa-angle-right"></i>',
                previous: '<i class="fa fa-angle-left"></i>'
            },
        },
        "initComplete": function (settings, json) {
            $('.dataTables_filter').appendTo('.search-input');
        },
        "buttons": [
            {
                extend: 'excelHtml5',
                footer: true,
                title: 'Production_Plan_Blend',
                text: '<i class="fa fa-file-excel"></i> Export Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: ':visible',
                    // Generate Sr. No dynamically in export
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0) return row + 1;
                            return data;
                        }
                    }
                }
            },
            {
                extend: 'print',
                footer: true,
                text: '<i class="fa fa-print"></i> Print',
                className: 'btn btn-primary btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0) return row + 1;
                            return data;
                        }
                    }
                }
            },
            {
                extend: 'copy',
                footer: true,
                text: '<i class="fa fa-copy"></i> Copy',
                className: 'btn btn-secondary btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0) return row + 1;
                            return data;
                        }
                    }
                }
            }
        ]
    });

    // Reassign Sr.No. after each order or search
    table.on('order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
    }).draw();
});
</script>
