<?php
Configure::write('Env.hostname', str_replace('www.', '', env('HTTP_HOST')));

$folder = (isset($_REQUEST['url'])) ? '/'.$_REQUEST['url'] : '';
$folder = rtrim(str_replace($folder, '', env('REQUEST_URI')), '/'); //calculates the current sub-folder of installation
$folder = (strpos($folder, '?') !== false)
	? substr($folder, 0, strpos($folder, '?')) // remove request params
	: $folder;

Configure::write('Env.subfolder', $folder); //current subfolder where app resides

//TMP folder writable?
Configure::write('Env.tmp_writable', (is_writable(TMP)));

//database.php present?
Configure::write('Env.dbconfig.present', (file_exists(CONFIGS.'database.php')));

//database.php can be written?
Configure::write('Env.dbconfig.writable', (is_writable(CONFIGS)));

//database connectable?
$connectable = false;
if(Configure::read('Env.dbconfig.present'))
{
	uses('model' . DS . 'connection_manager');
	$db = ConnectionManager::getInstance();
	@$connected = $db->getDataSource('default');
	$connectable = $connected->isConnected();
}

Configure::write('Env.dbconfig.connected', $connectable);

Configure::write('Env.installed', (Configure::read('Env.dbconfig.connected')) ? true : false); //installed, when connected

Configure::write('Env.site_hash', md5(Configure::read('Env.hostname').Configure::read('Env.subfolder')));

if(!function_exists('get_ip')) {

	/**
	 * Returns current IP or 127.0.0.1 on localhost
	 *
	 * @return string current ip
	 */
	function get_ip() {
		return (env('REMOTE_ADDR') != '::1')
			? env('REMOTE_ADDR')
			: '127.0.0.1';
	}
}
