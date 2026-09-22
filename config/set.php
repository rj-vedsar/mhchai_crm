<?php
require('autoload.php');
// Executes the function specified in 'data' key from POST request
if (isset($_POST['data']) && !empty($_POST['data'])) {
    $_POST['data']($mail); // Ensure 'data' contains a valid callable function name
}

// Handle GET requests: Check if 'data' parameter exists and is not empty
// Executes the function specified in 'data' key from GET request
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $_GET['data'](); // Ensure 'data' contains a valid callable function name
}
function login()
{
    global $action;
    $user_email = $action->db->setPostRequiredField('user_email', 'Email is required.');
    $user_pass = $action->db->setPostRequiredField('user_pass', 'Password is required.');
    $res = $action->db->employeeLogin($user_email, sha1($user_pass));
    if (!$res) {
        http_response_code(401);
        echo $action->db->json(401, "Invalid credentials.");
        return;
    }
    $action->session->set("admin_id", $res['id']);
    $action->session->set("role", $res['role']);
    http_response_code(200);
    echo $action->db->json(200, "Login successful.");
    return;
}
function logout()
{
    global $action;
    $action->session->delete('admin_id');
    if (!$action->session->get('admin_id')) {
        echo 1;
        return;
    }
    echo 2;
}
function save_warehouse()
{
    global $action;
    $warehouse_name = $action->db->setPostRequiredField('warehouse_name', 'Warehouse name is required.');
    $updateid = $action->db->validatePostData('updateid') ?: null;
    $checkExist = $action->db->sql("SELECT id FROM aimo_warehouse WHERE wtitle = '{$warehouse_name}'");
    if ($checkExist && (!$updateid || $checkExist[0]['id'] != $updateid)) {
        http_response_code(400);
        echo $action->db->json(400, "Warehouse name already exists.", 'warehouse_name');
        return;
    }
    $data = [
        'wtitle' => $warehouse_name
    ];
    if (!$updateid) {
        $res = $action->db->insert('aimo_warehouse', $data);
        $msg = "Warehouse added successfully.";
    } else {
        $res = $action->db->update('aimo_warehouse', " id = {$updateid}", $data);
        $msg = "Warehouse updated successfully.";
    }
    if (!$res) {
        http_response_code(500);
        echo $action->db->json(500, "Failed to save warehouse.", 'warehouse_name');
        return;
    }
    http_response_code($updateid ? 200 : 201);
    echo $action->db->json($updateid ? 200 : 201, $msg);
}

// Blend Function
function save_blend(){
    global $action;
    $blend_name = $action->db->setPostRequiredField('blend_name', 'Blend name is required.');
    $updateid = $action->db->validatePostData('updateid') ?: null; 

   $cexist = $action->db->sql("SELECT id FROM aimo_blend WHERE btitle = '{$blend_name}'");
   if($cexist && (!$updateid || $cexist[0]['id'] != $updateid)){
    http_response_code(400);
     echo $action->db->json(400, "Blend name is already exists", 'blend_name');
     return;
   }
   $data = [
    'btitle' => $blend_name
   ];
   if (!$updateid) {
    $response = $action->db->insert('aimo_blend', $data);
    $msg = "Blend added successfully.";
   } else {
    $response = $action->db->update('aimo_blend', "id = {$updateid}", $data);
    $msg = "Blend Updated successfully."; 
}
if(!$response){
    http_response_code(500);
    echo $action->db->json(500, 'Failed to save Blend.', 'Blend_name');
    return;
}
 http_response_code(!$updateid ? 200: 201);
 echo $action->db->json($updateid ? 200: 201, $msg);
}
// ! ||--------------------------------------------------------------------------------||
// ! ||                             DELETE DATA                                        ||
// ! ||--------------------------------------------------------------------------------||
function delete_table()
{
    global $action;

    $id = $action->db->sanitizeClientInput($_POST['del_id'] ?? '');
    $table_code = $action->db->sanitizeClientInput($_POST['table_name'] ?? '');

    if (!$id || !$table_code) {
        echo json_encode(['status' => 2, 'msg' => "Invalid request."]);
        return; 
    }

    $table_name = $action->db->getTable($table_code);
    $success = $action->db->update($table_name, "id = '{$id}'", [
        'deleteStatus' => 1,
        'deletedAt' => date('Y-m-d')
    ]);

    echo json_encode([
        'status' => $success ? 1 : 2,
        'msg' => $success ? "Record deleted successfully." : "An error occurred. Please try again later."
    ]);
}

