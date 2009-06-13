<?php
class Theme_Controller extends Controller {

	/**
	 *	Provides CRUD for theme and theme assets 
	 *	
	 */	 
	function __construct()
	{
		parent::__construct();
		$this->client->can_edit($this->site_id);
	}

# Manage Current Theme Files
	function index()
	{
		$primary	= new View("theme/manage_theme");	
		$directory	= new Data_Folder;	
		$db			= new Database;
		
		$custom_data_path	= DOCROOT."/data/$this->site_name/themes/$this->theme";		
		$theme_data_path	= APPPATH."views/$this->theme";
		
		$primary->theme_files = $directory->get_file_list($custom_data_path, 'root', TRUE);
		die($primary);
	}

# Update a file	
	function edit($file=NULL, $dir=NULL)
	{
		# Save a file
		if($_POST)
		{
			die(Site_Data::save_file($file, $_POST['contents']));
		}
		
		$primary = new View('theme/edit_file');
		$primary->file_name = $file;
		
		if( $primary->file_contents = Site_Data::edit_file($file) )
			die($primary);
			
		die('You cannot edit this file');	
	}

# Change Sitewide Theme
	function change()
	{
		$db = new Database;
		
		if(! empty($_POST['theme']) )
		{	
			$new_theme	= $_POST['theme'];
			$source		= APPPATH . "views/$new_theme";
			$dest		= DOCROOT . "data/$this->site_name/themes/$new_theme";				
	
			# If theme directory does not yet exist, create it.
			if(! is_dir($dest) )
			{					
				$copy_theme = new Data_Folder;	
				if(! $copy_theme->dir_copy($source, $dest) )
					die('Unable to change theme<br>Please try again later.'); # Error
			}
			$db->update('sites', array('theme' => $new_theme), "site_id = '$this->site_id'");			

			if(yaml::edit_site_value($this->site_name, 'site_config', 'theme', $new_theme ))
				die('Theme Changed');
			
			# on success: should clear the cache and reload the page.
			die('There was a problem changing the theme.');
		}

		$primary	= new View('theme/change');
		$themes		= $db->query('SELECT * FROM themes');
		$primary->themes = $themes;
		$primary->js_rel_command = 'reload-home';
		die($primary);	
	}

	
# Edit logo view
	function logo()
	{		
		$primary = new View("theme/logo");
		
		# Get all uploaded Logos
		$upload_path = DOCROOT."/data/$this->site_name/assets/images/banners";		
		$img_path	= url::site("data/$this->site_name/assets/images/banners");
		if(is_dir($upload_path))
		{
			$saved_banners = array();
			$dir = opendir("$upload_path");
			while (TRUE == ($file = readdir($dir))) 
			{
				$key = explode('.', $file);
				$key = $key['0'];
				if (strpos($file, '.gif', 1)||strpos($file, '.jpg', 1)||strpos($file, '.png', 1) ) 
					$saved_banners[$key] = $file;
			}
			$primary->saved_banners = $saved_banners;
			$primary->img_path = $img_path;
			die($primary);
		}
		die('Banner directory does not exist.');
	}
	
	function add_logo()
	{		
		if(empty($_FILES['image']['name']))
			die('Please select an image to upload.');  #error
	
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,jpeg,png]', 'upload::size[1M]');
			
		if (! $files->validate() )
			die('Unable to upload image');  #error
			
		# Temporary file name
		$filename	= upload::save('image');
		$image		= new Image($filename);			
		$ext		= $image->__get('ext');
		$image_name = basename($filename).'_ban.'.$ext;
		
		$image->save(DOCROOT . "data/$this->site_name/assets/images/banners/$image_name");
	 
		# Remove the temporary file
		unlink($filename);
		
		#success message	
		die("$image_name"); 				
			
