<?
class MyDB extends SQLite3
{
  function __construct()
  {
    $this->open('database.db');
  }
}

// Создание БД
function db_init()
{
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  }
  $sql = <<<EOF
    CREATE TABLE comment (
    ID INTEGER PRIMARY KEY AUTOINCREMENT     NOT NULL,
    text           TEXT    NOT NULL,
    item_id        INTEGER     NOT NULL,
    name        CHAR(50)   NOT NULL
    );

    CREATE TABLE item (
    ID INTEGER PRIMARY KEY AUTOINCREMENT    NOT NULL,
    title        CHAR(255)   NOT NULL,
    description  text NOT NULL
    ) ;

    CREATE TABLE master (
    ID INTEGER PRIMARY KEY AUTOINCREMENT     NOT NULL,
    name char(255) NOT NULL,
    email char(255) NOT NULL,
    photo char(255) NOT NULL ,
    description text NOT NULL) ;

    CREATE TABLE photo (
    ID INTEGER PRIMARY KEY  AUTOINCREMENT    NOT NULL,
    name char(255) NOT NULL,
    item_id INTEGER NOT NULL) ;

    CREATE TABLE  ticket (
    ID INTEGER PRIMARY KEY AUTOINCREMENT     NOT NULL,
    name char(255) NOT NULL,
    email char(255) NOT NULL,
    subject char(255) NOT NULL,
    text text NOT NULL,
    item_id        INTEGER    NOT NULL);
EOF;

  $ret = $db->exec($sql);
  if (!$ret) {
    echo $db->lastErrorMsg();
  } else {
    echo "Table created successfully\n";
  }
}

function insert_master($name,$email,$descr,$photo){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql = "INSERT INTO master (name,email,photo,description) VALUES (".$name.",".$email.",".$photo.",".$descr.")";
  $ret = $db->exec($sql);
  if(!$ret){
    echo $db->lastErrorMsg();
  };
}
function get_masters(){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql ="SELECT * FROM master";
  $ret = $db->query($sql);
  $array = array();
  while($data = $ret->fetchArray())
  {
    $array[] = $data;
  }
  $db->close();
  return $array;
}

function get_items(){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql ="SELECT * from item i, photo p where i.id = p.item_id";
  $ret = $db->query($sql);
  $array = array();
  while($data = $ret->fetchArray())
  {
    $array[] = $data;
  }
  $db->close();
  return $array;
}

function insert_item($title, $descr, $photos){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql = "INSERT INTO item (title, description) VALUES ('$title','$descr')";
  $ret = $db->exec($sql);
  $itemID = $db->lastInsertRowID();
  foreach($photos as $photo){
    $sql ="INSERT INTO photo (name, item_id) VALUES ('$photo','$itemID')";
    $ret = $db->exec($sql);
  }
  if(!$ret){
    echo $db->lastErrorMsg();
  };
}

function insert_comment($name,$email,$subject,$text,$item_id){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql = "INSERT INTO ticket (name,email,subject,text,item_id) VALUES (".$name.",".$email.",".$subject.",".$text.",".$item_id.")";
  $ret = $db->exec($sql);
  if(!$ret){
    echo $db->lastErrorMsg();
  };
}

function get_comments(){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
  };
  $sql ="SELECT * from ticket t, item i p where i.id = t.item_id";
  $ret = $db->query($sql);
  $array = array();
  while($data = $ret->fetchArray())
  {
    $array[] = $data;
  }
  $db->close();
  return $array;
}

?>