// ! ||--------------------------------------------------------------------------------||
// ! ||                             DELETE DATA Permanently                            ||
// ! ||--------------------------------------------------------------------------------||
function delete_table_permanently()
{
    global $action;

    $id = $action->db->sanitizeClientInput($_POST['del_id'] ?? '');
    $table_code = $action->db->sanitizeClientInput($_POST['table_name'] ?? '');

    if (!$id || !$table_code) {
        echo json_encode(['status' => 2, 'msg' => "Invalid request."]);
        return;
    }

    $table_name = $action->db->getTable($table_code);
    $success = $action->db->deleteTableRow($table_name, $id);
    // $success = $action->db->sql("DELETE FROM {$table_name} WHERE id = '{$id}'");


    echo json_encode([
        'status' => $success ? 1 : 2,
        'msg' => $success ? "Record deleted Permanently." : "An error occurred. Please try again later."
    ]);
}
// ! ||--------------------------------------------------------------------------------||
// ! ||                           RECOVERED DELETED DATA                               ||
// ! ||--------------------------------------------------------------------------------||
function delete_recycle()
{
    global $action;

    $id = $action->db->sanitizeClientInput($_POST['del_id'] ?? '');
    $table_code = $action->db->sanitizeClientInput($_POST['table_name'] ?? '');

    if (!$id || !$table_code) {
        echo json_encode(['status' => 2, 'msg' => "Invalid request."]);
        return;
    }

    $table_name = $action->db->getTable($table_code);
    $success = $action->db->update($table_name, "id = '{$id}'", ['deleteStatus' => 0, 'deletedAt' => null]);

    echo json_encode([
        'status' => $success ? 1 : 2,
        'msg' => $success ? "Data recovered successfully." : "An error occurred. Please try again later."
    ]);
}
// ! ||--------------------------------------------------------------------------------||
// ! ||                                 STATUS TOGGLE                                  ||
// ! ||--------------------------------------------------------------------------------||
function check_status()
{
    global $action;

    $status = isset($_POST['inactive']) ? 0 : (isset($_POST['active']) ? 1 : null);
    $id = $action->db->sanitizeClientInput($_POST['inactive'] ?? $_POST['active'] ?? '');
    $table_code = $action->db->sanitizeClientInput($_POST['table_name'] ?? '');
    if (is_null($status) || !$id || !$table_code) {
        echo $action->db->json(4, "Invalid request.");
        return;
    }

    $table_name = $action->db->getTable($table_code);
    $success = $action->db->update($table_name, "id = '{$id}'", ['status' => $status]);

    echo $action->db->json(
        $success ? ($status ? 1 : 3) : ($status ? 2 : 4),
        $success ? "Status " . ($status ? "active" : "inactive") . " successfully." : "An error occurred. Please try again later."
    );
}

