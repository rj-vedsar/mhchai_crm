<?php
class get_admin_function extends Database
{
  public function noData($col)
  {
    $html = '
      <tr class="mx-auto">
        <td colspan="' . $col . '">
          <div class="row">
            <div class="col-md-12 text-center mx-auto">
              <div class="card bg-transparent">
                <div class="card-body">
                  <img src="./assets/nodb.png" style="border-radius: 10px;" class="img-fluid" alt="">
                  <div class="card-text mt-3 text-danger">There is no data to show you at the moment.</div>
                </div>
              </div>
            </div>
          </div>
        </td>
      </tr>
      ';
    return $html;
  }
  public function get_ellipsis(
    $str,
    $offset = 0,
    $limit = 30,
    $con = true
  ) {
    if (strlen($str) > $limit) {
      $str = substr($str, $offset, $limit);
      if ($con) {
        $str .= "...";
      }
    }
    return $str;
  }
  public function fetchUsers($id = null, $email = null)
  {
    $q = "SELECT * FROM aimo_users WHERE 1";
    if ($id)
      $q .= " AND id = {$id}";
    if ($email)
      $q .= " AND user_email = '{$email}'";
    return $this->sql($q) ?: [];
  }
  public function fetchWarehouse($id = null, $status = null, $deleteStatus = null)
  {
    $q = "SELECT * FROM aimo_warehouse WHERE 1";
    if ($id)
      $q .= " AND id = {$id}";
    if ($status === 0 || $status === 1)
      $q .= " AND status = {$status}";
    if ($deleteStatus === 0 || $deleteStatus === 1)
      $q .= " AND deleteStatus = {$deleteStatus}";
    return $this->sql($q) ?: [];
  }
  public function fetchBlend($id = null, $status = null, $deleteStatus = null)
  {
    $q = "SELECT * FROM aimo_blend WHERE 1";
    if ($id)
      $q .= " AND id = {$id}";
    if ($status === 0 || $status === 1)
      $q .= " AND status = {$status}";
    if ($deleteStatus === 0 || $deleteStatus === 1)
      $q .= " AND deleteStatus = {$deleteStatus}";
    return $this->sql($q) ?: [];
  }
  public function fetchItem($id = null, $status = null, $deleteStatus = null)
  {
    $q = "SELECT * FROM aimo_item WHERE 1";
    if ($id)
      $q .= " AND id = {$id}";
    if ($status === 0 || $status === 1)
      $q .= " AND status = {$status}";
    if ($deleteStatus === 0 || $deleteStatus === 1)
      $q .= " AND deleteStatus = {$deleteStatus}";
    return $this->sql($q) ?: [];
  }
  public function fetchItemDropdown()
  {
    return $this->sql("SELECT item_name, print_name, id FROM aimo_item WHERE deleteStatus = 0") ?: [];
  }
  public function fetchParty($id = null, $status = null, $deleteStatus = null)
  {
    $q = "SELECT * FROM aimo_party WHERE 1";
    if ($id)
      $q .= " AND id = {$id}";
    if ($status === 0 || $status === 1)
      $q .= " AND status = {$status}";
    if ($deleteStatus === 0 || $deleteStatus === 1)
      $q .= " AND deleteStatus = {$deleteStatus}";
    return $this->sql($q) ?: [];
  }
  public function jsonData($tableCode, $id)
  {
    $q = "SELECT * FROM {$this->getTable($tableCode)} WHERE id = {$id}";
    $res = $this->sql($q);
    if ($res) {
      foreach ($res as $d) {
        echo json_encode($d);
      }
    }
  }


