<?php
class AdminController extends Controller
{
    /**
     * 
     */
    public function __construct()
    {
        // parent::__construct();
        session_start();
    }
    /**
     * login page
     */
    public function login()
    {
        if (isset($_SESSION["user"])) {
            $this->jump('admin', 'Manager', 'index');
        }
        $this->display('login.php');
    }
    /**
     * check username & password
     */
    public function check()
    {
        $info = [];
        header('Content-Type: application/json; charset=utf-8');
        if ($userInfo = M('Admin')->check($_POST['user'], $_POST['pass'])) {
            $info['status'] = true;
            $_SESSION["user"] = $userInfo['user'];
        } else {
            $info['status'] = false;
        }
        echo json_encode($info);
    }
}
