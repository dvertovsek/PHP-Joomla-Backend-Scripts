<?php

$jinput = JFactory::getApplication()->input;
$user_id = $jinput->getInt('user_id', 0);

if($user_id == 0)
{
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select($db->quoteName(array('id','username','name','email')));

	$query->from($db->quoteName('#__users'));

	$db->setQuery($query);

	$results = $db->loadObjectList();

	foreach($results as $key=>$val)

	{

		echo "<a href='?user_id=".$val->id."'>".$val->name." (".$val->username.")"."</a><br>";

	}
}

else
{

	$db = JFactory::getDbo();
	$querry = $db->getQuery(true);

	$querry->select($db->quoteName(array('title','introtext')));

	$querry->from($db->quoteName('#__content'));

	$querry->where($db->quoteName('created_by') . ' = '. $db->quote("$user_id"));

	$db->setQuery($querry);

	$resultArticles = $db->loadObjectList();
	foreach($resultArticles as $key=>$val)

	{

		$imgArray = json_decode($val->images);

		echo $val->title."<br>".$val->introtext;

	}
}


?>