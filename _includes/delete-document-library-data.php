<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php 
  /* This page deletes all documents from database and fileserver. Remove "exit();" line below to enable. Handle with care. */
  exit();
?>
<?php
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  mysql_query('BEGIN');
  //---DELETE DOCUMENTS---
  $qry_delete_documents = '
    DELETE FROM
      docs_documents
  ';
  $qry_delete_documents = mysql_query($qry_delete_documents);
  if(mysql_error($link) != '') :
    $bit_error = 1;
    $str_message .= '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  endif;
  //---/DELETE DOCUMENTS---
  //---DELETE DOCUMENT-CATEGORY RELS---
  $qry_delete_document_category_rels = '
    DELETE FROM
      docs_document_category_rels
  ';
  $qry_delete_document_category_rels = mysql_query($qry_delete_document_category_rels);
  if(mysql_error($link) != '') :
    $bit_error = 1;
    $str_message .= '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  endif;
  //---/DELETE DOCUMENT-CATEGORY RELS---
  if($bit_error) :
    mysql_query('ROLLBACK');
    $str_message .= '<p class="red-text bold-text">An error occured while deleting the documents.</p>';
  else :
    mysql_query('COMMIT');
    $str_message .= '<p class="green-text bold-text">The documents were deleted.</p>';
    //---=---
  endif;
  //---=---
  mysql_close($link);
?>
<?php
  if(!$bit_error) :
    DeleteDirectoryContent(dirname(__FILE__) . '/../temp');//---/DELETE TEMPORARY DIRECTORIES AND FILES/---
  endif;
  if(!$bit_error) :
    DeleteDirectoryContent(dirname(__FILE__) . '/../_protected-files/user-files');//---/DELETE USER DIRECTORIES AND FILES/---
  endif;
?>
