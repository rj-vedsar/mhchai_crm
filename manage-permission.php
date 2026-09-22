<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
if (isset($roleview['Permission'])) {
} else {
    echo "<script>window.location.assign('../403.php')</script>";
    exit();
}
?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Permission</h4>
                </div>
            </div>
            
        </div>
        <div class="row">
    
        <div class="col-md-4">
            <?php if (isset($roleview['Permission']) && in_array('Assign', $roleview['Permission'])){ ?>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Role & Permission</h2>
                </div>
                <div class="card-body">
                    <form id="permission_submit_update_role">
                        <div class="row">

                            <div class="col-md-12 col-lg-12 col-12 form-group col-sm-12">
                                <label class="font-weight-semibold" for="permission_staff">Employee : </label>
                                <select id="permission_staff" class="select2" name="permission_staff">

                                    <option disabled Selected value="">Select</option>
                                    <?php
                                    $resule_option =  $action->get_admin_function->get_user();
                                    if ($resule_option) {
                                        foreach ($resule_option as $op_data) {
                                    ?>
                                            <option value="<?php echo @$op_data['id']; ?>"><?php echo @$op_data['u_name']; ?> </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-12 col-12 form-group col-sm-12">
                                <label class="font-weight-semibold" for="fac_role">Role :</label>
                                <select id="load_role_for_permission" class="select2" name="fac_role">
                                    <option disabled Selected>Select</option>
                                </select>
                                <span class="mt-1 fac_role"></span>
                            </div>
                            <div class="col-md-12 col-lg-12 col-12 form-group col-sm-12">
                                <button type="submit" class="btn btn-dark btn-block btn-lg" id="permission_staff_button">Apply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <div class="col-md-8">
            <?php if (isset($roleview['Permission']) && in_array('View', $roleview['Permission'])){ ?>
            <div class="card">
                <div class="card-body container">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="datatable table">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Block/Unblock</th>
                                    </tr>
                                </thead>
                                <tbody id="load_members_with_roles">
                                    <?php $action->get_admin_function->load_user_with_role(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
        
    </div>

    
</div>



<?php
require_once('./common/footer.php');
?>