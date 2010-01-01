<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
 * control how account tool functions.
 */
class Edit_Account_Controller extends Edit_Tool_Controller {

  function __construct()
  {
    parent::__construct();  
  }

  
/*
 * manage user accounts
 */
  public function manage($id=NULL)
  {
    $view = new View("edit_account/manage");
    $view->users = ORM::factory('account_user')
              ->where('fk_site', $this->site_id)
              ->find_all();
    die($view);
  }

  
  
/*
 * get a singular view of a user.
 */  
  public function user()
  {
    $account_user = $this->get_item('account_user');
    $view = new View('edit_account/user_view');
    $view->user = $account_user;
    die($view);
  }
  
  
  
  public function delete_user()
  {
    valid::id_key($this->item_id);
    ORM::factory('account_user')
    ->where('fk_site', $this->site_id)
    ->delete($this->item_id);
    die('User deleted');
  }
  
  
  
  public function settings()
  {
    $account = $this->get_parent('account');
    if($_POST)
    {
      $account->login_title  = $_POST['login_title'];
      $account->create_title = $_POST['create_title'];
      $account->save();
      die('account settings saved');
    }
    
    $view = new View('edit_account/settings');
    $view->account = $account;
    $view->js_rel_command = "update-account-$account->id";
    die($view);
  }


}

/* -- end -- */