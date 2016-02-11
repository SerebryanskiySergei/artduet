<?
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//check if its an ajax request, exit if not
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    //exit script outputting json data
    $output = json_encode(
            array(
                'type' => 'error',
                'text' => 'Request must come from Ajax'
    ));
    die($output);
}
//check $_POST vars are set, exit if any missing
if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["description"])) {
    $output = json_encode(array('type' => 'error', 'text' => 'Input fields are empty!'));
    die($output);
}
//Sanitize input data using PHP filter_var().
$name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
$description = filter_var(trim($_POST["description"]), FILTER_SANITIZE_STRING);
//additional php validation
if (strlen($name) < 4) { // If length is less than 4 it will throw an HTTP error.
    $output = json_encode(array('type' => 'error', 'text' => 'Name is too short!'));
    die($output);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //email validation
    $output = json_encode(array('type' => 'error', 'text' => 'Please enter a valid email!'));
    die($output);
}
if (strlen($description) < 5) { //check emtpy message
    $output = json_encode(array('type' => 'error', 'text' => 'Too short description!'));
    die($output);
}
if(isset($_FILES["file"]["type"]))
{
  $validextensions = array("jpeg", "jpg", "png");
  $temporary = explode(".", $_FILES["file"]["name"]);
  $file_extension = end($temporary);
  if  ((($_FILES["file"]["type"] == "image/png")  ||
        ($_FILES["file"]["type"] == "image/jpg")  ||
        ($_FILES["file"]["type"] == "image/jpeg") &&
        in_array($file_extension, $validextensions))
  {
    if ($_FILES["file"]["error"] > 0)
    {
      echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
    }
    else
    {
      if (file_exists("upload/" . $_FILES["file"]["name"])) {
        echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
      }
      else
      {
        $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
        $targetPath = "upload/".$_FILES['file']['name']; // Target path where file is to be stored
        move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
        echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
        $photo = $_FILES['file']['name']
      }
    }
  }
  else
  {
    echo "<span id='invalid'>***Invalid file Type***<span>";
  }
}
$addMaster = insert_master($name,$email,$description,$photo);
//$sentMail = true;
if (!$sentMail) {
    $output = json_encode(array('type' => 'error', 'text' => 'Could not send mail! Please contact administrator.'));
    die($output);
} else {
    $output = json_encode(array('type' => 'message', 'text' => 'Hi ' . $username . ' Thank you for your email'));
    die($output);
}


?>
