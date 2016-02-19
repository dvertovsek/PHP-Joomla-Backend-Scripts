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
$catAlias="vijesti-gradevina";
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
	$alias = $val->alias;
	$articleTitle = $val->title;

	$doc = new DOMDocument();

 	$intro = $val->introtext;

 	$doc->loadHTML($intro);
 	$divs = $doc->getElementsByTagName('img');

	$object = $divs->item(0)->attributes;

	if(is_object($object))
	{
		echo "<img src='".htmlentities($object->getNamedItem('src')->nodeValue)."' alt='".$articleTitle."' title='".$articleTitle."'>";
	}

	echo "<a href='http://gradimozadar.hr/".$catAlias."/".$val->id."-".$val->alias."'><h1>".$articleTitle."</h1></a>";

}

?>