function checkbx_status()
{
    global $action;



    $status = isset($_POST['inactive']) ? 0 : (isset($_POST['active']) ? 1 : null);
    $id = $action->db->sanitizeClientInput($_POST['inactive'] ?? $_POST['active'] ?? '');
    $orderid = $action->db->sanitizeClientInput($_POST['orderid'] ?? 0);
    $itemid = $action->db->sanitizeClientInput($_POST['itemid'] ?? 0);
    if (is_null($status) || !$id) {
        echo $action->db->json(4, "Invalid request.");
        return;
    }

    if ($id == 'na') {
        $checkExist = $action->db->sql("SELECT `id` FROM `aimo_tick_order` WHERE `order_id`='$orderid' AND `item_id`='$itemid'");
        if ($checkExist) {
            $success = $action->db->update("aimo_tick_order", "id = '{$checkExist[0]['id']}'", ['status' => $status]);
        } else {

            $success = $action->db->insert("aimo_tick_order", ['order_id' => $orderid, 'item_id' => $itemid, 'status' => $status]);
        }
    } else {


        $success = $action->db->update("aimo_tick_order", "id = '{$id}'", ['status' => $status]);
    }

    echo $action->db->json(
        $success ? ($status ? 1 : 3) : ($status ? 2 : 4),
        $success ? "Item " . ($status ? "Checked" : "Unchecked") . " successfully." : "An error occurred. Please try again later."
    );
}
// FETCH JSON WAREHOUSE DATA
function fetchWareHouseJson()
{
    global $action;
    $id = $action->db->sanitizeClientInput($_POST['id'] ?: '');
    echo $action->get_admin_function->jsonData(2, $id);
}

// blend function 
function fetchBlendJson()
{
    global $action;
    $id = $action->db->sanitizeClientInput($_POST['id'] ?: '');
    echo $action->get_admin_function->jsonData(6, $id); 
}
function save_item()
{
    global $action;
    $id = $action->db->sanitizeClientInput($_POST['id'] ?: '');
    $item_name = $action->db->validatePostData('item_name') ?: null;
    $print_name = $action->db->validatePostData('print_name') ?: null;
    $item_qty = $action->db->validatePostData('item_qty') ?: 0;
    if (isset($_POST['alt_qty']) && !empty($_POST['alt_qty'])) {
        $alt_qty = $action->db->sanitizeClientInput($_POST['alt_qty']);
    } else {
        $alt_qty = 0;
    }
    $coupon_point = $action->db->validatePostData('coupon_point') ?: 0;
    $blendid = $action->db->validatePostData('blendid') ?: 0;
    $warehouseid = $action->db->setPostRequiredField('warehouseid', 'Warehouse is required');
    if (!$warehouseid) {
        http_response_code(400);
        echo $action->db->json(400, "Warehouse is required", "warehouseid");
        return;
    }
    $remark = $action->db->validatePostData('remark') ?: null;
    $data = [
        'item_name' => $item_name,
        'print_name' => $print_name,
        'item_qty' => $item_qty,
        'alt_qty' => $alt_qty,
        'coupon_point' => $coupon_point,
        'warehouseid' => $warehouseid,
        'remark' => $remark,
        'blendid' => $blendid
    ];
    if (!$id) {
        $res = $action->db->insert('aimo_item', $data);
        $msg = "Item added successfully.";
    } else {
        $res = $action->db->update('aimo_item', "id = '{$id}'", $data);
        $msg = "Item updated successfully.";
    }
    if (!$res) {
        http_response_code(500);
        echo $action->db->json(500, "An error occurred. Please try again later");
        return;
    }
    http_response_code($id ? 200 : 201);
    echo $action->db->json($id ? 200 : 201, $msg);
    return;
}
function upload_csv_item()
{
    global $action;

    if (!isset($_FILES['csvfile']) || empty($_FILES['csvfile']['name'])) {
        http_response_code(400);
        echo $action->db->json(400, "Please choose a CSV file to import items.");
        return;
    }

    $file = $_FILES['csvfile'];
    $tmpName = $file['tmp_name'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($fileExtension !== 'csv') {
        http_response_code(400);
        echo $action->db->json(400, "Invalid file type. Please upload a CSV file.");
        return;
    }

    if (($handle = fopen($tmpName, 'r')) === FALSE) {
        http_response_code(500);
        echo $action->db->json(500, "Failed to open the CSV file.");
        return;
    }

    set_time_limit(0);
    $row = 0;
    $warehouseCache = []; // Caching warehouse IDs

    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        if ($row === 0) { // Skip the header row
            $row++;
            continue;
        }

        $item_name = sanitizeInput($data, 1);
        $print_name = sanitizeInput($data, 2);
        $item_qty = sanitizeInput($data, 3, 0);
        $coupon_point = sanitizeInput($data, 4, 0);
        $warehouse = sanitizeInput($data, 5);

        if (!$item_name || !$print_name || !$warehouse) {
            continue; // Skip invalid rows
        }

        // Check warehouse cache first
        if (!isset($warehouseCache[$warehouse])) {
            $checkExist = $action->db->sql("SELECT id FROM aimo_warehouse WHERE wtitle = '{$warehouse}'");
            if (!$checkExist) {
                $warehouseid = $action->db->insert('aimo_warehouse', ['wtitle' => $warehouse]);
            } else {
                $warehouseid = $checkExist[0]['id'];
            }
            $warehouseCache[$warehouse] = $warehouseid;
        } else {
            $warehouseid = $warehouseCache[$warehouse];
        }
        $existItem = $action->db->sql("SELECT id FROM aimo_item WHERE item_name = '{$item_name}' AND print_name = '{$print_name}' AND item_qty = '{$item_qty}' AND coupon_point = '{$coupon_point}' AND warehouseid = '{$warehouseid}'");
        if (!$existItem) {
            // Insert item
            $insert = $action->db->insert('aimo_item', [
                "item_name" => $item_name,
                "print_name" => $print_name,
                "item_qty" => $item_qty,
                "coupon_point" => $coupon_point,
                "warehouseid" => $warehouseid,
            ]);

            if (!$insert) {
                fclose($handle);
                http_response_code(500);
                echo $action->db->json(500, "Error inserting data for item: {$item_name}");
                return;
            }
        }


        $row++;
    }

    fclose($handle);
    echo $action->db->json(200, "CSV file imported successfully.");
}

