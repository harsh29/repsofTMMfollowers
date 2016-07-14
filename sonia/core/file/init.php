<?php

/* file base structure:
 * 1. names of every files is format as [MD516l].postfix
 */
function init_base($dir)
{
  for ($ch = ord("0"); $ch <= ord("9"); $ch++) {
    if (!file_exists($dir. "/" . chr($ch))) {
      mkdir($dir. "/" . chr($ch));
    }
  }

  for ($ch = ord("a"); $ch <= ord("z"); $ch++) {
    if (!file_exists($dir. "/" . chr($ch))) {
      mkdir($dir. "/" . chr($ch));
    }
  }
}

?>
