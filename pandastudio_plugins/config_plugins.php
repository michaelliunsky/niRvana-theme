<?php
function pd_getDir($dir) 
{
	$dirArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) 
	{
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) 
		{
			if ($file != "." && $file != ".."&&!strpos($file,".")) 
			{
				$dirArray[$i]=$file;
				$i++;
			}
		}
		closedir ( $handle );
	}
	return $dirArray;
}
function pd_getFile($dir) 
{
	$fileArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) 
	{
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) 
		{
			if ($file != "." && $file != ".."&&strpos($file,".")) 
			{
				$fileArray[$i]=$file;
				if($i==100)
				{
					break;
				}
				$i++;
			}
		}
		closedir ( $handle );
	}
	return $fileArray;
}
$plugins_dir = pd_getDir(dirname(__file__));
foreach ($plugins_dir as $plugin_folder) 
{
	$folder = dirname(__file__).'/'.$plugin_folder;
	if (is_dir($folder)) 
	{
		$files = pd_getFile($folder);
		if (in_array("index.php", $files)) 
		{
			include_once($folder."/index.php");
		}
	}
}
?><?php
?>