<?php

class NNPG_Utils_Log
{
	public static function d($tag, $msg)
	{
		self::e($tag, $msg);
	}

	public static function e($tag, $msg)
	{
		file_put_contents('php://stderr', sprintf("%s\t%s\n", $tag, $msg));	
	}
}