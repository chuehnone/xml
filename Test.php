<?php
require "XMLTag.php";

$path = "strings.xml";
$obj = new SimpleXMLElement($path, null, true);
$tag = new XMLTag();
$tag->loadSimpleXMLElement($obj);
var_dump($tag->toArray());

echo $tag->toXML();
?>