<?php defined('SYSPATH') OR die('No direct access allowed.');


class Text_Controller extends Public_Tool_Controller {

  function __construct()
  {
    parent::__construct();
  }


/*
 * expects the parent text table object
 */
  public function _index($text, $sub_tool=FALSE)
  {
    # Need this to be able to append toolbar in edit mode
    if(empty($text->body) AND $this->client->logged_in())
      $text->body = '<p class="aligncenter">(sample text)</p>';

    $view = new View("public_text/basic/stock");
    $view->item = $text;
    return $this->wrap_tool($view, 'text', $text);
  }



}

/* -- end -- */