		if(! empty($_POST['enable']) )
		{
			$db		= new Database;
			$data	= array( 'banner' => $image_name );
			$db->update('sites', $data, "site_id = $this->site_id"); 
			$_SESSION['banner'] = $image_name;
			die('Logo Saved'); # success	  		
		}			
	}
	
	# Change logo
	function change_logo()
	{
		if($_POST)
		{
			$db		= new Database;
			$data	= array('banner' => $_POST['banner']);
			$db->update('sites', $data, "site_id = $this->site_id"); 
			$_SESSION['banner'] = $_POST['banner'];
			
			if(yaml::edit_site_value($this->site_name, 'site_config', 'banner', $_POST['banner']))
				die('Banner changed.'); # success message		
		}
		die('Nothing sent.');
	}
	
	
	function delete_logo()
	{
		if(empty($_POST['delete_logo']))
			die('nothing sent');

		$img_path = DOCROOT."data/$this->site_name/assets/images/banners/{$_POST['banner']}";
		if(file_exists($img_path))
		{
			if(unlink($img_path))
				die('Image deleted!');
		}
		die('Unable to delete image'); 
	}

	


	
	
/* not using*/
# Style Theme
	function css_theme()
	{
		#  submit_theme  (change theme css values)	
		if(!empty($_POST['submit_theme']))
		{	
			$custom = array();	
			foreach($_POST as $key => $var)
			{
				if(is_array($var)){
					if(!empty($var[1]))
						$custom["$key"] = $var[2];
					else
						$custom["$key"] = $var[0];
				}
			}			
			
			# Grab the default css values file
			$css_values_template = DOCROOT."/application/views/{$this->theme}/global/css_values_template.php";		
			$file = file_get_contents($css_values_template);

			# Change the values
			$receive = array();
			$send = array();
			foreach($custom as $key => $value)
			{
				array_push($receive, "__{$key}__");
				array_push($send, "$value");
			}			

			$file = str_replace($receive, $send, $file);					

			# Put it in the proper spot
			$user_css = DOCROOT."/data/{$this->site_name}/themes/{$this->theme}/global/css_values.php";	
			file_put_contents($user_css, $file); 		
		}
		
		# get custom css vars for global.css
		# produces an array variable $background : css_div_name => value
		$user_css = DOCROOT."/data/{$this->site_name}/themes/{$this->theme}/global";	
		$root_css = DOCROOT."/application/views/{$this->theme}/global";
	
		if (file_exists("$user_css/css_values.php"))
			include_once("{$user_css}/css_values.php");
		else
			include_once("$root_css/css_values.php");	
		
		#  get uploaded global background images
		$upload_path = DOCROOT."data/{$this->site_name}/themes/{$this->theme}/global/images";			
		$saved_backgrounds = array();
		$dir = opendir("$upload_path");
        while (false !== ($file = readdir($dir))) 
		{
			if (strpos($file, '.gif',1)||strpos($file, '.jpg',1)||strpos($file, '.png',1) ) 
				array_push($saved_backgrounds, $file);
        }

		#  if values are colors load into js file in order they appear
		$x = 0;
		$values = '';
		foreach($background as $v)
		{
			if (strpos($v, '.',1)) 
				$values .= "values[$x] = ' ';\n";
			else
				$values .= "values[$x] = '{$v}';\n";
			$x++;
		}

		#load color_wheel js		
		$this->template->global_linkJS('color_wheel/farbtastic.js');		
		
		$embed_js = array(
			"var values = [];
			{$values}
			var f = $.farbtastic('#picker');
			var selected;
			$('.colorwell').each(function (i, n){ f.linkTo(this).setColor(values[i]); })
			.focus(function(){
		        if (selected){ $(selected).removeClass('colorwell-selected'); }
		        f.linkTo(this);
		        $(selected = this).css('opacity', 1).addClass('colorwell-selected');
		    });",
		);
		$this->template->global_readyJS($embed_js);
		
		# view		
		$this->template->set('title', 'Style theme');
		
		$primary = new View("admin/style_theme");
		$primary->background = $background;	
		$primary->saved_backgrounds = $saved_backgrounds;	
		$this->template->primary = $primary; 
	
	}
	
} /* End of file /modules/admin/theme.php */