  public function getRoleArray()
  {
    $permissions = array(
      array(
        "title" => "Dashboard",
        "items" => array(
          array("label" => "Dashboard", "value" => "Dashboard", "crud" => array("View")),
        )
      ),

      array(
        "title" => "Order Management",
        "items" => array(
          array("label" => "Order Management", "value" => "Order Management", "crud" => array("Create", "View", "Update", "Delete", "Recover", "Delete Permanently")),
        )
      ),
      array(
        "title" => "Production Plan (Wareh.)",
        "items" => array(
          array("label" => "Production Plan (Wareh.)", "value" => "Production Plan (Wareh.)", "crud" => array("View")),
        )
      ),
      array(
        "title" => "Production Plan (Blend)",
        "items" => array(
          array("label" => "Production Plan (Blend)", "value" => "Production Plan (Blend)", "crud" => array("View")),
        )
      ),
      array(
        "title" => "Item Management",
        "items" => array(
          array("label" => "Item Management", "value" => "Item Management", "crud" => array("Create", "Import", "View", "Update", "Delete", "Status", "Recover", "Delete Permanently")),
        )
      ),
      array(
        "title" => "Party Management",
        "items" => array(
          array("label" => "Party Management", "value" => "Party Management", "crud" => array("Create", "Import", "View", "Update", "Delete", "Status", "Recover", "Delete Permanently")),
        )
      ),
      array(
        "title" => "User Management",
        "items" => array(
          array("label" => "User Management", "value" => "User Management", "crud" => array("Create", "View", "Update", "Delete", "Recover", "Delete Permanently")),
        )
      ),
      array(
        "title" => "Warehouse",
        "items" => array(
          array("label" => "Warehouse", "value" => "Warehouse", "crud" => array("Create", "View", "Update", "Delete", "Status", "Recover", "Delete Permanently")),
        )
      ),
      array(
        "title" => "Blend",
        "items" => array(
          array("label" => "Blend", "value" => "Blend", "crud" => array("Create", "View", "Update", "Delete", "Status", "Recover", "Delete Permanently")),
        )
      ),

      array(
        "title" => "Role",
        "items" => array(
          array("label" => "Role", "value" => "Role", "crud" => array("Create", "View", "Update", "Delete","Status", "Recover", "Delete Permanently")),
        )
      ),
      array(
        "title" => "Permission",
        "items" => array(
          array("label" => "Permission", "value" => "Permission", "crud" => array("Assign", "View", "Block/Unblock")),
        )
      ),
    );
    return $permissions;
  }
  public function get_role($id = null, $name = null, $status = 0)
  {
    $conditions = [];
    if ($id !== null) {
      $conditions[] = "id = '{$id}'";
    }
    if ($status) {
      $conditions[] = "status = '{$status}'";
    }
    if ($name !== null) {
      $conditions[] = "role_name = '{$name}'";
    }
    $whereClause = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";
    $sql = $this->sql("SELECT * FROM " . $this->getTable(10) . $whereClause . " ORDER BY id DESC");
    return $sql ? $sql : false;
  }

