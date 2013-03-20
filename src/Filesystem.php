<?php namespace Assets;

class Filesystem{

	/**
	 * Determine if a file exists.
	 *
	 * @param  string  $path
	 * @return bool
	 */
	public static function exists($path)
	{
		return file_exists($path);
	}

	/**
	 * Get the contents of a file.
	 *
	 * @param  string  $path
	 * @return string
	 */
	public static function get($path)
	{
		if ($this->exists($path)) return file_get_contents($path);

		throw new FileNotFoundException("File does not exist at path {$path}");
	}

	/**
	 * Get the contents of a remote file.
	 *
	 * @param  string  $path
	 * @return string
	 */
	public static function getRemote($path)
	{
		return file_get_contents($path);
	}

	/**
	 * Write the contents of a file.
	 *
	 * @param  string  $path
	 * @param  string  $contents
	 * @return int
	 */
	public static function put($path, $contents)
	{
		return file_put_contents($path, $contents, LOCK_EX);
	}

	/**
	 * Extract the file extension from a file path.
	 * 
	 * @param  string  $path
	 * @return string
	 */
	public static function extension($path)
	{
		return pathinfo($path, PATHINFO_EXTENSION);
	}

	/**
	 * Get the file's last modification time.
	 *
	 * @param  string  $path
	 * @return int
	 */
	public static function lastModified($path)
	{
		return filemtime($path);
	}

	/**
	 * Determine if the given path is a directory.
	 *
	 * @param  string  $directory
	 * @return bool
	 */
	public static function isDirectory($directory)
	{
		return is_dir($directory);
	}

	/**
	 * Find path names matching a given pattern.
	 *
	 * @param  string  $pattern
	 * @param  int     $flags
	 * @return array
	 */
	public static function glob($pattern, $flags = 0)
	{
		return glob($pattern, $flags);
	}

	/**
	 * Get an array of all files in a directory.
	 *
	 * @param  string  $directory
	 * @return array
	 */
	public static function files($directory)
	{
		$glob = glob($directory.'/*');

		if ($glob === false) return array();

		// To get the appropriate files, we'll simply glob the directory and filter
		// out any "files" that are not truly files so we do not end up with any
		// directories in our list, but only true files within the directory.
		return array_filter($glob, function($file)
		{
			return filetype($file) == 'file';
		});
	}

	/**
	 * Create a directory.
	 *
	 * @param  string  $path
	 * @param  int     $mode
	 * @param  bool    $recursive
	 * @return bool
	 */
	public static function makeDirectory($path, $mode = 0777, $recursive = false)
	{
		return mkdir($path, $mode, $recursive);
	}
}