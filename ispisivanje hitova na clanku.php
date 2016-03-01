<?php

	$url = htmlspecialchars($_SERVER['REQUEST_URI']);

	$arr = explode('/',$url);
	$articleId = explode('-',$arr[count($arr)-1])[0];

	if(is_numeric($articleId))
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(array('hits'));
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('id') . ' = '.(int)$articleId);

		$db->setQuery($query);
		$hitCount = $db->loadResult();

		echo "<div>".$hitCount."</div>";
	}
?>
