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
		$site = ORM::factory('site', $this->site_id);
		if(!$site->loaded)
			die('site not found');
			
		if($_POST)
		{
			$site->custom_domain = $_POST['custom_domain'];
			$site->homepage		 = $_POST['homepage'];
			$site->save();
			
			# update site_config.yml if new homepage
			if($this->homepage != $_POST['homepage'])
				yaml::edit_site_value($this->site_name, 'site_config', 'homepage', $_POST['homepage']);
			
			die('Sitewide settings saved.');
		}
		
		$pages = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->orderby('page_name')
			->find_all();
		
		$primary = new View('admin/index');
		$primary->pages = $pages;
		$primary->custom_domain = $site->custom_domain;
		$primary->js_rel_command = 'close-base';
		die($primary);
	}
	
}
/* End of file admin.php */