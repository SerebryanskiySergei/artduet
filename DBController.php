<?php

/**
 * Created by PhpStorm.
 * User: sereb
 * Date: 27.02.2016
 * Time: 15:09
 */

function init_db(){
    $db = sqlite_open("database.db") or die("failed to open/create the database");
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
    name char(255) NOT NULL,
    item_id INT NOT NULL) ;

    CREATE TABLE  ticket (
    ID INT PRIMARY KEY     NOT NULL,
    name char(255) NOT NULL,
    email char(255) NOT NULL,
    subject char(255) NOT NULL,
    text text NOT NULL,
    item_id        INT    NOT NULL);
EOF;
    sqlite_query($db,$sql);
}

function insert_master($name,$email,$descr,$photo){
    $db = sqlite_open("database.db") or die("failed to open/create the database");
    $sql = "INSERT INTO master (name,email,photo,description) VALUES ('$name','$email','$photo','$descr')";
    sqlite_query($db,$sql);
}

function get_masters(){
    $db = sqlite_open("database.db") or die("failed to open/create the database");
    $sql ="SELECT * FROM master";
    $res = sqlite_query($db,$sql);
    return $res;
}
