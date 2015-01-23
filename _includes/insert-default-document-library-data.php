<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  mysql_query('BEGIN');
  //---INSERT DEFAULT DOCUMENTS---
  $qry_insert_documents = '
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
      \'' . mysql_real_escape_string('Demonstration Document A') . '\',
      \'' . mysql_real_escape_string('This is demonstration document A.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-a') . '\',
      \'' . mysql_real_escape_string('demonstration-document-a.doc') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document B') . '\',
      \'' . mysql_real_escape_string('This is demonstration document B.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-b') . '\',
      \'' . mysql_real_escape_string('demonstration-document-b.docx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document C') . '\',
      \'' . mysql_real_escape_string('This is demonstration document C.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-c') . '\',
      \'' . mysql_real_escape_string('demonstration-document-c.xlsx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document D') . '\',
      \'' . mysql_real_escape_string('This is demonstration document D.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-d') . '\',
      \'' . mysql_real_escape_string('demonstration-document-d.xls') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document E') . '\',
      \'' . mysql_real_escape_string('This is demonstration document E.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-e') . '\',
      \'' . mysql_real_escape_string('demonstration-document-e.pdf') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document F') . '\',
      \'' . mysql_real_escape_string('This is demonstration document F.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-f') . '\',
      \'' . mysql_real_escape_string('demonstration-document-f.doc') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document G') . '\',
      \'' . mysql_real_escape_string('This is demonstration document G.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-g') . '\',
      \'' . mysql_real_escape_string('demonstration-document-g.docx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document H') . '\',
      \'' . mysql_real_escape_string('This is demonstration document H.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-h') . '\',
      \'' . mysql_real_escape_string('demonstration-document-h.xlsx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document I') . '\',
      \'' . mysql_real_escape_string('This is demonstration document I.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-i') . '\',
      \'' . mysql_real_escape_string('demonstration-document-i.xls') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document J') . '\',
      \'' . mysql_real_escape_string('This is demonstration document J.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-j') . '\',
      \'' . mysql_real_escape_string('demonstration-document-j.pdf') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document K') . '\',
      \'' . mysql_real_escape_string('This is demonstration document K.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-k') . '\',
      \'' . mysql_real_escape_string('demonstration-document-k.doc') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document L') . '\',
      \'' . mysql_real_escape_string('This is demonstration document L.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-l') . '\',
      \'' . mysql_real_escape_string('demonstration-document-l.docx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document M') . '\',
      \'' . mysql_real_escape_string('This is demonstration document M.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-m') . '\',
      \'' . mysql_real_escape_string('demonstration-document-m.xlsx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document N') . '\',
      \'' . mysql_real_escape_string('This is demonstration document N.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-n') . '\',
      \'' . mysql_real_escape_string('demonstration-document-n.xls') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document O') . '\',
      \'' . mysql_real_escape_string('This is demonstration document O.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-o') . '\',
      \'' . mysql_real_escape_string('demonstration-document-o.pdf') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document P') . '\',
      \'' . mysql_real_escape_string('This is demonstration document P.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-p') . '\',
      \'' . mysql_real_escape_string('demonstration-document-p.doc') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document Q') . '\',
      \'' . mysql_real_escape_string('This is demonstration document Q.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-q') . '\',
      \'' . mysql_real_escape_string('demonstration-document-q.docx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document R') . '\',
      \'' . mysql_real_escape_string('This is demonstration document R.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-r') . '\',
      \'' . mysql_real_escape_string('demonstration-document-r.xlsx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document S') . '\',
      \'' . mysql_real_escape_string('This is demonstration document S.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-s') . '\',
      \'' . mysql_real_escape_string('demonstration-document-s.xls') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document T') . '\',
      \'' . mysql_real_escape_string('This is demonstration document T.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-t') . '\',
      \'' . mysql_real_escape_string('demonstration-document-q.pdf') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document U') . '\',
      \'' . mysql_real_escape_string('This is demonstration document U.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-u') . '\',
      \'' . mysql_real_escape_string('demonstration-document-q.doc') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document V') . '\',
      \'' . mysql_real_escape_string('This is demonstration document v.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-v') . '\',
      \'' . mysql_real_escape_string('demonstration-document-v.docx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document W') . '\',
      \'' . mysql_real_escape_string('This is demonstration document W.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-w') . '\',
      \'' . mysql_real_escape_string('demonstration-document-w.xlsx') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document X') . '\',
      \'' . mysql_real_escape_string('This is demonstration document X.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-x') . '\',
      \'' . mysql_real_escape_string('demonstration-document-x.xls') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document Y') . '\',
      \'' . mysql_real_escape_string('This is demonstration document Y.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-y') . '\',
      \'' . mysql_real_escape_string('demonstration-document-y.pdf') . '\',
      NOW(),
      NOW()
    ),(
      \'' . mysql_real_escape_string($str_username) . '\',
      \'' . mysql_real_escape_string('Demonstration Document Z') . '\',
      \'' . mysql_real_escape_string('This is demonstration document Z.') . '\',
      \'' . mysql_real_escape_string('demonstration, demo-z') . '\',
      \'' . mysql_real_escape_string('demonstration-document-z.doc') . '\',
      NOW(),
      NOW()
    )
  ';
  $qry_insert_documents = mysql_query($qry_insert_documents);
  if(mysql_error($link) != ''){
    $bit_error = 1;
    $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  }
  //---/INSERT DEFAULT DOCUMENTS---
  //---INSERT DEFAULT DOCUMENT-CATEGORY RELS---
  $qry_select_documents = '
    SELECT
      int_id
    FROM
      docs_documents
    WHERE
      str_username = \'' . mysql_real_escape_string($str_username) . '\'
    ORDER BY
      int_id
  ';
  $qry_select_documents = mysql_query($qry_select_documents);
  //---=---
  $int_current_category_id = 0;
  while($obj_document = mysql_fetch_array($qry_select_documents)) :
    $int_current_category_id = $int_current_category_id < 6 ? $int_current_category_id + 1 : 1; //---INCREMENT FROM 1 TO 6, AND THEN START AT 1 AGAIN/---
    $qry_insert_document_category_rel = '
      INSERT INTO docs_document_category_rels(
        int_document_id,
        int_category_id
      )
      VALUES(
        \'' . mysql_real_escape_string($obj_document['int_id']) . '\',
        \'' . mysql_real_escape_string($int_current_category_id) . '\'
      )
    ';
    $qry_insert_document_category_rel = mysql_query($qry_insert_document_category_rel);
    if(mysql_error($link) != ''){
      $bit_error = 1;
      $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
    }
  endwhile;
  //---/INSERT DEFAULT DOCUMENT-CATEGORY RELS---
  RecursiveCopy(
    dirname(__FILE__) . '/../_protected-files/default/',
    dirname(__FILE__) . '/../_protected-files/user-files/' . $str_username . '/'
  ); //---UPLOAD DEFAULT DOCUMENTS/---
  if($bit_error) :
    mysql_query('ROLLBACK');
  else :
    mysql_query('COMMIT');
    $str_message = $str_message . '<p class="bold-text green-text">The default documents were uploaded.</p>';
  endif;
  //---=---
  mysql_close($link);
?>