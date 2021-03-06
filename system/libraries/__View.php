<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Loads and displays Kohana view files. Can also handle output of some binary
 * files, such as image, Javascript, and CSS files.
 *
 * $Id: View.php 3821 2008-12-19 16:06:38Z samsoir $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http:#kohanaphp.com/license.html
 */
class View_Core {

	public $js_array = array();
 
	# The view file name and type
	protected $kohana_filename = FALSE;
	protected $kohana_filetype = FALSE;

	# View variable storage
	protected $kohana_local_data = array();
	protected static $kohana_global_data = array();

	/**
	 * Creates a new View using the given parameters.
	 *
	 * @param   string  view name
	 * @param   array   pre-load data
	 * @param   string  type of file: html, css, js, etc.
	 * @return  object
	 */
	public static function factory($name = NULL, $data = NULL, $type = NULL)
	{
		return new View($name, $data, $type);
	}

	/**
	 * Attempts to load a view and pre-load view data.
	 *
	 * @throws  Kohana_Exception  if the requested view cannot be found
	 * @param   string  view name
	 * @param   array   pre-load data
	 * @param   string  type of file: html, css, js, etc.
	 * @return  void
	 */
	public function __construct($name = NULL, $data = NULL, $type = NULL)
	{		
		if (is_string($name) AND $name !== '')
		{
			# Set the filename
			$this->set_filename($name, $type);
		}

		if (is_array($data) AND ! empty($data))
		{
			# Preload data using array_merge, to allow user extensions
			$this->kohana_local_data = array_merge($this->kohana_local_data, $data);
		}
	}
	
	/**
	 * Magic method access to test for view property
	 *
	 * @param   string   View property to test for
	 * @return  boolean
	 */
	public function __isset($key = NULL)
	{
		return $this->is_set($key);
	}

	/**
	 * Sets the view filename.
	 *
	 * @chainable
	 * @param   string  view filename
	 * @param   string  view file type
	 * @return  object
	 */
	public function set_filename($name, $type = NULL)
	{
		if ($type == NULL)
		{
			# Load the filename and set the content type
			$this->kohana_filename = Kohana::find_file('views', $name, TRUE);
			$this->kohana_filetype = EXT;
		}
		else
		{
			# Check if the filetype is allowed by the configuration
			if ( ! in_array($type, Kohana::config('view.allowed_filetypes')))
				throw new Kohana_Exception('core.invalid_filetype', $type);

			# Load the filename and set the content type
			$this->kohana_filename = Kohana::find_file('views', $name, TRUE, $type);
			$this->kohana_filetype = Kohana::config('mimes.'.$type);

			if ($this->kohana_filetype == NULL)
				$this->kohana_filetype = $type; # Use the specified type
		}

		return $this;
	}

	/**
	 * Sets a view variable.
	 *
	 * @param   string|array  name of variable or an array of variables
	 * @param   mixed         value when using a named variable
	 * @return  object
	 */
	public function set($name, $value = NULL)
	{
		if (is_array($name))
			foreach ($name as $key => $value)
				$this->__set($key, $value);
		else
			$this->__set($name, $value);

		return $this;
	}

	/**
	 * Checks for a property existence in the view locally or globally. Unlike the built in __isset(), 
	 * this method can take an array of properties to test simultaneously.
	 *
	 * @param string $key property name to test for
	 * @param array $key array of property names to test for
	 * @return boolean property test result
	 * @return array associative array of keys and boolean test result
	 */
	public function is_set( $key = FALSE )
	{
		# Setup result;
		$result = FALSE;

		# If key is an array
		if (is_array($key))
		{
			# Set the result to an array
			$result = array();
			
			# Foreach key
			foreach ($key as $property)
			{
				# Set the result to an associative array
				$result[$property] = (array_key_exists($property, $this->kohana_local_data) OR array_key_exists($property, self::$kohana_global_data)) ? TRUE : FALSE;
			}
		}
		else
		{
			# Otherwise just check one property
			$result = (array_key_exists($key, $this->kohana_local_data) OR array_key_exists($key, self::$kohana_global_data)) ? TRUE : FALSE;
		}

		# Return the result
		return $result;
	}

	/**
	 * Sets a bound variable by reference.
	 *
	 * @param   string   name of variable
	 * @param   mixed    variable to assign by reference
	 * @return  object
	 */
	public function bind($name, & $var)
	{
		$this->kohana_local_data[$name] =& $var;

		return $this;
	}

	/**
	 * Sets a view global variable.
	 *
	 * @param   string|array  name of variable or an array of variables
	 * @param   mixed         value when using a named variable
	 * @return  object
	 */
	public function set_global($name, $value = NULL)
	{
		if (is_array($name))
			foreach ($name as $key => $value)
				self::$kohana_global_data[$key] = $value;
		else
			self::$kohana_global_data[$name] = $value;

		return $this;
	}

	/**
	 * Magically sets a view variable.
	 *
	 * @param   string   variable key
	 * @param   string   variable value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->kohana_local_data[$key] = $value;
	}

	/**
	 * Magically gets a view variable.
	 *
	 * @param  string  variable key
	 * @return mixed   variable value if the key is found
	 * @return void    if the key is not found
	 */
	public function __get($key)
	{
		if (isset($this->kohana_local_data[$key]))
			return $this->kohana_local_data[$key];

		if (isset(self::$kohana_global_data[$key]))
			return self::$kohana_global_data[$key];

		if (isset($this->$key))
			return $this->$key;
	}

