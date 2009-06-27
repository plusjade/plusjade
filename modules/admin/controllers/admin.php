<?php
class Admin_Controller extends Controller {

/**
 * sitewide admin functions for the current site
 * note: not for current user (these are at the root +jade site)
 */
	
	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}

/**
 * set some sitewide settings
 */	
	function index()
	{
		$db = new Database;
		
		if($_POST)
		{
			$data = array(
				'custom_domain' => $_POST['custom_domain'],
				'homepage' => $_POST['homepage'],
			);
			$db->update('sites', $data, "site_id = '$this->site_id'");
			
			# update site_config.yml if new homepage
			if($this->homepage != $_POST['homepage'])
				yaml::edit_site_value($this->site_name, 'site_config', 'homepage', $_POST['homepage']);
			
			die('Sitewide settings saved.');
		}
		
		$primary = new View('admin/index');
		$db = new Database;
		
		# TODO: optimize this later, should be a better way to get the custom domain.
		$site = $db->query("
			SELECT *
			FROM sites 
			WHERE site_id = '$this->site_id'
		")->current();
		$pages = $db->query("
			SELECT page_name
			FROM pages 
			WHERE fk_site = '$this->site_id'
		");
		$primary->pages = $pages;
		
		$primary->custom_domain = $site->custom_domain;
		$primary->js_rel_command = 'close-base';
		die($primary);
	}
	
}
/* End of file admin.php */