// Helper function to sanitize input
function sanitizeInput($data, $index, $default = null)
{
    global $action;
    return isset($data[$index]) && !empty($data[$index]) ? $action->db->sanitizeClientInput($data[$index]) : $default;
}
function save_party()
{
    global $action;
    $party_name = $action->db->setPostRequiredField('party_name', 'Party name is required.');
    $party_phone = $action->db->validatePostData('party_phone') ?: null;
    $address = $action->db->validatePostData('address') ?: null;
    $updateid = $action->db->validatePostData('updateid') ?: null;
    $checkExist = $action->db->sql("SELECT id FROM aimo_party WHERE party_name = '{$party_name}' AND party_phone = '{$party_phone}'");
    if ($checkExist && (!$updateid || $checkExist[0]['id'] != $updateid)) {
        http_response_code(400);
        echo $action->db->json(400, "Party name and phone already exist.");
        return;
    }
    $data = [
        "party_name" => $party_name,
        "party_phone" => $party_phone,
        "address" => $address
    ];
    if (!$updateid) {
        $res = $action->db->insert('aimo_party', $data);
        $msg = "Party created successfully.";
        $data['id'] = $res;
    } else {
        $res = $action->db->update('aimo_party', "id = {$updateid}", $data);
        $msg = "Party updated successfully.";
    }
    if (!$res) {
        http_response_code(500);
        echo $action->db->json(500, "Error saving party.");
        return;
    }
    http_response_code($updateid ? 200 : 201);
    echo $action->db->json($updateid ? 200 : 201, $msg, null, $data);
}
function fetchItemByid()
{
    global $action;
    $item_id = $action->db->validatePostData('item_id') ?: null;
    if ($item_id) {
        echo $action->get_admin_function->jsonData(3, $item_id);
    }
}
function fetchWareHouseName()
{
    global $action;
    if (isset($_POST['ids'])) {
        // Decode the JSON data
        $ids = json_decode($_POST['ids'], true);

        // Filter out any non-numeric or empty IDs
        $ids = array_filter($ids, function ($id) {
            return is_numeric($id) && $id > 0;  // Ensure the ID is numeric and greater than 0
        });

        // Extract only the keys from the filtered array
        $keys = array_keys($ids);

        // Convert the keys into a comma-separated string
        $keys_str = implode(',', $keys);

        // If there are valid IDs, proceed with the query (optional)
        if (!empty($ids)) {
            $res = $action->db->sql("SELECT id, wtitle FROM aimo_warehouse WHERE id IN (" . $keys_str . ")");
            // If needed, you can print the query or results for debugging
            if ($res) {
                echo json_encode($res);
            }
        } else {
        }
    }
}
function pending_order_save()
{
    global $action;
    $note = $action->db->validatePostData('note') ?: '';
    $order_date = $action->db->setPostRequiredField('order_date', 'Order date is required.');
    $pending_party = $action->db->setPostRequiredField('pending_party', 'Party field is required.');
    $note = $action->db->validatePostData('note') ?: '';
    $no_of_bags = $action->db->setPostRequiredField('no_of_bags', 'Total number of bags is required.');
    $no_of_coupon = $action->db->validatePostData('no_of_coupon') ?: 0;
    $t_weight = $action->db->setPostRequiredField('t_weight', 'Total kgs is required.');
    $vehicle_no = $action->db->validatePostData('vehicle_no') ?: '';
    $updateid = $action->db->validatePostData('updateid') ?: null;
    $dispatched_date = $action->db->validatePostData('dispatched_date') ?: null;
    $item_id = $_POST['item_id'];
    $item_qty = $_POST['item_qty'];
    $item_kg = $_POST['item_kg'];
    $item_coupon = $_POST['item_coupon'];
    $warehouseid = $_POST['warehouseid'];
    $total_item_coupon = $_POST['total_item_coupon'];
    $total_item_weight = $_POST['total_item_weight'];
    $remark = @$_POST['remark'];

        $dataArray = [];
        foreach ($item_id as $index => $val) {
            if($action->db->sanitizeClientInput($item_id[$index])){
            $item = [
                'item_id' => $action->db->sanitizeClientInput($item_id[$index]),
                'item_qty' => $action->db->sanitizeClientInput($item_qty[$index]),
                'item_kg' => $action->db->sanitizeClientInput($item_kg[$index]),
                'item_coupon' => $action->db->sanitizeClientInput($item_coupon[$index]),
                'warehouseid' => $action->db->sanitizeClientInput($warehouseid[$index]),
                'total_item_coupon' => $action->db->sanitizeClientInput($total_item_coupon[$index]),
                'total_item_weight' => $action->db->sanitizeClientInput($total_item_weight[$index]),
                'remark' => base64_encode($action->db->sanitizeClientInput($remark[$index])),
            ];
            $dataArray[] = $item;
        }
        }
        $data = [
            'dispatched_date' => $dispatched_date,
            'note' => $note,
            'order_date' => $order_date,
            'pending_party' => $pending_party,
            'no_of_bags' => $no_of_bags,
            'no_of_coupon' => $no_of_coupon,
            't_weight' => $t_weight,
            'vehicle_no' => $vehicle_no,
            'item' => json_encode($dataArray, JSON_UNESCAPED_UNICODE)
        ];
                $isFileUploaded = isset($_FILES['p_file']['name']) && !empty($_FILES['p_file']['name']);
                $uploadDir = '../upload/';

                if ($isFileUploaded) {
                $file = $_FILES['p_file'];
                $fileNameParts = explode('.', $file['name']);
                $extension = strtolower(end($fileNameParts));
                $allowedExtensions = ['webp', 'png', 'jpg', 'jpeg'];

                if (!in_array($extension, $allowedExtensions)) {
                    http_response_code(400);
                    echo $action->db->json(400, "Allowed file types: " . implode(', ', $allowedExtensions));
                    return;
                }

                if ($file['size'] > 2 * 1024 * 1024) {
                    http_response_code(400);
                    echo $action->db->json(400, "File size must not exceed 2MB.");
                    return;
                }

                $uniqueName = $file['name'];
                $destination = $uploadDir . $uniqueName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    http_response_code(500);
                    echo $action->db->json(500, "Failed to upload file.");
                    return;
                }

                $data['p_file'] = $uniqueName;
                }


        if (!$updateid) {
            $response = $action->db->insert('aimo_order', $data);
            $msg = "Order created.";
            $updateid = $response;
        } else {
            $response = $action->db->update('aimo_order', "id = {$updateid}", $data);
            $msg = "Order updated.";
        }
        if (!$response) {
            http_response_code(500);
            echo $action->db->json(500, "Internal server error");
            return;
        }
        http_response_code($updateid ? 200 : 201);
        echo $action->db->json($updateid ? 200 : 201, $msg, null, $updateid);
 
}

