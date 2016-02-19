<?php


$link = "index.php?option=com_content&view=category&layout=blog&id=86";
$poljeLinkova = explode("=", $link);
echo end($poljeLinkova);



//dohvati Joomla database objekt
$db = JFactory::getDbo();

/*
*
* TO DO: promjeniti menu id 
*
*/
$menuId = 1;
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

//dohvati novi Query (upit) objekt iz db (prethodno dohvacenog) objekta
$menuQuery = $db->getQuery(true);
$menuQuery->select('link,alias');
$menuQuery->from($db->quoteName('#__menu'));
$menuQuery->where($db->quoteName('id') . ' ='.$menuId);
//postavljanje definiranog upita u db objekt, zapravo: izvrsavanje upita
$db->setQuery($menuQuery);
//dohvacanje rezultat
$menuResult = $db->loadObjectList()[0];
$menuAlias = $menuResult->alias;

$linkArray = explode("=", $menuResult->link);
$catId = end($poljeLinkova);

$query = $db->getQuery(true);

$query->select(array('content.id', 'content.hits', 'content.alias AS content_alias', 'content.title AS content_title', 'content.introtext', 'content.created', 'users.username', 'categ.title AS categ_title'));
$query->from($db->quoteName('#__content', 'content'));
$query->join('INNER', $db->quoteName('#__users', 'users') . ' ON (' . $db->quoteName('content.created_by') . ' = ' . $db->quoteName('users.id') . ')');
$query->join('LEFT', $db->quoteName('#__categories', 'categ') . ' ON (' . $db->quoteName('categ.id') . ' = ' . $db->quoteName('content.catid') . ')');
$query->where($db->quoteName('content.state') . ' = 1');
$query->where($db->quoteName('content.catid') . ' = ' . $catId);
$query->order($db->quoteName('content.created') . ' DESC');
$query->setLimit($limit,$offset);

$db->setQuery($query);
$results = $db->loadObjectList();

foreach($results as $key=>$val)
{
	$id = $val->id;
	$alias = $val->content_alias;
	$articleTitle = $val->content_title;
	$categTitle = $val->categ_title;
	$articleHits = $val->hits;
	$username = $val->username;
	$created = $val->created;

	$doc = new DOMDocument();

 	$intro = htmlspecialchars_decode($val->introtext);

 	@$doc->loadHTML($intro);
 	$divs = $doc->getElementsByTagName('img');

	$object = $divs->item(0)->attributes;

	if(is_object($object))
	{
		echo "<img src='".htmlentities($object->getNamedItem('src')->nodeValue)."' alt='".$articleTitle."' title='".$articleTitle."'>";
	}
	echo "<div>".$categTitle."</div>";
	echo "<div>".$username."</div>";
	echo "<div>".$articleHits."</div>";
	echo "<div>".$created."</div>";
	echo "<a href='http://gradimozadar.hr/".$menuAlias."/".$id."-".$alias."'><h1>".$articleTitle."</h1></a>";

}

?>
