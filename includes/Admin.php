<?php
namespace Simple301Redirects;

class Admin {
    public function __construct()
    {
        $this->add_menu();
        $this->load_assets();
        add_filter('Simple301Redirects/Admin/skip_no_conflict', [$this, 'skip_no_conflict']);
    }
    public function add_menu()
    {
        new Admin\Menu();
    }
    public function load_assets()
    {
        new Admin\Assets();
    }
    public function skip_no_conflict()
	{
		$whitelist = ['127.0.0.1', '::1'];
		if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
			return true;
		}
		return false;
	}
}