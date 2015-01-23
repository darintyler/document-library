<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  mysql_query('BEGIN');
  echo $bit_debug ? 'log: mysql_query(\'BEGIN\')' : '';
  //---=---
  if(isset($_FILES['fil_document_to_upload']['name']) && $_FILES['fil_document_to_upload']['name'] != '') :
    //---SELECT OLD DOCUMENT PATH FROM DATABASE---
    $qry_select_document_path = '
      SELECT
        str_path
      FROM
        docs_documents
      WHERE
        int_id = \'' . mysql_real_escape_string($_POST['int_document_id']) . '\'
    ';
    $qry_select_document_path = mysql_query($qry_select_document_path);
    if(mysql_error($link) != ''){
      $bit_error = 1;
      $str_message .= '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
      echo $bit_debug ? $str_message : '';
    }
    //mysql_close($link);
    //---/SELECT OLD DOCUMENT PATH FROM DATABASE---
    while($obj_document = mysql_fetch_array($qry_select_document_path)) :
      @unlink('../_protected-files/user-files' . $str_username . '/' . $obj_document['str_path']); //---DELETE OLD DOCUMENT/---
      $str_destination_file_name = UploadFile($_FILES['fil_document_to_upload'],'../_protected-files/user-files/' . $str_username . '/'); //---UPLOAD NEW DOCUMENT/---
      echo $bit_debug ? 'log: uploaded new file' : '';
      $str_query_expression = '
        str_path = \'' . mysql_real_escape_string($str_destination_file_name) . '\',
      ';
    endwhile;
  else :
    $str_query_expression = '';
  endif;
  if(!$bit_error) :
    //---INSERT DOCUMENT INTO DATABASE---
    $qry_insert_document = '
      UPDATE
        docs_documents
      SET
        str_title = \'' . mysql_real_escape_string($_POST['str_title']) . '\',
        str_description = \'' . mysql_real_escape_string($_POST['str_description']) . '\',
        str_keywords = \'' . mysql_real_escape_string($_POST['str_keywords']) . '\',
        ' . $str_query_expression . '
        dat_last_updated = \'' . mysql_real_escape_string(GetValidDate($_POST['dat_current'])) . '\'
      WHERE
        int_id = \'' . mysql_real_escape_string($_POST['int_document_id']) . '\'
    ';
    $qry_insert_document = mysql_query($qry_insert_document);
    if(mysql_error($link) != ''){
      $bit_error = 1;
      $str_message .= '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
      echo $bit_debug ? $str_message : '';
    }
    //mysql_close($link);
    echo $bit_debug ? 'log: /INSERT DOCUMENT INTO DATABASE' : '';
    //---/INSERT DOCUMENT INTO DATABASE---
    //---DELETE EXISTING DOCUMENT-CATEGORY RELATIONSHIPS FROM THE DATABASE
    if(!$bit_error) :
      $qry_delete_document_category_rels = '
        DELETE FROM
          docs_document_category_rels
        WHERE
          int_document_id = \'' . mysql_real_escape_string($_POST['int_document_id']) . '\'
      ';
      $qry_delete_document_category_rels = mysql_query($qry_delete_document_category_rels);
      if(mysql_error($link) != ''){
        $bit_error = 1;
        $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
        echo $bit_debug ? $str_message : '';
      }
    endif;
    echo $bit_debug ? 'log: /DELETE EXISTING DOCUMENT-CATEGORY RELATIONSHIPS FROM THE DATABASE' : '';
    //---/DELETE EXISTING DOCUMENT-CATEGORY RELATIONSHIPS FROM THE DATABASE
    //---INSERT DOCUMENT-CATEGORY RELATIONSHIPS INTO DATABASE---
    if(!$bit_error) :
      foreach($_POST AS $key=>$value) :
        $pattern_1 = '/^str_category_/';
        $subject_1 = $key;
        $bit_preg_match_result_1 = preg_match($pattern_1,$subject_1,$matches_1);
        //print_r($matches_1);
        $pattern_2 = '/(?<=str_category_)[0-9]+/';
        $subject_2 = $key;
        $bit_preg_match_result_2 = preg_match($pattern_2,$subject_2,$matches_2);
        if($bit_preg_match_result_1 && $bit_preg_match_result_2) :
          //---=---
          $qry_insert_document_category_rel = '
            INSERT INTO docs_document_category_rels(
              int_document_id,
              int_category_id
            )
            VALUES(
              \'' . mysql_real_escape_string($int_document_id) . '\',
              \'' . mysql_real_escape_string($matches_2[0]) . '\'
            )
          ';
          $qry_insert_document_category_rel = mysql_query($qry_insert_document_category_rel);
          if(mysql_error($link) != ''){
            $bit_error = 1;
            $str_message .= '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
            echo $bit_debug ? $str_message : '';
          }
          $int_document_category_rel_id = mysql_insert_id();
          //echo mysql_error($link);
          //mysql_close($link);
          //---=---
        endif;
      endforeach;
    endif;
    echo $bit_debug ? 'log: /INSERT DOCUMENT-CATEGORY RELATIONSHIPS INTO DATABASE' : '';
    //---/INSERT DOCUMENT-CATEGORY RELATIONSHIPS INTO DATABASE---
  /*
  */
  endif;
  //---=---
  if($bit_error) :
    mysql_query('ROLLBACK');
    $str_message .= '<p class="red-text bold-text">An error occured while updating your document.</p>';
    echo $bit_debug ? $str_message : '';
  else :
    mysql_query('COMMIT');
    $bit_action_completed = 1;
    $str_message .= '<p class="green-text bold-text">Your document was updated.</p>';
    echo $bit_debug ? $str_message : '';
  endif;
  //---=----
  /*
  */
  $str_message .= '
    <ul>
      <li><a href="/' . $str_document_library_directory_name . '/index.php" class="focus-target">Go to Document Library homepage</a></li>
    </ul>
  ';
  //---=---
  mysql_close($link);
  //---=---
?>