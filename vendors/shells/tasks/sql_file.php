<?php
/**
 * Flour SqlFileTask
 * 
 * Takes care of inserting .sql files into database.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class SqlFileTask extends FlourShell {

	/**
	 * Execute the task
	 *
	 * @return void
	 * @access public
	 */
	function execute() {	}

	/**
	 * Import the file
	 *
	 * @return string dataset used or false, if error occured
	 * @access public
	 */
	function import($fileName, $datasource = 'default')
	{
		App::import('ConnectionManager');
		$ds = (in_array($datasource, ConnectionManager::sourceList())) ? $datasource : 'default'; //check, if this datasource exists
		$db = ConnectionManager::getDataSource($ds);

		$sqliteCompatibility = ($db->config['driver'] == 'Flour.DboSqlite3')
			? true
			: false;

		if($sqliteCompatibility) {
			$fileName = str_replace('.sql', '.lite.sql', $fileName);
		}

		$exists = file_exists($fileName);
		if($this->verbose) {
			$msg = ($exists)
				? sprintf('Found file %s', $fileName)
				: sprintf('FILE NOT FOUND! - %s', $fileName);
			
			$this->out($msg);
		}

		if(!$exists) {
			return false;
		}

		$statements = @file_get_contents($fileName);
		if(empty($statements))
		{
			return false;
		}

		$statements = explode(';', $statements);
		$prefix = (array_key_exists('prefix', $db->config)) ? $db->config['prefix'] : ''; //get current table-prefix
		$retVal = false;
		
		if($this->verbose) {
			$this->out(sprintf('Found %s Statements', count($statements)));
		} else {
			$this->ProgressBar->start(count($statements)-1); //remove one from last
		}
		foreach ($statements as $statement)
		{
			if (trim($statement) != '')
			{
				$statement = str_replace('"', '`', $statement);
				$statement = str_replace("CREATE TABLE `{$datasource}_", "CREATE TABLE IF NOT EXISTS `$prefix", $statement);
				$statement = str_replace("CREATE TABLE IF NOT EXISTS `{$datasource}_", "CREATE TABLE IF NOT EXISTS `$prefix", $statement);
				$statement = str_replace("INSERT INTO `{$datasource}_", "INSERT INTO `$prefix", $statement);
				$statement = str_replace("INSERT IGNORE INTO `{$datasource}_", "INSERT IGNORE INTO `$prefix", $statement);

				// //rewrite statements to be compatible with sqlite (in case of sqlite)
				// if($sqliteCompatibility) {
				// 	$statement = str_replace('ENGINE=MyISAM DEFAULT CHARSET=utf8', '', $statement);
				// 	$statement = preg_replace('/(UNIQUE)? KEY (.+) (\(.+\))(\,)?/','CONSTRAINT $2 $1 $3',$statement);
				// 	preg_match('/KEY (.+) (\(.+\))(\,)?/', $statement, $hits);
				// 	if(!empty($hits)) {
				// 		list($temp, $name, $cols) = $hits;
				// 		preg_match('/CREATE TABLE IF NOT EXISTS \`(.+)\` \(/', $statement, $hits);
				// 		list($temp, $dbname) = $hits;
				// 		$additionalQueries[] = "CREATE INDEX $name ON $dbname$cols;";
				// 		$this->out('on Statement: '.$statement);
				// 	}
				// }

				if($this->verbose) {
					preg_match('/CREATE TABLE IF NOT EXISTS \`(.+)\` \(/', $statement, $hits);
					if(!empty($hits)) {
						list($temp, $tablename) = $hits;
						$this->out(sprintf('Create table %s', $tablename));
					}
					preg_match('/INSERT IGNORE INTO \`(.+)\` \(/', $statement, $hits);
					if(!empty($hits)) {
						list($temp, $tablename) = $hits;
						$this->out(sprintf('Insert data into table %s', $tablename));
					}
				}

				$statement = String::insert(
					$statement,
					array(
						'UUID' => String::uuid(),
					)
				);
				// $this->out($statement);
				$result = $db->query($statement);
				if($result === false) {
					$this->out('ERROR on Statement: '.$statement);
				}
				if(!$this->verbose) {
					$this->ProgressBar->next();
				}
				if($result !== false) $retVal = true;
			}
		}
		$this->datasource = $ds;
		return $ds;
	}
}
