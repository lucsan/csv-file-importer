<?php
namespace claro;

include_once('main.php');

$main = new main();

if (isset($_POST['uploading'])) {
  // Error check the new file here.
  // Upload.
  $tmpFile = move_uploaded_file($_FILES['upload']['tmp_name'], 'input.csv');
  // Relaod main for the new file values.
  $main = new main();
}

$main->importData();
$data = $main->processData();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

  <table>

<div>


      <?php foreach ($data as $title => $values): ?>
    <tr>
        <td>
          <?=$title;?>
        </td>
        <td>
          <?=$values['cost'];?>
        </td>
    </tr>
    <?php endforeach; ?>

</div>

<div>
  <a href="output.csv" >Download</a> this report as csv.
</div>


<div>
  Upload a New csv.
</div>

<div>
  <form action="" method="POST" enctype="multipart/form-data">
    <imput type="hidden" name="uploading" value="uploading" />
    <input type="file" name="file" />
    <input type="submit" name='upload' value="Upload" />
  </form>
</div>




  </table>




  </body>
</html>
