<?php

function Tree()
{
    $tree = array();
    $html = '';
    $q = "select * from price order by kod";
    $r = mysql_query($q);
    $n = mysql_num_rows($r);
    
    for($i=0;$i<$n;$i++)
    {
	$row = mysql_fetch_array($r);
	$name = $row['name'];
	$aa = preg_split("//",trim($row['kod']), -1, PREG_SPLIT_NO_EMPTY);
	//print_r($aa);
	$m = count($aa);
	$ind = array();
	for($j=0;$j<$m;$j++)
	    $ind[$j] = isset($aa[$j]) ? $aa[$j] : 'name';
	
	switch($m){
	    case 3:
		$tree[$ind[0]][$ind[1]][$ind[2]]['name'] = $name;
		break;
	    case 4:
		$tree[$ind[0]][$ind[1]][$ind[2]][$ind[3]]['name'] = $name;
		break;
	    case 5:
		$tree[$ind[0]][$ind[1]][$ind[2]][$ind[3]][$ind[4]]['name'] = $name;
		break;
	    case 6:
		$tree[$ind[0]][$ind[1]][$ind[2]][$ind[3]][$ind[4]][$ind[5]]['name'] = $name;
		break;
	    case 7:
		$tree[$ind[0]][$ind[1]][$ind[2]][$ind[3]][$ind[4]][$ind[5]][$ind[6]]['name'] = $name;
		break;
	}
	//$tree[$ind[0]][$ind[1]][$ind[2]][$ind[3]][$ind[4]][$ind[5]][$ind[6]]['name'] = $row['name'];
    }
    //echo '<pre>';
    //print_r($tree);
    //echo '</pre>';
    
    return $tree;
}

?>
















