<?php
function getArticles($limit, $offset, $menuId)
{
	//dohvati Joomla database objekt
	$db = JFactory::getDbo();

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
	$catId = end($linkArray);

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
	return $results = $db->loadObjectList();
}

function showArticles($results)
{
	foreach($results as $key=>$val)
	{
		$id = $val->id;
		$alias = $val->content_alias;
		$articleTitle = $val->content_title;
		$categTitle = $val->categ_title;
		$articleHits = $val->hits;
		$username = $val->username;
		$created = (new DateTime($val->created))->format('d-m-Y');

		$categURL = "http://gradimozadar.hr/".$menuAlias;
		$articleURL = $categURL."/".$id."-".$alias;

		$doc = new DOMDocument();

	 	$intro = htmlspecialchars_decode($val->introtext);

	 	@$doc->loadHTML($intro);
	 	$divs = $doc->getElementsByTagName('img');

		$object = $divs->item(0)->attributes;

		if(is_object($object))
		{
			echo "<a href='".$articleURL."'><img src='".htmlentities($object->getNamedItem('src')->nodeValue)."' alt='".$articleTitle."' title='".$articleTitle."'></a>";
		}
		echo "<a href='".$categURL."'><div>".$categTitle."</div></a>";
		echo "<div>".$username."</div>";
		echo "<div>".$articleHits."</div>";
		echo "<div>".$created."</div>";
		echo "<a href='".$articleURL."'><h1>".$articleTitle."</h1></a>";

	}
}

//tu pozoves funkciju getArticles, parametri su, redom: LIMIT, OFFSET, MENUID
$resultArticles = getArticles(3, 0, 841);
//zatim showArticles kako bi se prikazali clanci, parametri je samo jedan: RESULTARTICLES (parametar dobijen iz prethodne funkcije)
showArticles($resultArticles);

?>
