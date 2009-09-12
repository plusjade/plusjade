<?php defined('SYSPATH') or die('No direct script access.');

/**
 * sitewide admin functions for the current site
 * note: not for current user (these are at the root +jade site)
 */
 
class Admin_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}

/**
 * set some sitewide settings
 */	
	public function index()
	{
		$site = ORM::factory('site', $this->site_id);
		if(!$site->loaded)
			die('site not found');
			
		if($_POST)
		{
			$homepage = explode(':', $_POST['homepage']);
			
			$site->custom_domain = $_POST['custom_domain'];
			$site->homepage		 = $homepage[0];
			$site->save();
			
			# update site_config.yml if new homepage
			# and force page to be enabled.
			if($this->homepage != $homepage[0])
			{
				yaml::edit_site_value($this->site_name, 'site_config', 'homepage', $_POST['homepage']);
				$page = ORM::factory('page', $homepage[1]);
				$page->enable = 'yes';
				$page->save();
			}
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