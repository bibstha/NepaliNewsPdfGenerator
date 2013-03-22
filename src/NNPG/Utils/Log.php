<?php

class NNPG_Utils_Log
{
	public static function d($tag, $msg)
	{
		self::e($tag, $msg);
	}

	public static function e($tag, $msg)
	{
		if (is_array($msg)) $msg = print_r($msg, true);
		file_put_contents('php://stderr', sprintf("%s\t%s\n", $tag, $msg));	
	}
}
