<?php

try {



$sbt = new PDO("odbc:Driver={Microsoft Visual Foxpro Driver};SourceType=DBC;SourceDB=\\\harvey2\sbt\pro32\prodata.dbc;Exclusive=No");


} catch (PDOException $e) {
die("Error #".$e->getCode()." " . $e->getMessage() . "<br>");
}
?>
