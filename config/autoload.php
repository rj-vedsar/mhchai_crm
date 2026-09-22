<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// error_reporting(E_ALL);
error_reporting(0);
//ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");
// Set session cookie parameters
@session_set_cookie_params([
    'secure' => true,      // Set to true if the site is served over HTTPS
    'httponly' => true,    // Restrict cookie access to HTTP requests only
    'samesite' => 'Strict',   // Set to 'Lax' or 'Strict' for the SameSite attribute
]);
// Regenerate session ID if not authenticated
if (!isset($_SESSION['authenticated'])) {
    session_regenerate_id(true);
    $_SESSION['authenticated'] = true;
}
require_once('config.php'); // CONNECTION AND CRUD OPERATION FUNCTION MAIN FILE
require_once('custom.php'); // CUSTOM FUNCTION LIKE EMAIL OTP AND SO ON......
require_once('session.php'); // SESSION RELATED FUNCTION
require_once('get.php'); // FOR ADMIN PANEL GET FUNCTION

class Action
{
    public $db, $custom, $session, $get_admin_function;

    public function __construct()
    {
        $this->db = new Database;
        $this->custom = new custom;
        $this->session = new session;
        $this->get_admin_function = new get_admin_function;
    }
}
$action = new Action;
// THIS SINGLE OBJECT VARIABLE USED FOR ALL SITE AND ADMIN QUERY
?>