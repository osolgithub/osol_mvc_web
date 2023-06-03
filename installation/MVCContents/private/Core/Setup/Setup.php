<?php
/**
 * This is called fromDefault controller
 * To run thsi call http://<home_url>/<anyPermittedCoreController>/setup
 * http://<home_url>/<anyPermittedCoreController>/Contact/setup
 */
//comment line 498 in OSOLMySQL.php \upkar\php\helpers\ClassLogHelper::doLog($query, false);
namespace OsolMVC\Core\Setup;
class Setup extends \OsolMVC\Core\CoreParent
{
	private $tablePrefix = "";
	private $queries = array();
	public function runMysqlQueries()
	{
		$siteConfig = $this->getSiteConfig();
		$dbSettings = $siteConfig->getDBSettings();
		$this->tablePrefix = $dbSettings['table_prefix'];
		require_once(__DIR__."/Mysql.php");
		$db =  $this->getDB();
		echo "Running Queries <br />";
		foreach($this->queries as $query)
		{
			$db->executePS($query);
		}//foreach($this->queries as $query)
		echo "Done. Run all Queries!!! <br />";
	}//public function runMysqlQueries()
}//class Setup extends \OsolMVC\Core\CoreParent
?>