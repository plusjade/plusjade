<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * abstraction class for all tool models.
 */
abstract class Tool_Core extends ORM {

/*
 * deletes assets and child data associated with this tool.
 */
  abstract public function delete_tool();



  
} // End tool model abstraction library



