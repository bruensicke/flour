<?php
/**
 * Syslogger Class
 *
 * INSTALLATION:
 *
 * In app/config/bootstrap.php put:
 *
 * CakeLog::config('default', array('engine' => 'Flour.SysLog'));
 * 
 *
 * To get rid of unneded cake-errors:
 *
 * In app/config/core.php:
 *
 * Configure::write('log', E_ALL & ~E_DEPRECATED);
 * Configure::write('debug', 0);
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class SysLog {
	/**
	 * Ident to send with the log files.
	 *
	 * @var string
	 */
	var $_ident = null;

	/**
	 * The facility to use for storing log files.
	 *
	 * @var string
	 */
	var $_facility = null;

	/**
	 * Constructs a new SysLog Logger.
	 * 
	 * Options
	 *
	 * - `ident` the ident to be added to each message.
	 * - `facility` what type of application is recording a message. Default: LOG_LOCAL0. LOG_USER if Windows.
	 *
	 * @param array $options Options for the SysLog, see above.
	 * @return void
	 */
	function SysLog($options = array()) {
		if ($this->isWindows()) {
			$default_facility = LOG_USER;
		} else {
			$default_facility= LOG_LOCAL0;
		}
		$options += array('ident' => LOGS, 'facility' => $default_facility);
		$this->_ident = $options['ident'];
		$this->_facility = $options['facility'];
	}

	/**
	 * Utilty method to identify if we're running on a Windows box.
	 *
	 * @return boolean if running on windows.
	 */
	function isWindows() {
		return (DIRECTORY_SEPARATOR == '\\' ? true : false);
	}

	/**
	 * Implements writing to the specified syslog
	 *
	 * @param string $type The type of log you are making.
	 * @param string $message The message you want to log.
	 * @return boolean success of write.
	 */
	function write($type, $message) {
		$debugTypes = array('notice', 'info', 'debug');
		$priority = LOG_INFO;
		if ($type == 'error' || $type == 'warning') {
			$priority = LOG_ERR;
		} elseif (in_array($type, $debugTypes)) {
			$priority = LOG_DEBUG;
		}
		$output = date('Y-m-d H:i:s') . ' ' . ucfirst($type) . ': ' . $message . "\n";
		if (!openlog($this->_ident, LOG_PID | LOG_PERROR, $this->_facility)) {
			return false;
		}
		$result = syslog($priority, $output);
		closelog();
		return $result;
	}
}