  public function load_role_data($delete = 0)
  {
    $sql = $this->sql(" SELECT * FROM " . $this->getTable(10) . " WHERE deleteStatus= '$delete'");

    if ($sql) {
      $role_data = @$this->get_role(@$_SESSION["role"], null, 1);
      $roleview = json_decode($role_data[0]['role'], true);





      $counter = $ch1 = $ch2 = 1;
      foreach ($sql as $se_data) {
?>
        <tr>
          <td><?= @$counter;

              $counter++; ?></td>
          <td style="white-space: nowrap;" class="text-capitalize"><?= @$se_data['role_name']; ?></td>
          <td class="text-capitalize">
            <?php

            $decodeData = json_decode($se_data['role'], true);

            if ($decodeData) {
              foreach ($decodeData as $roleName => $permissions) {
            ?>
                <p class="mb-1"><strong><?= $roleName; ?></strong> :

                  <?php
                  $colors = ['avatar-magenta', 'avatar-blue', 'avatar-green', 'avatar-yellow', 'avatar-red']; // Define your color classes

                  $badges = array_map(function ($permission) use ($colors) {
                    $random_color = $colors[array_rand($colors)];
                    return "<span class=\"badge badge-pill $random_color mr-2 mt-2\">$permission</span>";
                  }, $permissions);

                  echo implode('', $badges);
                  ?>
                </p>
            <?php
              }
            } ?>
          </td>

          <td style="white-space: nowrap;"><?= (@$se_data['date']); ?></td>
          <td>
            <?php //if (strtolower($se_data['role_name']) != 'super admin') { 
            ?>

            <div class="d-flex m-auto text-center">
              <?php if ($se_data['deleteStatus'] == 0) { ?>
              <?php if (isset($roleview['Role']) && in_array('Status', $roleview['Role'])) { ?>
                <div class="switch">
                  <input type="checkbox" class="role-checkbox" id="switch-<?= $ch2;
                                                                          $ch2++; ?>" <?php if ($se_data['status'] == 1) {
                                                                                        echo "checked";
                                                                                      } ?> data-checkid="<?= @$se_data['id']; ?>">
                  <label class="mt-1" for="switch-<?= $ch1;
                                                  $ch1++; ?>"></label>
                </div>

                <?php } ?>

                <?php if (isset($roleview['Role']) && in_array('Update', $roleview['Role'])){ ?>

                <div class="avatar avatar-icon avatar-md avatar-blue">
                  <button class="text-success border-0 edit_role" data-toggle="modal" data-target="#update_role"
                    data-eid="<?= $se_data['id']; ?>"><i class="fas fa-edit"></i></button>
                </div>
              <?php }
              ?>
              <?php } ?>
              <?php if ($se_data['deleteStatus'] == 1) { ?>
                <?php if (isset($roleview['Role']) && in_array('Recover', $roleview['Role'])) { ?><button type="button" class="p-2 border-0 bg-transparent recover-role"
                    data-delid="<?= @$se_data['id']; ?>">
                    <i data-feather="refresh-ccw" class="feather-refresh-ccw text-success"></i>
                  </button><?php } ?>
                <?php if (isset($roleview['Role']) && in_array('Delete Permanently', $roleview['Role'])) { ?><button type="button" class="p-2 border-0 bg-transparent delete-role-permanently"
                    data-delid="<?= @$se_data['id']; ?>">
                    <i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
                  </button><?php } ?>
              <?php } else { ?>
                <?php if (isset($roleview['Role']) && in_array('Delete', $roleview['Role'])) { ?><button type="button" class="p-2 border-0 bg-transparent delete-role"
                    data-delid="<?= @$se_data['id']; ?>">
                    <i data-feather="trash-2" class="feather-trash-2 text-danger"></i>
                  </button><?php } ?>
              <?php } ?>

            </div>
            <?php //} 
            ?>
          </td>
        </tr>

      <?php }
    } else { ?>
      <?= $this->noData(40); ?>
    <?php }
  }
  public function get_append_role($id)
  {
    $select = $this->select_assoc($this->getTable(10), "id = '{$id}'", "*");
    if ($select) {
      foreach ($select as $data) {
        $role_id = $data['id'];
        $role_name = $data['role_name'];
        $role = $data['role'];
      }
      $ex_role = explode("$;", $role);
      $role_new = array_values($ex_role);
      $decodeData = json_decode($data['role'], true);
    }
    ?>
    <div class="form-group">
      <input type="hidden" name="hidden_id" value="<?= @$role_id; ?>">
      <label for="new_role_name">Role Name</label>
      <input type="text" name="new_role_name" id="new_role_name" value="<?= $role_name; ?>" class="form-control"
        placeholder="Role Name">
      <span class="new_role_name"></span>
    </div>

    <label>Role Permission</label>
    <br>
    <span class="role_permisson mt-2"></span>
    <div class="row">
      <?php
      $permissions = $this->getRoleArray();
      $sr = 0;
      foreach ($permissions as $section) { ?>
        <div class="col-12 col-md-12 my-2">
          <h5 class="card-title text-muted"><?= $section["title"]; ?></h5>
        </div>

        <?php foreach ($section["items"] as $item):
          $sr++;
        ?>
          <div class="col-12 col-md-12 my-2">
            <h6 class="card-title font-12"><?= $item["label"]; ?></h6>
          </div>
          <?php
          if (isset($item['crud'])):
            foreach ($item['crud'] as $item2): ?>

              <?php if ($item2): ?>
                <input type="hidden" name="roleName[]" value="<?= $item['label']; ?>">
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
                    <input type="checkbox" name="role[<?= $label; ?>][]" id="<?= $item2 . $sr; ?>" value="<?= $item2; ?>"
                      class="form-check-input role_checkbox" <?= $checked; ?>>
                    <label for="<?= $item2 . $sr; ?>"><?= $item2; ?></label>
                  </div>
                </div>

              <?php endif; ?>

          <?php endforeach;
          endif; ?>
        <?php endforeach; ?>
      <?php } ?>

    </div>
    <div class="row mt-3">
      <div class="col-md-12 text-right">
        <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
        <button class="btn btn-dark"> Save </button>
      </div>
    </div>
    <?php
  }

  public function get_user(
    $email = null,
    $pass = null,
    $id = null,
    $customLimit = null,
    $status = null,
    $limit = null,
    $duname = null
  ) {
    $sql1 = "SELECT * FROM " . $this->getTable(1) . " WHERE 1 ";


    if ($duname !== null) {
      $sql1 .= " AND u_name = '{$duname}'";
    }
    if ($pass !== null) {
      $sql1 .= " AND pass = '{$pass}'";
    }

    if ($email !== null) {
      $sql1 .= " AND u_email = '{$email}' ";
    }

    if ($id !== null) {
      $sql1 .= " AND id = '{$id}'";
    }

    if ($status !== null) {
      $sql1 .= " AND status = '{$status}'";
    }

    $sql1 .= " ORDER BY `id` DESC";
    if ($customLimit !== NULL) {
      $sql1 .= " $customLimit";
    }
    if ($limit !== NULL) {
      $sql1 .= " LIMIT '{$limit}'";
    }
    $sql = $this->sql($sql1);
    if ($sql) {
      return $sql;
    } else {
      return false;
    }
  }

  public function load_user_with_role()
  {
    $role_data = @$this->get_role(@$_SESSION["role"], null, 1);
    $roleview = json_decode($role_data[0]['role'], true);
    $sql = $this->get_user();
    if ($sql) {
      $ch1 = $ch2 = $count = 1;
      foreach ($sql as $op_data) {
        $role_idd = $op_data['role'];
        $resule_option11 = $this->get_role();
        if ($resule_option11) {
          foreach ($resule_option11 as $op_data22) {
            if (isset($role_idd) && $role_idd == $op_data22['id']) {
    ?>
              <tr>
                <td><?= @$count;
                    $count++; ?></td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="d-flex align-items-start pt-2">
                      <?php if (isset($op_data['avtar']) && !empty($op_data['avtar']) && file_exists('../../upload/user/' . @$op_data['uid'] . '/' . @$op_data['avtar'])) { ?>
                        <div class="avatar avatar-badge avatar-image" style="height: 40px; min-width: 40px; max-width:40px">
                          <img src="../upload/user/<?= @$op_data['uid'] . '/' . @$op_data['avtar']; ?>"
                            style="height: 40px; min-width: 40px; max-width:40px" alt="<?= @$op_data['u_name']; ?>">
                        </div>
                      <?php } else { ?>
                        <div class="avatar avatar-badge avatar-icon avatar-green d-flex justify-content-center align-items-center"
                          style="height: 40px; min-width: 40px; max-width:40px">
                          <span><?= substr(@$op_data['u_name'], 0, 1); ?></span>
                        </div>
                      <?php } ?>
                      <h6 class="m-l-10 m-b-0 d-flex justify-content-start align-items-start flex-column">
                        <span class="font-14 my-0"><?= @$op_data['u_name']; ?></span>
                        <small class="font-12"><?= @$op_data['u_email']; ?></small>

                      </h6>
                    </div>
                  </div>
                </td>
                <td><?= @$op_data22['role_name']; ?></td>
                <td>
                  <?php if ($_SESSION['admin_id'] == @$op_data['id']) {
                  } else {
                    if (isset($roleview['Permission']) && in_array('Block/Unblock', $roleview['Permission'])) {
                  ?>
                      <div class="switch mx-2">
                        <input type="checkbox" class="user-checkbox" id="switch-<?= $ch2;
                                                                                $ch2++; ?>" <?php if ($op_data['status'] == 1) {
                                                                                              echo "checked";
                                                                                            } ?> data-checkid="<?= @$op_data['id']; ?>">
                        <label for="switch-<?= $ch1;
                                            $ch1++; ?>"></label>
                      </div>

                  <?php }
                  } ?>
                </td>
              </tr>

        <?php
            }
          }
        }
      }
    } else {
      echo "<tr><td class='text-center'><span class='badge badge-danger py-2 px-3 h6'>No Record Found</span></td></tr>";
    }
  }

  public function fetch_role_by_staff($id)
  {
    $result = $this->get_user(null, null,  $id);
    if ($result) {
      foreach ($result as $data) {


        $sql = $this->sql("SELECT * FROM " . $this->getTable(10) . " WHERE deleteStatus=0 ORDER BY id DESC");
        ?>
        <option value="">Select</option>
        <?php
        if ($sql) {

          foreach ($sql as $op_data22) { ?>
            <option value="<?= @$op_data22['id']; ?>" <?php if (isset($data['role']) && $data['role'] === $op_data22['id']) {
                                                        echo "Selected";
                                                      } ?>><?= @$op_data22['role_name']; ?></option>

<?php }
        } else {
          return false;
        }
      }
    } else {
      return false;
    }
  }

  // END OF CLASS
}
?>