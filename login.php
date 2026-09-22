<?php
require_once('./common/head.php');
if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])){ 
    header("location:./index.php");
}
?>
<!-- Main Wrapper -->
<div class="main-wrapper">
    <div class="account-content">
        <div class="row login-wrapper m-0">
            <div class="col-lg-6 p-0">
                <div class="login-content">
                    <form id="login_fd">
                        <div class="login-userset">
                            <div class="login-logo logo-normal">
                                <img src="assets/img/mh-logo.jpeg" alt="img">
                            </div>
                            <a href="index-2.html" class="login-logo logo-white">
                                <img src="assets/img/mh-logo.jpeg" alt="Img">
                            </a>
                            <div class="login-userheading">
                                <h3>Sign In</h3>
                                <h4>Access the panel using your email and passcode.</h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="user_email" class="form-control border-end-0">
                                    <span class="input-group-text border-start-0">
                                        <i class="ti ti-mail"></i>
                                    </span>
                                </div>
                                <span class="error-span text-danger user_email"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="pass-group">
                                    <input type="password" name="user_pass" class="pass-input form-control">
                                    <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                                </div>
                                <span class="error-span text-danger user_pass"></span>
                            </div>
                            <div class="form-login">
                                <button type="submit" class="btn btn-login flex" id="sub_btn">Sign In <div
                                        class="loader-f d-none" id="loader-f"></div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="login-img">
                    <img src="assets/img/authentication/authentication-01.svg" alt="img">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->
<?php
require_once('./common/footer.php');
?>