<?php

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName(array('id','username','name','email')));

$query->from($db->quoteName('#__users'));

$db->setQuery($query);

$results = $db->loadObjectList();

 foreach($results as $key=>$val)

{

echo "<h1>".$val->name." (".$val->username.")"."</h1>";

$userId = $val->id;

$querry = $db->getQuery(true);

$querry->select($db->quoteName(array('title','introtext','images')));

$querry->from($db->quoteName('#__content'));

$querry->where($db->quoteName('created_by') . ' = '. $db->quote("$userId"));

$db->setQuery($querry);

$resultArticles = $db->loadObjectList();

 echo "<div class='well well-lg'>";

foreach($resultArticles as $key=>$val)

{

$imgArray = json_decode($val->images);

 

echo "<div class=media>";

echo "<div class='media-left'><a href=#><img style='height:150px;width:150px' class='media-object' src='../".$imgArray->image_intro."' alt=clanak></a></div>";

echo "<div class=media-body><h4 class=media-heading>".$val->title."</h4>".$val->introtext."</div>";

echo "</div>";

}

 

echo "</div>";

}

?>