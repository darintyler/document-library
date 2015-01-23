<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  $qry_select_documents = '
    SELECT
      str_title,
      str_path
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
  //---=---
  $str_title = '';
  $str_path = '';
  while($obj_document = mysql_fetch_array($qry_select_documents)) :
    $str_title = $obj_document['str_title'];
    $str_path = $obj_document['str_path'];
  endwhile;
  //---=---
?>
<?php
  try{
    unlink('../_protected-files/user-files/' . $str_username . '/' . $str_path);
  }
  catch(RuntimeException $e){
    $bit_error = 1;
    $str_message = $str_message . $e->getMessage();
    echo '...error...';
  }
  //---=---
?>
<?php
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  mysql_query('BEGIN');
  //---=---
  $qry_delete_document = '
    DELETE FROM
      docs_documents
    WHERE
      int_id = \'' . mysql_real_escape_string($int_document_id) . '\'
  ';
  $qry_delete_document = mysql_query($qry_delete_document);
  if(mysql_error($link) != ''){
    $bit_error = 1;
    $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  }
  //---=---
  if(!$bit_error){
    $qry_delete_document_category_rels = '
      DELETE FROM
        docs_document_category_rels
      WHERE
        int_document_id = \'' . mysql_real_escape_string($int_document_id) . '\'
    ';
    $qry_delete_document_category_rels = mysql_query($qry_delete_document_category_rels);
    if(mysql_error($link) != ''){
      $bit_error = 1;
      $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
    }
  }
  //---=---
  if($bit_error){
    mysql_query('ROLLBACK');
  }
  else{
    mysql_query('COMMIT');
    $bit_action_completed = 1;
    //session_unset();
    //header('Location: ' . $str_url_root . 'document-deleted-confirmation.php');
    $str_message = $str_message . '<p class="green-text">The <strong>' . $str_title . '</strong> document was deleted.</p>';
  }
  mysql_close($link);
  //---=---
  //echo $str_message;
?>
