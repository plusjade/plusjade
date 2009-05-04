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
			AND fk_site = '$this->site_id'
		")->current();
		$primary->parent_id = $parent->id;
		
		$contacts = $db->query("SELECT * FROM contact_items 
			JOIN contact_types ON contact_types.type_id = contact_items.type 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '$this->site_id' 
			AND enable = 'yes' 
			ORDER BY position
		");
		$primary->contacts = $contacts;
		
		# contact_types (to switch display styles)
		$contact_types = $db->query('SELECT * FROM contact_types');
		$primary->contact_types = $contact_types;
		
		# Javascript
		$primary->add_root_js_files('ajax_form/ajax_form.js');	
		$primary->readyJS('contact','index');			
		
		return $primary;
	}
  
	function email_form()
	{
		if($_POST)
		{
			$to      = $_POST['sendto'];
			$subject = 'Customer message from: '.url::site();			
			
			$message = 'Name: '.$_POST['name']."\r\n";
			$message .= 'Email: '.$_POST['email']."\r\n";
			$message .= 'Phone: '.$_POST['phone']."\r\n";
			$message .= 'Message: '.$_POST['message']."\r\n";
			
			$headers = 'From: '.$_POST['email'] . "\r\n" .
				'Reply-To: '.$_POST['email'] . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			if(! mail($to, $subject, $message, $headers) )
				echo '<div class="send_error">There was a problem sending the email.</div>';
			else
				echo '<div class="send_success">Email Sent! We will be in touch shortly!</div>';
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