	/**
	 * Magically converts view object to string.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Renders a view.
	 *
	 * @param   boolean   set to TRUE to echo the output instead of returning it
	 * @param   callback  special renderer to pass the output through
	 * @return  string    if print is FALSE
	 * @return  void      if print is TRUE
	 */
	public function render($print = FALSE, $renderer = FALSE)
	{
		if (empty($this->kohana_filename))
			throw new Kohana_Exception('core.view_set_filename');

		if (is_string($this->kohana_filetype))
		{
			# Merge global and local data, local overrides global with the same name
			$data = array_merge(self::$kohana_global_data, $this->kohana_local_data);

			# Load the view in the controller for access to $this
			$output = Kohana::$instance->_kohana_load_view($this->kohana_filename, $data);

			if ($renderer !== FALSE AND is_callable($renderer, TRUE))
			{
				# Pass the output through the user defined renderer
				$output = call_user_func($renderer, $output);
			}

			if ($print === TRUE)
			{
				# Display the output
				echo $output;
				return;
			}
		}
		else
		{
			# Set the content type and size
			header('Content-Type: '.$this->kohana_filetype[0]);

			if ($print === TRUE)
			{
				if ($file = fopen($this->kohana_filename, 'rb'))
				{
					# Display the output
					fpassthru($file);
					fclose($file);
				}
				return;
			}

			# Fetch the file contents
			$output = file_get_contents($this->kohana_filename);
		}

		return $output;
	}
	
	/*
	JADES EXTRA STUFF
	*/
	
	public function linkCSS($href, $var = 'load_css') 
	{
		if (!isset($this->$var))  
			$this->$var = "<!-- @{$var} -->\n\t";
			
		if (is_array($href)) 
			foreach ($href as $value)
				$this->$var .= '<link type="text/css" rel="stylesheet" href="http://localhost.com/'.$value.'" media="screen" />'."\n\t";
		else
			$this->$var .= '<link type="text/css" rel="stylesheet" href="http://localhost.com/'.$href.'" media="screen" />'."\n\t";
	}
	
	public function linkJS($href, $var = 'load_js') 
	{
		if (!isset($this->$var))  
			$this->$var = "<!-- @{$var} -->\n\t";
		
		if (is_array($href))
		{
			foreach ($href as $value)
			{
				if(!in_array($value, $this->js_array))
					$this->$var .= '<script type="text/javascript" src="http://localhost.com/js/'.$value.'" charset="utf-8"></script>'."\n\t";
			}
		}
		else
			if(!in_array($href, $this->js_array))
				$this->$var .= '<script type="text/javascript" src="http://localhost.com/js/'.$href.'" charset="utf-8"></script>'."\n\t";


		if (is_array($href)) 
			foreach ($href as $value)
				array_push($this->js_array, $value);
		else
			array_push($this->js_array, $href);
	}

	public function readyJS($script, $var = 'readyJS') 
	{
		if (!isset($this->$var))  
			$this->$var = '';
			
		if (is_array($script)) 
			foreach ($script as $value)
				$this->$var .= $value."\n";
		else
			$this->$var .= $script."\n";
	}

	public function rootJS($script, $var = 'rootJS') 
	{
		if (!isset($this->$var))  
			$this->$var = "<!-- @{$var} -->\n\t";
			
		if (is_array($script)) 
			foreach ($script as $value)
				$this->$var .= $value."\n";
		else
			$this->$var .= $script."\n";
	}

	
	public function admin_urls($href, $name = NULL, $var = 'admin_links') 
	{
		if (!isset($this->$var))  
			$this->$var = "<!-- @{$var} -->\n\t";
			
		if (is_array($href)) 
		{
			foreach ($href as $name => $link)
				$this->$var .= '<a href="'.url::site("e/$link").'" />'.$name.'</a>'."\n\t";
		}
		else
			$this->$var .= '<a href="'.url::site("e/$href").'" />'.$name.'</a>'."\n\t";
	}
	
	public function meta_tags($name, $content = NULL, $var = 'meta_tags') 
	{
		if (!isset($this->$var))  
			$this->$var = "<!-- @{$var} -->\n\t";
			
		if (is_array($name)) 
		{
			foreach ($name as $name => $content)
				$this->$var .= '<meta name="'.$name.'" content="'.$content.'">'."\n\t";
		}
		else
			$this->$var .= '<meta name="'.$name.'" content="'.$content.'">'."\n\t";
	}

	# Load Frame View - build page
	# them name
	# controller (module) name
	public function load_frame($theme, $controller)
	{
		# $frame->linkCSS("css/global/{$this->site_name}/{$this->theme}/");
		# Module CSS when in 'e'			
		$css = array(
			'css/frame.css',
			'css/admin_global.css',
			"css/module/{$this->site_name}/{$theme}/$controller",
			
			
		);
		# load ui_tabs and jw editor
		$js_array = array(
			'jquery-1.3.1.min.js',
			'jquery-ui-light.js',
			'jw/jquery.wysiwyg.js',
			'ui_tabs/ui.tabs.js',
			'jgrowl/jquery.jgrowl_minimized.js'
		);
		#fix this static id reference
		$embed_js =array (
			"$('textarea').wysiwyg();",	
			"$('.ui-tabs-nav').tabs({ fx: { opacity: 'toggle',duration: 'fast'} });",
		);
		$this->linkCSS($css);		
		$this->linkJS($js_array);
		$this->readyJS($embed_js);
	}
} # End View