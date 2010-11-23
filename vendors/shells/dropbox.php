<?php
App::import('Vendor', array('Dropbox'));
class DropboxShell extends Shell
{
	public $methods = array(
		'info',
		'ls',
		'upload',
		'help',
	);

	public $key = '';
	public $secret = '';
	
	public $user = '';
	public $pass = '';
	
	public $Dropbox = null; 

	function main()
	{
		$method = array_shift($this->args);
		if(empty($method)) $method = 'help';
		if(in_array($method, $this->methods))
		{
			$this->$method();
		} else {
			$this->help();
		}
	}

	function upload()
	{
		$this->_init();
		$file = $this->args[0];
		$folder = 'test';
		$folder = '';
		if(!file_exists($file))
		{
			$this->out("File $file does not exist.");
			return;
		}
		if(!$this->_upload($file, $file))
		{
			$this->out("Upload of $file failed.");
		}
		$this->out("Uploaded $file to $folder.");
	}

	function ls()
	{
		$this->_init();
		$data = $this->Dropbox->getMetaData('/');
		if(empty($data['contents']))
		{
			$this->out('No Content available.');
			return;
		}
		$files = $folders = array();
		foreach($data['contents'] as $item)
		{
			if($item['is_dir'])
			{
				$folders[] = $item;
			} else {
				$files[] = $item;
			}
		}

		foreach($folders as $folder)
		{
			extract($folder);
			$this->out(" - folder - $path");
		}
		// debug($data);
	}

	function info()
	{
		$this->_init();
		$data = $this->Dropbox->getAccountInfo();
		debug($data);
	}

	function _upload($file, $folder = '')
	{
		$filename = basename($file);
		return $this->Dropbox->putFile($filename, $file);
	}

	function _init()
	{
		$this->user = Configure::read('Service.Dropbox.user');
		$this->pass = Configure::read('Service.Dropbox.pass');
		$this->key = Configure::read('Service.Dropbox.key');
		$this->secret = Configure::read('Service.Dropbox.secret');
		
		$oauth = new Dropbox_OAuth_PHP($this->key, $this->secret);
		if(empty($oauth))
		{
			$oauth = new Dropbox_OAuth_PEAR($this->key, $this->secret);
		}
		$this->Dropbox = new Dropbox_API($oauth);
		$tokens = $this->Dropbox->getToken($this->user, $this->pass); 
		$oauth->setToken($tokens);

/*
		if (isset($_SESSION['state'])) {
			$state = $_SESSION['state'];
		} else {
			$state = 1;
		}

		switch($state) {

			case 1 :
				$this->out("Step 1: Acquire request tokens");
				$tokens = $oauth->getRequestToken();
				print_r($tokens);

				// Note that if you want the user to automatically redirect back, you can
				// add the 'callback' argument to getAuthorizeUrl.
				$this->out( "Step 2: You must now redirect the user to:");
				$this->out( $oauth->getAuthorizeUrl());
				$_SESSION['state'] = 2;
				$_SESSION['oauth_tokens'] = $tokens;
				die();
			case 2 :
				$this->out("Step 3: Acquiring access tokens");
				$oauth->setToken($_SESSION['oauth_tokens']);
				$tokens = $oauth->getAccessToken();
				print_r($tokens);
				$_SESSION['state'] = 3;
				$_SESSION['oauth_tokens'] = $tokens;
			case 3 :
				$this->out( "The user is authenticated");
				$this->out( "You should really save the oauth tokens somewhere, so the first steps will no longer be needed");
				print_r($_SESSION['oauth_tokens']);
				$oauth->setToken($_SESSION['oauth_tokens']);
				break;
		}
		$this->con = new Dropbox_API($oauth);
*/
	}

	function help()
	{
		$this->out('');
		$this->out('  interact with your dropbox.');
		$this->hr();
		$this->out('  available tasks: ');
		$this->out('');
		$this->out('  - list         lists all folders you currently have in your dropbox');
		$this->out('  - help         prints the help you are looking at, right now.');
		$this->out('');
	}

}