function reset_password_admin()
{
    global $action;

    $old = $action->db->setPostRequiredField('old-password', "Please enter your old Password.");
    $new = $action->db->setPostRequiredField('new-password', "Please enter your new Password.");
    $cnf = $action->db->setPostRequiredField('confirm-password', "Please confirm your new Password.");

    if (sha1($cnf) == sha1($new)) {
        $pass_old = sha1($old);
        if ($action->session->get("admin_id") != "") {
            $id = $action->session->get("admin_id");

            $select = $action->db->sql("SELECT * FROM aimo_users WHERE id = '{$id}' AND password = '{$pass_old}'");
            if ($select) {
                $update = $action->db->update('aimo_users', "id='{$id}'", ['password' => sha1($cnf)]);
                if ($update) {
                    echo $action->db->json(200, "Password Changed successfully.");
                    http_response_code(200);
                    return;
                } else {
                    echo $action->db->json(500, "Internal Server Error.");
                    http_response_code(500);
                    return;
                }
            } else {
                echo $action->db->json(400, "Old Password that you have entered is incorrect.");
                http_response_code(400);
                return;
            }
        }
    } else {
        echo $action->db->json(400, "Both Password Should Match.");
        http_response_code(400);
        return;
    }


}


function role_add()
{
    global $action;

    if (!empty($_POST['roleName'])) {
        $new_role = implode("$;", $_POST['roleName']);
        $new_role = $action->db->sanitizeClientInput($new_role);
    } else {
        $json = $action->db->json(400, "Please select atleast one permission.");
    }
    // role name
    if (isset($_POST['new_role_name']) && !empty($action->db->sanitizeClientInput($_POST['new_role_name']))) {
        $role_name = $action->db->sanitizeClientInput($_POST['new_role_name']);
    } else {
        $json = $action->db->json(400, "Please enter a role name.");
    }
    ##<------------------------------- hidden_id --------------------------->
    if (isset($_POST['hidden_id']) && !empty($action->db->sanitizeClientInput($_POST['hidden_id']))) {
        $hidden_id = $action->db->sanitizeClientInput($_POST['hidden_id']);
    } else {
        $hidden_id = "";
    }
    if (!isset($json) || empty($json)) {

        // Combine role names and permissions
        $combined_data = array();
        $keyArray = [];
        if (isset($new_role)) {
            // Sanitize and validate input
            if (!empty($_POST['role'])) {
                $new_role = "";
                foreach ($_POST['role'] as $key => $value) {
                    $new_role = implode(",", $value);
                    $new_role = $action->db->sanitizeClientInput($new_role);
                    $rolePermission = explode(',', $new_role);
                    foreach ($rolePermission as $rpData) {
                        $combined_data[$key][] = $rpData;
                    }
                }
            } else {
                $json = $action->db->json(400, "Please select atleast one permission.");
            }
            

        }
        $finalData = json_encode($combined_data);
        if (empty($hidden_id)) {
            $result = $action->get_admin_function->get_role($role_name);
            if ($result) {
                $json['status'] = 400;
                $json['msg'] = "Role already registered.";
            } else {
                $field['role_name'] = $role_name;
                $field['role'] = $finalData;
                $field['date'] = date('Y-m-d H:m:s');
                $insert = $action->db->insert($action->db->getTable(10), $field);
                if ($insert) {
                    $json = $action->db->json(200, "Record Successfully Saved.");
                } else {
                    $json = $action->db->json(500, "An error occurred. Please try again later.");
                }
            }
        } elseif (!empty($hidden_id)) {

            $success = $action->db->update($action->db->getTable(10), "id= '{$hidden_id}'", ['role_name' => $role_name, 'role' => $finalData]);
            if ($success) {
                $json = $action->db->json(200, "Record Successfully Updated.");
            } else {
                $json = $action->db->json(500, "An error occurred. Please try again later.");
            }
        }
    }
    echo $json;
}
function load_append_role()
{
    global $action;
    if (isset($_POST['role_ed']) && !empty($action->db->sanitizeClientInput($_POST['role_ed']))) {
        $id = $action->db->sanitizeClientInput($_POST['role_ed']);
        echo $action->get_admin_function->get_append_role($id);
    }
}
function staff_permission()
{
    global $action;
    if (isset($_POST['fac_role']) && !empty($action->db->sanitizeClientInput($_POST['fac_role']))) {
        $role = $action->db->sanitizeClientInput($_POST['fac_role']);
    } else {
        $json = $action->db->json(101, "Please select the role.");
    }

    if (isset($_POST['permission_staff']) && !empty($action->db->sanitizeClientInput($_POST['permission_staff']))) {
        $permission_staff = $action->db->sanitizeClientInput($_POST['permission_staff']);
    } else {
        $json = $action->db->json(101, "Please select an user.");
    }
    if (!empty($role) && !empty($permission_staff)) {
        $success = $action->db->update($action->db->getTable(1), "id='{$permission_staff}'", ['role' => $role]);
        if ($success) {
            $json = $action->db->json(100, "Permission granted successfully.");
        } else {
            $json = $action->db->json(101, "An error occurred. Please try again later.");
        }
    }
    echo $json;
}
function load_user_with_role()
{
    global $action;
    echo $action->get_admin_function->load_user_with_role();
}

