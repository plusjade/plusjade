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
			/*
			 * JADES EDIT: don't know if i'll ever need this,
			 * but for now i need to inject files contents into shell view
			*/
			#header('Content-Type: '.$this->kohana_filetype[0]);

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
	
// -----------------------------------------------------
// -----------------------------------------------------
				# JADES EXTRA STUFF #
// -----------------------------------------------------
// -----------------------------------------------------


/*
 * Add request for javascript file to be loaded in the root document
 * filters for duplicates and collisions
 * This is used for live pages only. Admin pages pre-load everything at runtime.
 */
	static public function request_js_files($files)
	{			
		if(is_array($files))
		{
			foreach($files as $file)
			{
				$pieces = explode('/', $file);
				$folder = $pieces['0'];
				$_SESSION['js_files'][$folder] = $file;
			}
		}
		else
		{
			$pieces = explode('/', $files);
			$folder = $pieces['0'];
			$_SESSION['js_files'][$folder] = $files;
		}
	}

/*
 * actually adds javascript files to the root document
 * should only be called after request_js_files() filters requests
 * strings or array
 */	
	public function admin_linkJS($href, $var = 'load_js') 
	{
		if (! isset($this->$var) )  
			$this->$var = '';
		
		$this->$var .= '<script type="text/javascript" src="' . url::site($href) . '" charset="utf-8"></script>'."\n\t";
	}
	
/*
 * actually adds javascript files to the root document
 * should only be called after request_js_files() filters requests
 * strings or array
 */	
	public function linkJS($href, $var = 'load_js') 
	{
		if (! isset($this->$var) )  
			$this->$var = '';
		
		if (is_array($href))
		{
			foreach ($href as $value)
			{
				if(!in_array($value, $this->js_array))
					$this->$var .= '<script type="text/javascript" src="' . url::site("_assets/js/$value") . '" charset="utf-8"></script>' . "\n\t";
			}
		}
		else
			if(!in_array($href, $this->js_array))
				$this->$var .= '<script type="text/javascript" src="' . url::site("_assets/js/$href") . '" charset="utf-8"></script>' . "\n\t";


		if (is_array($href)) 
			foreach ($href as $value)
				array_push($this->js_array, $value);
		else
			array_push($this->js_array, $href);
	}
	
/*
 * Add inline Javascript within the jquery document ready function
 * Adds to the initial root page
 * Takes only strings
 */
	public function global_readyJS($script, $var = 'public_javascript')
	{
		if (! isset(self::$kohana_global_data[$var]) )  
			self::$kohana_global_data[$var] = '';
			
		self::$kohana_global_data[$var] .= $script;
	}


/*
 * adds a javascript file to the document ready function at the end of the page.
 * strings only
 */
	public function readyJS($toolname, $filename, $variable=NULL, $edit=FALSE)
	{
		if (! isset(self::$kohana_global_data['javascript']) )  
			self::$kohana_global_data['javascript'] = '';

		$view = (TRUE === $edit) ? 'edit' : 'public';
		$file_path = MODPATH . "$toolname/views/".$view."_$toolname/js/$filename.js";
		
		if( file_exists($file_path) )
		{
			$contents = file_get_contents($file_path);
			
			if(! empty($variable) )
				$contents = str_replace('%VAR%', $variable , $contents);
			
			self::$kohana_global_data['javascript'] .= $contents;
		}
	}
	
/*
 *	Load CSS files in the source page
 */	
	public function linkCSS($href, $id=NULL, $var = 'load_css') 
	{
		if (!isset($this->$var))
			$this->$var = '';
	
		$id = (empty($id)) ? '' : "id=\"$id\"";
		if (is_array($href)) 
			foreach ($href as $value)
				$this->$var .= '<link type="text/css" '. $id .' rel="stylesheet" href="' . url::site($value) . '" media="screen" />'."\n\t";
		else
			$this->$var .= '<link type="text/css"  '. $id .' rel="stylesheet" href="' . url::site($href) . '" media="screen" />'."\n\t";
	}

/*
 *	Build meta tags for pages
 */	
	public function meta_tags($name, $content = NULL, $var = 'meta_tags') 
	{
		if (!isset($this->$var))  
			$this->$var = '';
			
		if (is_array($name)) 
		{
			foreach ($name as $name => $content)
				$this->$var .= '<meta name="'.$name.'" content="'.$content.'">'."\n\t";
		}
		else
			$this->$var .= '<meta name="'.$name.'" content="'.$content.'">'."\n\t";
	}

} # End View
