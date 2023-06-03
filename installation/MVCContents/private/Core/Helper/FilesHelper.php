<?php
namespace OsolMVC\Core\Helper;
class FilesHelper extends \OsolMVC\Core\CoreParent
{

	public function removeDirectory($path) {

		$files = glob($path . '/*');
		foreach ($files as $file) {
			is_dir($file) ? $this->removeDirectory($file) : unlink($file);
		}
		rmdir($path);
	
		return;
	}//public function removeDirectory($path)
}//class FilesHelper extends \OsolMVC\Core\CoreParent{
?>