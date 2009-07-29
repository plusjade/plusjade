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
	
	public function delete_user($user_id=NULL)
	{
		valid::id_key($user_id);
		ORM::factory('account_user')
		->where('fk_site', $this->site_id)
		->delete($user_id);
		die('User deleted');
	}
	
	public function settings($tool_id)
	{
		$account = ORM::factory('account')
			->where('fk_site', $this->site_id)
			->find($tool_id);
		if(!$account->loaded)
			die('invalid account id');
			
		if($_POST)
		{
			$account->login_title = $_POST['login_title'];
			$account->create_title = $_POST['create_title'];
			$account->save();
			die('account settings saved');
		}
		
		$primary = new View('edit_account/settings');
		$primary->account = $account;
		$primary->js_rel_command = "update-account-$account->id";
		die($primary);
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