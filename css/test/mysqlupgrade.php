<?php
/**
 *
 * %%%copyright%%%
 *
 * MySQLUpgrade - Daabase Upgrade Class for MySQL
 * Copyright (C) 2007-2008 LumenSoft Int. All rights reserved.
 *
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@lumensoft.nl if any conditions of this licencing isn't
 * clear to you.
 */

define("DB_DEADLOCK", 1213);

define("DB_LOGPATH", './');
define('DB_HOST','');
define('DB_USERNAME','root');
define('DB_PWD','genius12');
define('DB_DATABASE','plusjade');

class MySQLUpgrade {
  private Static $DB_Link;
  
  // new SQLi extenstions

  /*
  * $DB_Struction needs to be a array with the tablename as key and a second array with fields/index's
  * like: Array(name, $definition)
  *   => Array('ID', 'int(11) NOT NULL auto_increment');
  *
  */
  public static function DatabaseUpgrade($Struction)
  {
      $error = '';

      if (!is_string($Struction) or  substr($Struction,0,7) !=='LumDBu:') {return;}
      $Struction = unserialize (substr($Struction,7));
      self::dblogging("[SQLupdate:] Start \n");
      foreach ($Struction as $tablename => $fields) {
          $update = false;
          If ($tblFields = self::TableCreateData($tablename)) {
              $sql = "";
              $oldkey = '';
              foreach ($fields['fields'] as $key => $info) {// echo $key,'-';
                if (!array_key_exists($key,$tblFields['fields'])) {
                    $update = true;
                    $sql .= ', ADD `' . $key . "` " . $info;
                    $sql .= (($oldkey == '')?' FIRST':' AFTER ' . $oldkey);//. "\n";
                } elseif ($info != $tblFields['fields'][$key]) {
                    echo $info,"\n", $tblFields['fields'][$key],"\n";
                    $update = true;
                    $sql .= ', MODIFY `' . $key . "` " . $info;
//                      $sql .= (($oldkey == '')?' FIRST':' AFTER ' . $oldkey);//. "\n";
                }
                $oldkey = $key;
              }
              $sql = "ALTER TABLE `$tablename` " . substr($sql, 2);
          } else {
              $update = true;
              $sql = '';
              foreach ($fields['fields'] as $key => $info) {
                 $sql .= ", `" . $key . "` " . $info;
              }
              If ((isset($fields['keys'])) and (count($fields['keys']) > 0))
                  foreach ($fields['keys'] as $info) $sql .= ', ' . $info;
              $sql = "CREATE TABLE `$tablename` (" . substr($sql, 2) . ")";
//                if ($fields['auto_increment']) $sql .= ' AUTO_INCREMENT='.$fields['auto_increment'];
              if ($fields['engine']) $sql .= ' ENGINE='.$fields['engine'];
          }
          If ($update) {
             self::dblogging("[SQLupdate:] ".$sql."\n");
             $result = self::query($sql);
          }
      }
      self::dblogging("[SQLupdate:] Finnish \n");
      return;
  }

  function CreateStructorDump() {
    $tables = self::TableListExt();
    foreach ($tables as $table ) {
      $tbls[$table->Name] = self::TableCreateData($table->Name);
      $tbls[$table->Name]['engine'] = $table->Engine;
      If ($table->Auto_increment)
        $tbls[$table->Name]['auto_increment'] = $table->Auto_increment;  //   break;

    } //  print_r($tbls);
		header('Content-type: text/plain');
  	header('Content-Disposition: attachment; filename="install_db.txt"');
    echo  'LumDBu:';
    echo  Serialize($tbls);
  }

  private static function init () {
    $link = @ new mysqli(DB_HOST, DB_USERNAME, DB_PWD, DB_DATABASE);
    $error = @ mysqli_connect_error();
    if ($error) {
      self::dblogging ("Could not connect: " . $error."\n" );
      return false;
    }
    self::$DB_Link = $link;
    return ($link);
  }

  private static function query($query)
  {
    if (!isset(self::$DB_Link) and !self::init()) {  exit; }

    $res = @ self::$DB_Link->query($query);
    if (!$res) {
      self::dblogging("[Error:] ".$query."\n");
      self::dblogging(mysqli_error(self::$DB_Link)."\n");
    }
    return $res;
  }

  private static function query_one_row ($query)
  {
    if ($result = self::query($query) and $row = $result->fetch_array()) {
      return $row;
    }
  }

  private static function affected_rows()
  {
    if (!isset(self::$DB_Link)) { return; }
    return self::$DB_Link->affected_rows;
  }

  /*
  *
  * returns all the table status information in a araary of objects.
  *
  * @access private
  * @return Arrar with all table status informations
  **/
  private static function TableListExt ()
  {
      $tables = Array ();

      $result = self::Query("SHOW TABLE STATUS") ;//. ((!empty($prefix))?" LIKE '$prefix%'":""));

      if (!$result) {
          return false;
      } while ($row = $result->fetch_object()) {
          $tables[] = $row;
      }
      Return $tables;
  }

   /*
  *
  * returns Returns an array of the table structure.
  *
  * @access private
  * @return Arrar with all table status informations
  **/
 private static function TableCreateData( $tablename )
  {
    $result = self::query_one_row('SHOW CREATE TABLE ' ."`$tablename`");
    $keys = array ();
    if ($result) {
      $tables = $result[1];
      unset($result);
      // Convert end of line chars to one that we want (note that MySQL doesn't return query it will accept in all cases)
      if (strpos($tables, "(\r\n ")) {
          $tables = str_replace("\r\n", "\n", $tables);
      } elseif (strpos($tables, "(\r ")) {
          $tables = str_replace("\r", "\n", $tables);
      }
      // Split the query into lines, so we can easily handle it. We know lines are separated by $crlf (done few lines above).
      $sql_lines = explode("\n", $tables);
      $sql_count = count($sql_lines);
      // lets find first line with constraints
      for ($i = 1; $i < $sql_count; $i++) {
         $sql_line = trim($sql_lines[$i]);
         if (substr($sql_line,-1) ==',') $sql_line = substr($sql_line,0,-1);
         if (preg_match('/^[\s]*(CONSTRAINT|FOREIGN|PRIMARY)*[\s]+(KEY)+/', $sql_lines[$i])) {
            $keys['keys'][] = $sql_line;
         } else if (preg_match('/(ENGINE)+/', $sql_line)) {
         } else {
           $x = strpos( $sql_line,' ');
           $key = substr($sql_line,0,$x);
           if (strpos("`'\"", substr($key,0,1)) !== false) {
             $key = substr($key,1,-1);
           }
           $keys['fields'][$key] = substr($sql_line,$x);
         }
      }
    }         // print_r($keys);
    Return $keys;
  }

  private static function dblogging($debug)
  {
      global $_SHOP;
      $handle=fopen(DB_LOGPATH."database.log","a");
      fwrite($handle,$debug);
      fclose($handle);
  }
}
?>