function fetch_role_by_staff()
{
    global $action;
    if (isset($_POST['role_id']) && !empty($action->db->sanitizeClientInput($_POST['role_id']))) {
        $id = $action->db->sanitizeClientInput($_POST['role_id']);
        echo $action->get_admin_function->fetch_role_by_staff($id);
    }
}

function save_user()
{
    global $action;
    $u_name = $action->db->setPostRequiredField('u_name', 'User name is required.');
    $u_email = $action->db->setPostRequiredField('u_email',"User Email is required") ;
    $password = $action->db->validatePostData('password') ?: null;
    $updateid = $action->db->validatePostData('updateid') ?: null;
    $checkExist = $action->db->sql("SELECT id FROM aimo_party WHERE u_email = '{$u_email}' ");
    if ($checkExist && (!$updateid || $checkExist[0]['id'] != $updateid)) {
        http_response_code(400);
        echo $action->db->json(400, "User with same email already exist.");
        return;
    }
    $data = [
        "u_name" => $u_name,
        "u_email" => $u_email,
    ];
    if (!$updateid) {
        if(empty($password)){
            $passwordhash= sha1('123456');
        }
        else{
            $passwordhash= sha1($password);
        }
        $data['password']=$passwordhash;
        $res = $action->db->insert('aimo_users', $data);
        $msg = "User created successfully.";
        $data['id'] = $res;
    } else {
        if(!empty($password));
        {
            $data['password']=sha1($password);
        }
        $res = $action->db->update('aimo_users', "id = {$updateid}", $data);
        $msg = "User updated successfully.";
    }
    if (!$res) {
        http_response_code(500);
        echo $action->db->json(500, "Error saving party.");
        return;
    }
    http_response_code($updateid ? 200 : 201);
    echo $action->db->json($updateid ? 200 : 201, $msg, null, $data);
}


?>