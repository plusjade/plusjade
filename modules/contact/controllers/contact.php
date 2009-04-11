<?php

class Contact_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{	
		$db = new Database;
		$primary = new View("contact/index");
		$primary->email_form = new View("contact/email_form");
		
		# Get contact parent
		$parent = $db->query("SELECT * FROM contacts 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'")->current();
		$primary->parent_id = $parent->id;
		
		# Grab contact items
		$contacts = $db->query("SELECT * FROM contact_items 
			JOIN contact_types ON contact_types.type_id = contact_items.type 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '{$this->site_id}' 
			AND enable = 'yes' 
			ORDER BY position");
		$primary->contacts = $contacts;
		
		# Get all contact_types (to switch display styles)
		$contact_types = $db->query('SELECT * FROM contact_types');
		$primary->contact_types = $contact_types;
		
		
		# Javascript
		$primary->add_root_js_files('facebox/public_multi.js');
		$primary->add_root_js_files('ajax_form/ajax_form.js');	
				
		$embed_js = View::factory('contact/index', NULL, 'js');		
		$primary->global_readyJS($embed_js);			
		
		return $primary;
	}
  
	function email_form()
	{
		if($_POST)
		{
			echo 'Offline but working...';
			echo '<br><a href="/get/page" rel="facebox">blah</a>';
			echo'<pre>';print_r($_POST);echo'</pre>';
			
			die();
			
			$to      = 'superjadex12@gmail.com';
			$subject = 'Customer message from: '.url::site();			
			
			$message = 'Name: '.$_POST['name']."\r\n";
			$message .= 'Email: '.$_POST['email']."\r\n";
			$message .= 'Phone: '.$_POST['phone']."\r\n";
			$message .= 'Message: '.$_POST['message']."\r\n";
			
			$headers = 'From: webmaster@example.com' . "\r\n" .
				'Reply-To: webmaster@example.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			if(! mail($to, $subject, $message, $headers) )
				echo 'There was a problem sending the email.';
			else
				echo 'Email Sent! We will be in touch shortly!';
		}
		
		die();
	}
 
	function gmap($item_id=NULL)
	{
		tool_ui::validate_id($item_id);
		$map_link = 'http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=Alhambra,+CA+91803&sll=37.0625,-95.677068&sspn=50.823846,82.089844&ie=UTF8&ll=34.076052,-118.133755&spn=0.052396,0.080166&t=h&z=14&iwloc=addr';
		
		$db = new Database;
		$item = $db->query("SELECT value FROM contact_items WHERE id ='$item_id' AND fk_site = '$this->site_id'")->current();
			
		$primary = new View('contact/gmap');
		$primary->link = $item->value;	
		echo $primary;
		die();
	}

	
	
	
}	/* -- end of application/controllers/contact.php -- */