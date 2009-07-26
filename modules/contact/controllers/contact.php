<?php

class Contact_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{
		$db = new Database;		
		# Get contact parent
		$parent = $db->query("
			SELECT * FROM contacts 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();
		
		
		$contacts = $db->query("
			SELECT * FROM contact_items 
			JOIN contact_types ON contact_types.type_id = contact_items.type 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '$this->site_id' 
			AND enable = 'yes' 
			ORDER BY position
		");
		if('0' == $contacts->count())
			return $this->public_template('(no contact types)', 'contact', $tool_id);		
		
		$primary = new View("public_contact/index");
		$primary->parent_id = $parent->id;
		$primary->email_form = new View("public_contact/email_form");		
		$primary->contacts = $contacts;
		
		# contact_types (to switch display styles)
		$contact_types = $db->query('SELECT * FROM contact_types');
		$primary->contact_types = $contact_types;
		
		
		$primary->add_root_js_files('ajax_form/ajax_form.js');		

		return $this->public_template($primary, 'contact', $tool_id);
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

			if(mail($to, $subject, $message, $headers) )
				die('<div class="send_success">Email Sent! We will be in touch shortly!</div>');
				
			die('<div class="send_error">There was a problem sending the email.</div>');
		}
	}
 
	function gmap($item_id=NULL)
	{
		valid::id_key($item_id);
		$map_link = 'http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=Alhambra,+CA+91803&sll=37.0625,-95.677068&sspn=50.823846,82.089844&ie=UTF8&ll=34.076052,-118.133755&spn=0.052396,0.080166&t=h&z=14&iwloc=addr';
		
		$db = new Database;
		$item = $db->query("
			SELECT value FROM contact_items
			WHERE id ='$item_id'
			AND fk_site = '$this->site_id'
		")->current();
			
		$primary = new View('public_contact/gmap');
		$primary->link = $item->value;	
		die($primary);
	}
}