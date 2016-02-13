<?php
//dohvati Joomla database objekt
$db = JFactory::getDbo();

//dohvati novi Query (upit) objekt iz db (prethodno dohvacenog) objekta
$query = $db->getQuery(true);

//upit u bazu za dohvacanjem kategorija
$query->select(array('id','title'));

$query->from($db->quoteName('#__categories'));
//$query->where($db->quoteName('published') . ' = 1'); //opcionalno: ako zelimo provjeriti jeli kategorija 'published'
$query->where($db->quoteName('parent_id') . ' != 0');

//postavljanje definiranog upita u db objekt, zapravo: izvrsavanje upita
$db->setQuery($query);
$results = $db->loadObjectList();
foreach($results as $key=>$val)
{
	echo "<h1>".$val->title."</h1>";

	//za svaku kategoriju cemo sad napraviti isto sto i ranije, ali ovaj put cemo dohvatiti clanke (articles)
	$querry = $db->getQuery(true);
	$querry->select(array('title','introtext'));

	$querry->from($db->quoteName('#__content'));
	$querry->where($db->quoteName('catid') . ' = ' . $val->id);

	$db->setQuery($querry);
	$articlesResults = $db->loadObjectList();
	foreach($articlesResults as $keyArticle=>$valArticle)
	{
		echo "<h2>".$valArticle->title."</h2>";
		echo $valArticle->introtext;
	}
}

?>
