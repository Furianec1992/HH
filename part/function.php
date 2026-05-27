<?php
  function wedate($date){
    if (date("w", strtotime($date)) == 0) { $n = 5; }
    if (date("w", strtotime($date)) == 1) { $n = 4; }
    if (date("w", strtotime($date)) == 2) { $n = 3; }
    if (date("w", strtotime($date)) == 3) { $n = 2; }
    if (date("w", strtotime($date)) == 4) { $n = 1; }
    if (date("w", strtotime($date)) == 5) { $n = 0; }
    if (date("w", strtotime($date)) == 6) { $n = 6; }
    return date("Y-m-d", strtotime($date) + $n*60*60*24);
  }
?>