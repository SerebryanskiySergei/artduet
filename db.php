<?
class MyDB extends SQLite3
{
  function __construct()
  {
    $this->open('test.db');
  }
}


function db_init()
{
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  }
  $sql = <<<EOF
    CREATE TABLE comment (
    ID INT PRIMARY KEY     NOT NULL,
    text           TEXT    NOT NULL,
    item_id        INT     NOT NULL,
    name        CHAR(50)   NOT NULL
    );

    CREATE TABLE item (
    ID INT PRIMARY KEY     NOT NULL,
    title        CHAR(255)   NOT NULL,
    description  text NOT NULL
    ) ;

    CREATE TABLE master (
    ID INT PRIMARY KEY     NOT NULL,
    name char(255) NOT NULL,
    email char(255) NOT NULL,
    photo char(255) NOT NULL ,
    description text NOT NULL) ;

    CREATE TABLE photo (
    ID INT PRIMARY KEY     NOT NULL,
    name char(255) NOT NULL ,
    item_id INT NOT NULL) ;

    CREATE TABLE  ticket (
    ID INT PRIMARY KEY     NOT NULL,
    name char(255) NOT NULL,
    email char(255) NOT NULL,
    subject char(255) NOT NULL,
    text text NOT NULL);
    EOF;

  $ret = $db->exec($sql);
  if (!$ret) {
    echo $db->lastErrorMsg();
  } else {
    echo "Table created successfully\n";
  }
  $db->close();
}
function insert_master($name,$email,$descr,$photo){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql = "INSERT INTO master (name,email,photo description) VALUES (".$name.",".$email.",".$descr.",".$photo.")";
  $ret = $db->exec($sql);
  if(!$ret){
    echo $db->lastErrorMsg();
  };
  $db->close();
}
function get_masters(){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql ="SELECT * from master";
  $ret = $db->query($sql);
  $db->close();
  return $ret;
}

function get_items(){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql ="SELECT * from item i, photo p where i.id = p.item_id";
  $ret = $db->query($sql);
  $db->close();
  return $ret;
}
function insert_item($title, $descr, $photos){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql = "INSERT INTO item (title, description) VALUES (".$title.",".$descr.")";
  $ret = $db->exec($sql);
  foreach($photos as $photo){
    $sql ="INSERT INTO photo (ref, item_id) VALUES (".$photo['name'].",".$ret['id'].")";
    $ret = $db->exec($sql);
  }
  if(!$ret){
    echo $db->lastErrorMsg();
  };
  $db->close();
}

?>
