<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---INITIALIZE PAGE---
  include_once('_includes/init.php');
  //---VALIDATION---
  if(isset($_GET['document_id']) && is_numeric($_GET['document_id'])) :
    $int_document_id = round($_GET['document_id']);
  else :
    header('Location: ' . $str_url_root . $str_document_library_directory_name . '/index.php');
  endif;
  //---/VALIDATION---
  //---INITIALIZE DATABASE---
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  $qry_select_documents = '
    SELECT
      str_path,
      str_title
    FROM
      docs_documents
    WHERE
      str_username = \'' . mysql_real_escape_string($str_username) . '\'
      AND int_id = \'' . mysql_real_escape_string($int_document_id) . '\'
    ORDER BY
      str_title
  ';
  $qry_select_documents = mysql_query($qry_select_documents);
  //---=---
  if(mysql_error($link) != ''){
    $bit_error = 1;
    $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  }
  //---=---
  $int_num_rows = mysql_num_rows($qry_select_documents);
  if($int_num_rows < 1):
    $bit_error = 1;
    $str_message .= '
      <p class="red-text bold-text">No document was found with ID ' . $int_document_id . '.</p>
      <ul>
        <li><a href="/' . $str_document_library_directory_name . '/index.php">Go to Document Library homepage</a></li>
      </ul>
    ';
  endif;
  //---/INITIALIZE DATABASE---
  //---GENERATE PAGE TITLE---
  $obj_document = mysql_fetch_assoc($qry_select_documents);
  $str_page_title = GenerateNavigation(
    [
      '<a href="/' . $str_document_library_directory_name . '/download.php?document_id=' . $obj_document['int_id'] . '">Download&nbsp;' . preg_replace('/\s+/','&nbsp;',$obj_document['str_title']) . '</a>',
      '<a href="/' . $str_document_library_directory_name . '/">Document&nbsp;Library</a>'
    ]
  );
  mysql_data_seek($qry_select_documents, 0);
  //---/GENERATE PAGE TITLE---
  //---/INITIALIZE PAGE---
?>
<?php
  if(!$bit_error) :
    while($obj_document = mysql_fetch_array($qry_select_documents)) :
      //---CREATE DIRECTORY AND FILE VARIABLES---
      /*
        $str_file_name - the name of the file
        $str_browsable_temp_directory_name - the name of the temporary dir on the server where browsable files will be placed
        $str_unbrowsable_file_directory_name - the name of the dir that is not accessible by a browser which contains the file named $str_file_name
        $str_download_directory_url - the URL equivalent of the $str_browsable_temp_directory_name
      */
      $str_file_name = $obj_document['str_path'];
      $str_new_file_name = 'document.' . pathinfo($str_file_name, PATHINFO_EXTENSION);
      $str_browsable_temp_directory_name = 'temp';
      $str_unbrowsable_file_directory_name = '_protected-files/user-files/' . $str_username;
      $str_download_directory_url = $str_url_root . $str_document_library_directory_name . '/' . $str_browsable_temp_directory_name;
      //---/CREATE DIRECTORY AND FILE VARIABLES---
      DeleteDirectoryContent(dirname(__FILE__) . '/'. $str_browsable_temp_directory_name); //---REMOVE ANY EXISTING CONTENT FROM TEMP DIRECTORY/---
      //---GENERATE A RANDOM TEMPORARY DIRECTORY NAME---
      $letters = 'abcdefghijklmnopqrstuvwxyz';
      srand((double) microtime() * 1000000);
      $str_random_directory_name = '';
      for($i = 1; $i <= rand(4,12); $i++) :
        $q = rand(1,24);
        $str_random_directory_name = $str_random_directory_name . $letters[$q];
      endfor;
      //---/GENERATE A RANDOM TEMPORARY DIRECTORY NAME---
      //---COPY FILE TO TEMPORARY DIRECTORY, AND THEN REDIRECT TO TEMPARY DIRECTORY---
      if(!mkdir($str_browsable_temp_directory_name . '/' . $str_random_directory_name)) :
        $bit_error = 1;
        $str_message .= '<p class="bold-text red-text">An error occured while creating a temporary directory.</p>';
      endif;
      //---=---
      if(
        !$bit_error
        && !copy(
          $str_unbrowsable_file_directory_name . '/' . $str_file_name,
          $str_browsable_temp_directory_name . '/' . $str_random_directory_name . '/' . $str_new_file_name
        )
      ) :
        $bit_error = 1;
        $str_message = $str_message . '<p class="bold-text red-text">An error occured while importing your document.</p>';
      endif;
      //---=---
      if(!$bit_error) :
        header('Location: ' . $str_download_directory_url . '/' . $str_random_directory_name . '/' . $str_new_file_name);
      endif;
      //---/COPY FILE TO TEMPORARY DIRECTORY, AND THEN REDIRECT TO TEMPARY DIRECTORY---
    endwhile;
  endif;
  //---=---
?>
<?php
  if($bit_error) :
    include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/header.php');
?>
  <div class="body-container">
    <?php
      echo $str_message;
    ?>
  </div>
<?php
  include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/footer.php');
  endif;
  //---=---
?>
<?php 
  mysql_close($link);
?>
