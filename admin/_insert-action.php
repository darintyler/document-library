<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //--=--
  $str_destination_file_name = UploadFile($_FILES['fil_document_to_upload'],'../_protected-files/user-files/' . $str_username . '/');
  //--=--
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  mysql_query('BEGIN');
  if(!$bit_error) :
    //---INSERT DOCUMENT INTO DATABASE---
    $qry_insert_document = '
      INSERT INTO docs_documents(
        str_username,
        str_title,
        str_description,
        str_keywords,
        str_path,
        dat_uploaded,
        dat_last_updated
      )
      VALUES(
        \'' . mysql_real_escape_string($str_username) . '\',
        \'' . mysql_real_escape_string($_POST['str_title']) . '\',
        \'' . mysql_real_escape_string($_POST['str_description']) . '\',
        \'' . mysql_real_escape_string($_POST['str_keywords']) . '\',
        \'' . mysql_real_escape_string($str_destination_file_name) . '\',
        \'' . mysql_real_escape_string(GetValidDate($_POST['dat_current'])) . '\',
        \'' . mysql_real_escape_string(GetValidDate($_POST['dat_current'])) . '\'
      )
    ';
    $qry_insert_document = mysql_query($qry_insert_document);
    if(mysql_error($link) != ''){
      $bit_error = 1;
      $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
    }
    $int_document_id = mysql_insert_id();
    //mysql_close($link);
    //---/INSERT DOCUMENT INTO DATABASE---
    //---INSERT DOCUMENT-CATEGORY RELATIONSHIPS INTO DATABASE---
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
          $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
        }
        $int_document_category_rel_id = mysql_insert_id();
        //echo mysql_error($link);
        //mysql_close($link);
        //---=---
        /*
        if($bit_error || $int_document_category_rel_id == 0) :
          $bit_error = 1;
          $str_message = $str_message . '
            <div class="message">
              <p class="red-text bold-text">
                An error occured while designating your document categories.
              </p>
            </div>
          ';
        endif;
        */
        //---=---
      endif;
    endforeach;
    //---/INSERT DOCUMENT-CATEGORY RELATIONSHIPS INTO DATABASE---
  endif;
  //---=---
  if($bit_error) :
    mysql_query('ROLLBACK');
    //$str_message = $str_message . '<p class="red-text bold-text">An error occured while uploading your document.</p>';
  else :
    mysql_query('COMMIT');
    $bit_action_completed = 1;
    $str_message = $str_message . '<p class="green-text bold-text">Your document was uploaded.</p>';
  endif;
  //---=---
  mysql_close($link);
  //---=---
  $str_message = $str_message . '
    <ul>
      <li><a href="/' . $str_document_library_directory_name . '/index.php" class="focus-target">Go to Document Library homepage</a></li>
    </ul>
  ';
  //---=---
  //echo $str_message;
?>
