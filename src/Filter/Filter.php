<?php namespace Assets\Filter;

class Filter{

	static public function add($name)
	{
		if (class_exists($filter = "Assets\\Filter\\{$name}") or class_exists($filter = "Assetic\\Filter\\{$name}"))
		{
			return new $filter;
		}		
	}
}