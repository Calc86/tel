<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$ret = my_exec('sudo asterisk -rx "sip show inuse"');
print_r($ret);

?>
