<?php
//dohvati Joomla database objekt
$db = JFactory::getDbo();

//dohvati novi Query (upit) objekt iz db (prethodno dohvacenog) objekta
$query = $db->getQuery(true);


$query->select('*');
/*
*
* TO DO: promjeniti kategorija id 
*
*/
$catId = 10;
$query->from($db->quoteName('#__content'));
//$query->where($db->quoteName('published') . ' = 1'); //opcionalno: ako zelimo provjeriti jeli kategorija 'published'
$query->where($db->quoteName('catid') . ' = ' . $catId);
$query->order($db->quoteName('created') . ' DESC');
$query->setLimit('3');

//postavljanje definiranog upita u db objekt, zapravo: izvrsavanje upita
$db->setQuery($query);
//dohvacanje rezultat
$results = $db->loadObjectList();
foreach($results as $key=>$val)
{
	echo "<h1>".$val->title."</h1>";
	print_r($val);
	echo "<br><br><br>";
}

?>
