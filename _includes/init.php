<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  $bit_debug = 0; //---CUSTOMIZABLE/---
  $bit_development = 0; //---CUSTOMIZABLE/---
  $bit_error = 0;
  $bit_action_completed = 0; //---SET TO TRUE WHEN POST ACTIONS COMPLETE SUCCESSFULLY/---
  //---=---
  $int_qry_insert_id = 0;
  //---=---
  $str_message = '';
  //---=---
  $str_db_address = 'localhost'; //---CUSTOMIZABLE/---
  $str_db_username = '[your-db-username]'; //---CUSTOMIZABLE/---
  $str_db_password = '[your-db-password]'; //---CUSTOMIZABLE/---
  $str_db_name = '[your-db-name]'; //---CUSTOMIZABLE/---
  $str_url_domain = $_SERVER['HTTP_HOST'];
  //---=---
  $str_document_library_directory_name = 'document-library'; //---SET TO DIRECTORY WHERE DOCUMENT LIBRARY APPLICATION IS INSTALLED (EXCLUDE BEGINNING AND TRAILING SLASHES, FOR EXAMPLE "my/installation/directory")/--- //---CUSTOMIZABLE/---
  //---=---
  $str_url_root = 'http://' . $str_url_domain . '/';
  if(!isset($str_page_title) || $str_page_title == '') :
    $str_page_title = '<a href="/">Document Library</a>';
  endif;
  //---=---
  if(isset($_SERVER['REMOTE_ADDR'])) : //---THIS IS A NORMAL USER, NOT AN INTERNAL SERVER PROCESS/---
    if(isset($_COOKIE['str_username']) && trim($_COOKIE['str_username']) != '') :
      $str_username = $_COOKIE['str_username'];
    else :
      $str_username = $_SERVER['REMOTE_ADDR'] . uniqid ('_',true);
      setcookie('str_username','','0'); //---EXPIRE ANY EXISTING COOKIE/---
      setcookie('str_username',$str_username,'2147483647'); //---SET COOKIE EXPIRE ON 01/19/2038 AT 3:14am (UTC) (AS LATE AS POSSIBLE)/---
    endif;
    //---=---
  endif;
  //---=---
  include_once(dirname(__FILE__) . '/functions.php');
  //---=---
  if(isset($_SERVER['REMOTE_ADDR'])) : //---THIS IS A NORMAL USER, NOT AN INTERNAL SERVER PROCESS/---
    $break = explode('/', $_SERVER['SCRIPT_NAME']);
    $str_file_name = $break[count($break) - 1];
    if($str_file_name != 'cron.php') : //---IF NOT SPECIAL CRON JOB PAGE AND DEFAULT DOCUMENTS ARE NOT ALREADY PRESENT FOR THIS USER, THEN INSERT DEFAULT DOCUMENTS/---
      $int_counter = 0;
      while(($handle = @opendir(dirname(__FILE__) . '/../_protected-files/user-files/' . $str_username)) === false) :
        include_once(dirname(__FILE__) . '/insert-default-document-library-data.php');
        $int_counter++;
        if($int_counter > 10) :
          $bit_error = 1;
          $str_message .= '<p class="red-text bold-text">An error occured while initializing the default data.</p>';
          break;
        endif;
      endwhile;
      closedir($handle);
    endif;
  endif;
  //---=---
?>
