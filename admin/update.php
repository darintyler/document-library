<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---INITIALIZE PAGE---
  include_once('../_includes/init.php');
  //---=---
  //---VALIDATION---
  if(isset($_GET['document_id']) && is_numeric($_GET['document_id'])) :
    $int_document_id = round($_GET['document_id']);
  else :
    header('Location: ' . $str_url_root . $str_document_library_directory_name . '/admin/upload.php');
  endif;
  //---/VALIDATION---
  //---INITIALIZE DATABASE---
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  $qry_select_documents = '
    SELECT
      str_title
    FROM
      docs_documents
    WHERE
      int_id = \'' . mysql_real_escape_string($int_document_id) . '\'
    ORDER BY
      str_title
  ';
  $qry_select_documents = mysql_query($qry_select_documents);
  $int_num_rows = mysql_num_rows($qry_select_documents);
  if(mysql_error($link) != ''){
    $bit_error = 1;
    $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  }
  mysql_close($link);
  //---/INITIALIZE DATABASE---
  //---GENERATE PAGE TITLE---
  $obj_document = mysql_fetch_assoc($qry_select_documents);
  $str_page_title = GenerateNavigation(
    [
      '<a href="/' . $str_document_library_directory_name . '/admin/update.php?document_id=' . $int_document_id . '">Update&nbsp;' . preg_replace('/\s+/','&nbsp;', $obj_document['str_title']) . '&nbsp;Details</a>',
      '<a href="/' . $str_document_library_directory_name . '/">Document&nbsp;Library</a>'
    ]
  );
  mysql_data_seek($qry_select_documents, 0);
  //---/GENERATE PAGE TITLE---
  //---/INITIALIZE PAGE---
  include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/header.php');
?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/admin/_document-editor.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/footer.php'); ?>
