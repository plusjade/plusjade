<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * All edit_tool controllers extend this class.
 * used to factor common functionality 
 * and provide an interface and overloading access point.
 */
 
abstract class Edit_Tool_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login to edit this tool');
	}


/*
 * parses stored text/html for tool tokens and replaces those tokens with
 * appropriate HTML output.
 * the tokens are defined here.
 */
	public function parse_tokens($body)
	{
		# NEWSLETTER TOKEN.
		str_replace('{{newsletter}}', '', $body, $count);
	
		if(0 < $count)
		{
			$pages_config = yaml::parse($this->site_name, 'pages_config');
			if(empty($pages_config['newsletter']))
				return $body;

			$parent_id = explode('-', $pages_config['newsletter']);
			$parent_id = $parent_id['1'];
		
			# get the newsletter HTML.
			$newsletter = new Newsletter_Controller;
			$body = str_replace('{{newsletter}}', $newsletter->_index($parent_id), $body);
		}
		
		# ------------------------------------------------------
		
		# SHOWROOM TOKEN. format: {showroom_cats:parent_id:parameters}
		$pattern ='/{showroom_cats:(\d+)\:(\w+)\}/';
		
		if(0 < preg_match($pattern, $body, $match))
		{
			# get the page name.
			$page_name = yaml::does_value_exist($this->site_name, 'pages_config', "showroom-{$match[1]}");
			if(!$page_name)
				return $body;			
			
			# get the showroom category html.
			$showroom = ORM::factory('showroom', $match[1]);
			if(!$showroom->loaded)
				return $body;
			
			# how should the list be displayed?
			if(!empty($match[2]) AND 'flat' == $match[2])
			{
				# showing only root categories.
				$root_cats = ORM::factory('showroom_cat')
					->where(array(
						'fk_site'		=> $this->site_id,
						'showroom_id'	=> $showroom->id,
						'local_parent'	=> $showroom->root_id,
					))
					->orderby(array('lft' => 'asc'))
					->find_all();
					
				$categories = Tree::display_flat_tree('showroom', $root_cats, $page_name);
			}
			else
				$categories = Tree::display_tree('showroom', $showroom->showroom_cats, $page_name);

			$body = preg_replace($pattern, $categories, $body, 1);

		}
		
		return $body;
	}

	
/*
 * callback function when deleting a tool.
 * useful for cleaning up assets generated with a tool.
 
 */	
	public static function _tool_deleter($parent_id, $site_id)
	{
		# delete items_meta (items)
		return TRUE;	
	}
	
} # End edit_tool_Controller