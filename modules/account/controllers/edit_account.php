<?php

class Edit_Account_Controller extends Edit_Tool_Controller {

/*
 * control how account tool functions.
 */
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * manage user accounts
 */
	public function manage($id=NULL)
	{
		$primary = new View("edit_account/manage");
		$primary->users = ORM::factory('account_user')
							->where('fk_site', $this->site_id)
							->find_all();
		die($primary);
	}

/*
 * get a singular view of a user.
 */	
	function user($user_id=NULL)
	{
		valid::id_key($user_id);
		$account_user = ORM::factory('account_user', $user_id);

		if(FALSE == $account_user->loaded)
			die('invalid user');
		
		$primary = new View('edit_account/user_view');
		$primary->user = $account_user;
		die($primary);
	}
	
	function delete_user($user_id=NULL)
	{
		valid::id_key($user_id);
		ORM::factory('account_user')
		->where('fk_site', $this->site_id)
		->delete($user_id);
		die('User deleted');
	}
	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}
	
	static function _tool_deleter($tool_id, $site_id)
	{
		return true;
	}
}

/* -- end -- */