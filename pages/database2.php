<?php

try {

//$pickingtable = new PDO("odbc:Driver={Microsoft Visual Foxpro Driver};SourceType=DBF;SourceDB=\\\harvey2\sbt\pro32\dart\;Exclusive=No");
$pickingtable = new PDO("odbc:Driver={Microsoft Visual Foxpro Driver};SourceType=DBF;SourceDB=\\\Exchange\Accutest\pro32\dart\;Exclusive=No");


  } catch (PDOException $e) {
    die("Error #".$e->getCode()." " . $e->getMessage() . "<br>");
}

?> 