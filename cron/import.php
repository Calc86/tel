<?php
/*** process asterisk cdr file (Master.csv) insert usage
* http://www.johnlange.ca/tech-tips/asterisk/asterisk-cdr-csv-mysql-import-v20/
* values into a mysql database which is created for use
* with the Asterisk_addons cdr_addon_mysql.so
* The script will only insert NEW records so it is safe
* to run on the same log over-and-over.
*
* Author: John Lange (john@johnlange.ca)
* Date: Version 2 Released July 8, 2008
*
* Here is what the script does:
*
* Parse each row from the text log and insert it into the database after testing for a
* matching "calldate, src, duration" record in the database. Note that not all fields are
* tested.
*
* If you have a large existing database it is recomended that you add an index to the calldate
* field which will greatly speed up this import.
*
*/
$locale_db_host  = 'localhost';
$locale_db_name  = 'asterisk';
$locale_db_login = 'asterisk';
$locale_db_pass  = '';
if($argc == 2) {
    $logfile = $argv[1];
} else {
    print("Usage ".$argv[0]." <filename>\n");
    print("Where filename is the path to the Asterisk csv file to import (Master.csv)\n");
    print("This script is safe to run multiple times on a growing log file as it only imports records that are newer than the database\n");
    exit(0);
}
// connect to db
$linkmb = mysql_connect($locale_db_host, $locale_db_login, $locale_db_pass) or die("Could not connect : " . mysql_error());
mysql_select_db($locale_db_name, $linkmb) or die("Could not select database $locale_db_name");
//** 1) Find records in the asterisk log file. **
$rows = 0;
$handle = fopen($logfile, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    // NOTE: the fields in Master.csv can vary. This should work by default on all installations but you may have to edit the next line to match your configuration
    list($accountcode,$src, $dst, $dcontext, $clid, $channel, $dstchannel, $lastapp, $lastdata, $start, $answer, $end, $duration, $billsec, $disposition, $amaflags, $uniqueid ) = $data;	//$uid добавили
    /** 2) Test to see if the entry is unique **/
    $sql="SELECT calldate, src, duration".
	" FROM cdr".
	" WHERE calldate='$start'".
	" AND src='$src'".
	" AND duration='$duration'".
	" LIMIT 1";
    if(!($result = mysql_query($sql, $linkmb))) {
	print("Invalid query: " . mysql_error()."\n");
	print("SQL: $sql\n");
	die();
    }
    if(mysql_num_rows($result) == 0) { // we found a new record so add it to the DB
	// 3) insert each row in the database
	//добавили автоинкремент
	//$sql = "INSERT INTO cdr (0,calldate, clid, src, dst, dcontext, channel, dstchannel, lastapp, lastdata, duration, billsec, disposition, amaflags, accountcode) VALUES('$end', '".mysql_real_escape_string($clid)."', '$src', '$dst', '$dcontext', '$channel', '$dstchannel', '$lastapp', '$lastdata', '$duration', '$billsec', '$disposition', '$amaflags', '$accountcode')";
	$sql = "INSERT INTO cdr (id, calldate, clid, src, dst, dcontext, channel, dstchannel, lastapp, lastdata, duration, billsec, disposition, amaflags, uniqueid) VALUES(0, '$start', '".mysql_real_escape_string($clid)."', '$src', '$dst', '$dcontext', '$channel', '$dstchannel', '$lastapp', '$lastdata', '$duration', '$billsec', '$disposition', '$amaflags', '$uniqueid')";
	if(!($result2 = mysql_query($sql, $linkmb))) {
	    print("Invalid query: " . mysql_error()."\n");
	    print("SQL: $sql\n");
	    die();
	}
	print("Inserted: $start $src $duration\n");
	$rows++;
    } else {
	//print("Not unique: $start $src $duration\n");
    }
}
fclose($handle);
print("$rows imported\n");
?>
