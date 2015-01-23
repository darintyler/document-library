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
      int_id,
      str_title,
      str_description,
      str_keywords,
      str_path,
      dat_uploaded,
      dat_last_updated
    FROM
      docs_documents
    WHERE
      str_username = \'' . mysql_real_escape_string($str_username) . '\'
      AND int_id = \'' . mysql_real_escape_string($int_document_id) . '\'
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
  //---INITIALIZE DATABASE---
  //---GENERATE PAGE TITLE---
  $document = mysql_fetch_assoc($qry_select_documents);
  $str_page_title = GenerateNavigation(
    [
      '<a href="/' . $str_document_library_directory_name . '/details.php?document_id=' . $document['int_id'] . '">' . preg_replace('/\s+/','&nbsp;',$document['str_title']) . '&nbsp;Details</a>',
      '<a href="/' . $str_document_library_directory_name . '/">Document&nbsp;Library</a>'
    ]
  );
  mysql_data_seek($qry_select_documents, 0);
  //---/GENERATE PAGE TITLE---
  //---/INITIALIZE PAGE---
  include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/header.php');
?>
<div class="body-container">
  <?php if($int_num_rows > 0) : ?>
    <table>
      <?php while($document = mysql_fetch_array($qry_select_documents)) : ?>
        <?php
          $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
          mysql_select_db($str_db_name);
          $qry_select_document_categories = '
            SELECT
              C.int_id,
              C.str_category
            FROM
              docs_document_category_rels AS DCR
            JOIN
              docs_categories AS C
            ON
              C.int_id = DCR.int_category_id
            WHERE
              DCR.int_document_id = \'' . mysql_real_escape_string($document['int_id']) . '\'
            ORDER BY
              str_category
          ';
          $qry_select_document_categories = mysql_query($qry_select_document_categories);
          echo mysql_error($link);
          mysql_close($link);
          //---=---
        ?>
        <tr>
          <th style="width:1px;">ID</th>
          <td><?php echo $document['int_id']?></td>
        </tr>
        <tr>
          <th>Title</th>
          <td><?php echo $document['str_title']?></td>
        </tr>
        <tr>
          <th>Description</th>
          <td>
            <table class="unstyled">
              <tr>
                <td style="width:1px;">
                  <span style="display:inline-block;margin:0 5px 0 0;">
                    <img style="width:50px;" src="images/<?php echo GetIconFileName($document['str_path']); ?>" alt="Document Icon"/>
                  </span>
                </td>
                <td>
                  <?php echo $document['str_description']?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <th>Keywords</th>
          <td><?php echo $document['str_keywords']?></td>
        </tr>
        <tr>
          <th>Categories</th>
          <td>
            <?php 
              while($category = mysql_fetch_array($qry_select_document_categories)) : 
                echo '<p><a href="index.php?category_id=' . $category['int_id'] . '">' . $category['str_category'] . '</a></p>';
              endwhile;
            ?>
          </td>
        </tr>
        <tr>
          <th>Date&nbsp;Created</th>
          <td>
            <?php 
              $dat_uploaded = new DateTime($document['dat_uploaded']);
              echo $dat_uploaded->format('F j, Y') . ' at ' . $dat_uploaded->format('g:i:s a');
            ?>
          </td>
        </tr>
        <tr>
          <th>Date&nbsp;Last&nbsp;Updated</th>
          <td>
            <?php 
              $dat_last_updated = new DateTime($document['dat_last_updated']);
              echo $dat_last_updated->format('F j, Y') . ' at ' . $dat_last_updated->format('g:i:s a');
            ?>
          </td>
        </tr>
        <tr>
          <th>Download</th>
          <td><a class="button" href="download.php?document_id=<?php echo $document['int_id']?>">Download</a></td>
        </tr>
        <tr>
          <th>Administration</th>
          <td>
            <a class="button" href="admin/update.php?document_id=<?php echo $document['int_id']; ?>">
              Update
            </a>
            <a class="button" href="admin/delete.php?document_id=<?php echo $document['int_id']; ?>">
              Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else : ?>
    <p>
      No document was found with ID <strong><?php echo $int_document_id; ?></strong>
    </p>
    <ul>
      <li><a href="/<?php echo $str_document_library_directory_name; ?>/index.php" class="focus-target">Go to Document Library homepage</a></li>
    </ul>
  <?php endif; ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/footer.php'); ?>