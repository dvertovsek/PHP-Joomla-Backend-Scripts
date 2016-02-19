<?php
//dohvati Joomla database objekt
$db = JFactory::getDbo();

//dohvati novi Query (upit) objekt iz db (prethodno dohvacenog) objekta
$query = $db->getQuery(true);



/*
*
* TO DO: promjeniti kategorija id 
*
*/
$catId = 10;
/*
*
* TO DO: set offset
*
*/
$offset = 0;
/*
*
* TO DO: set article limit
*
*/
$limit = 3;

$catQuery = $db->getQuery(true);
$catQuery->select('alias');
$catQuery->from($db->quoteName('#__categories'));
$catQuery->where($db->quoteName('id') . ' ='.$catId);
$db->setQuery($catQuery);
$catAlias = $db->loadResult();

$query->select('id,alias,title,introtext');
$query->from($db->quoteName('#__content'));
$query->where($db->quoteName('state') . ' = 1');
$query->where($db->quoteName('catid') . ' = ' . $catId);
$query->order($db->quoteName('created') . ' DESC');
$query->setLimit($limit,$offset);

//postavljanje definiranog upita u db objekt, zapravo: izvrsavanje upita
$db->setQuery($query);
//dohvacanje rezultat
$results = $db->loadObjectList();
foreach($results as $key=>$val)
{
	$id = $val->id;
	$alias = $val->alias;
	$articleTitle = $val->title;

	$doc = new DOMDocument();

 	$intro = htmlspecialchars_decode($val->introtext);

 	@$doc->loadHTML($intro);
 	$divs = $doc->getElementsByTagName('img');

	$object = $divs->item(0)->attributes;

	if(is_object($object))
	{
		echo "<img src='".htmlentities($object->getNamedItem('src')->nodeValue)."' alt='".$articleTitle."' title='".$articleTitle."'>";
	}

	echo "<a href='http://gradimozadar.hr/".$catAlias."/".$id."-".$alias."'><h1>".$articleTitle."</h1></a>";

}

?>
