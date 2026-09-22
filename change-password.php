<?php
require_once('./common/head.php');
require_once('./common/header.php');
require_once('./common/sidebar.php');
?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Change Password</h4>

                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="am-page-upr-btn">

                <div class="page-btn goBack">
                    <a href="#" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h2 class="card-title">Change Your Password</h2>
                    </div>
                    <div class="card-body">
                        <form class="d-flex justify-content-center" id="reset_password_admin">
                            <div class="row w-100">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="old-password">Old Password:</label>
                                    <input type="password" id="old-password" name="old-password" class="form-control">
                                    <span class="text-danger old_pass"></span>
                                </div>
                                <div class="col-md-12 form-group mb-3">
                                    <label for="new-password">New Password:</label>
                                    <input type="password" id="new-password" name="new-password" class="form-control">
                                    <span class="text-danger new_pass"></span>
                                </div>
                                <div class="col-md-12 form-group mb-3">
                                    <label for="confirm-password">Confirm Password:</label>
                                    <input type="password" id="confirm-password" name="confirm-password" class="form-control">
                                    <span class="text-danger conf_pass"></span>
                                </div>
                                <div class="col-md-12 form-group">
                                    <button type="submit" class="btn btn-block btn-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<?php
require_once('./common/footer.php');
?>
<script>
    $(document).ready(function() {
        setTimeout(() => {
            $("#toggle_btn").trigger('click');
        }, 200);
    });
</script>