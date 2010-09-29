<?php
require_once FLOUR.'/vendors/oauth/OAuth.php';

/**
 * Wrapper for CloudContent API.
 *
 * @see http://cloudcontent.cc/your_app/pages/describe
 * @package CloudContentClient
 */
class CloudContentService {

	/* Service Connectivity */

	/**
	 * Service host.
	 *
	 * @var string
	 */
	protected $_host = 'service.cloudcontent.cc';

	/**
	 * The name of your application.
	 *
	 * @var string
	 */
	protected $_app;

	/**
	 * API response format.
	 *
	 * @var string `'json'`, `'array'` or `'object'.`
	 */
	protected $_responseType = 'json';

	/* Authentication */

	/**
	 * CC user id.
	 *
	 * @var string
	 */
	protected $_userId;

	/**
	 * The OAuth consumer.
	 *
	 * @var OAuthConsumer
	 */
	protected $_consumer;

	/**
	 * OAuth signature method.
	 *
	 * After initalization this holds an instance
	 * of a OAuthSignatureMethod_* class.
	 *
	 * @var string|object `'HMAC-SHA1'` or `'PLAINTEXT'`.
	 */
	protected $_sigMethod = 'HMAC-SHA1';

	protected $_key = '';

	protected $_secret = '';

	/* Analysis/Error Handling */

	protected $_messages = array();

	protected $_errors = array();

	/**
	 * Constructor.
	 *
	 * @param array $config Configuration.
	 * @return void
	 */
	public function __construct(array $config = array()) {
		foreach ($config as $key => $value) {
			if ($value === null || $key == 'app') {
				continue;
			}
			if (method_exists($this, $key)) {
				$this->{$key}($value);
			} elseif (property_exists($this, "_{$key}")) {
				$this->{"_{$key}"} = $value;
			}
		}
		if (isset($config['app'])) {
			$this->app($config['app']);
		}
		switch ($this->_sigMethod) {
			case 'HMAC-SHA1':
			default:
				$this->_sigMethod = new OAuthSignatureMethod_HMAC_SHA1();
			break;
			case 'PLAINTEXT':
				$this->_sigMethod = new OAuthSignatureMethod_PLAINTEXT();
			break;
		}
		$this->_consumer = new OAuthConsumer($this->_key, $this->_secret);
	}

	/**
	 * Magic method enabling set of `getBy*` methods.
	 *
	 * @see _get()
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params) {
		preg_match('/^getBy(?P<field>\w+)$/', $method, $args);

		if (!$args) {
			$message = "Method %s not defined or handled in class %s";
			throw new BadMethodCallException(sprintf($message, $method, __CLASS__));
		}
		$options = array(
			'conditions' => array(
				strtolower($args['field']) => array_shift($params)
			) + (array) array_shift($params)
		);
		return call_user_func(array($this, '_get'), $options);
	}

	public function messages() {
		return $this->_messages;
	}

	public function errors() {
		return $this->_errors;
	}

	/**
	 * Sets or gets the hostname to bind to.
	 *
	 * @param string $name If not provided the current name is returned otherwise the value of
	 *                     this parameter is used to set the name.
	 * @return boolean
	 */
	public function host($name = null) {
		if (!$name) {
			return $this->_host;
		}
		$this->_host = $name;
		return true;
	}

	/**
	 * Sets or gets the application to bind to.
	 *
	 * @param string $name If not provided the current name is returned otherwise the value of
	 *                     this parameter is used to set the name. Passing `'auto'` results in the
	 *                     app name being determined by key and secret automatically.
	 * @return boolean
	 */
	public function app($name = null) {
		if (!$name) {
			return $this->_app;
		}
		if ($name == 'auto') {
			$data = array(
				'key' => $this->_key,
				'secret' => $this->_secret
			);
			$result = $this->_curl("http://{$this->_host}/auth/access/app", 'POST', $data);
			$result = json_decode($result, true);
			$name = $result['body']['data']['name'];

		} elseif (!preg_match('/[a-z]+/', $name)) {
			throw new Exception('The application name must only contain lowercase letters.');
		}
		$this->_app = $name;
		return true;
	}

	/**
	 * Sets or gets the response type.
	 *
	 * @param string $type If not provided the current type is returned otherwise the value of
	 *                     this parameter is used to set the type. Allowed values are `'json'`,
	 *                     `'array'` or `'object'`.
	 * @return boolean
	 */
	public function responseType($type = null) {
		if (!$type) {
			return $this->_responseType;
		}
		if (!in_array($type, array('json', 'array', 'object'))) {
			throw new Exception('Unknown response type.');
		}
		$this->_responseType = $type;
		return true;
	}

	/**
	 * Unsets OAuth token and secret from session.
	 *
	 * @return void
	 */
	public function forceRequestCredentials() {
		$_SESSION['oauth:key'] = $_SESSION['oauth:secret'] = null;
	}

	/**
	 * API request: Initializes a new application.
	 *
	 * @return mixed
	 */
	public function initApp() {
		return $this->_returnSingle($this->_request($this->_baseUrl() . "/init", array(
			'httpMethod' => 'GET',
			'auth' => false
		)));
	}

	/**
	 * API request: Creates a new document.
	 *
	 * @param string $title
	 * @param string $typeId
	 * @param array $fields
	 * @return mixed
	 */
	public function create($title, $typeId, array $fields = array()) {
		$fields['title'] = $title;

		$fields['meta']['status'] = isset($fields['status']) ? $fields['status'] : 1;
		$fields['meta']['tags'] = isset($fields['tags']) ? $fields['tags'] : array();
		$fields['meta']['index'] = isset($fields['index']) ? $fields['index'] : array();

		$document = $this->_build($fields);
		$back = $this->responseType();

		$this->responseType('array');
		$response = $this->_request($this->_baseUrl() . '/documents/create', array(
			'httpMethod' => 'POST',
			'json' => $document,
			'typeId' => $typeId
		));

		// reset response type
		$this->responseType($back);

		return $response['meta']['message_type'] == 'success'
			? $response['body']['id']
			: null;
	}

	/**
	 * API request: Sets a document field.
	 *
	 * @param string $id
	 * @param string $fieldName
	 * @param string $value
	 * @return mixed
	 */
	public function set($fieldName, $value, $id) {

		$responseType = $this->responseType();
		$this->responseType('json');

		$response = $this->_find($id);

		if (is_null($response)) {
			return $this->_returnSingle($response);
		}

		$response = json_decode($response, true);
		$fields = $response['body']['data'];

		if (isset($fields['meta'][$fieldName])) {
			$fields['meta'][$fieldName] = $value;
		}

		$fields['data'][$fieldName] = $value;

		// modified is autoset by CC
		unset($fields['modified']);

		$document = json_encode($fields);

		// reset response type
		$this->responseType($responseType);

		$response = $this->_request($this->_baseUrl() . '/documents/edit', array(
			'httpMethod' => 'POST',
			'id' => $id,
			'json' => $document
		));
		return $this->_returnSingle($response);
	}

	/**
	 * Returns a complete index of the entity given by $type.
	 *
	 * @param string $type Either `'documents'`, `'groups'`, `'types'` or `'users'`.
	 * @return mixed
	 */
	public function index($type = 'documents') {
		if (!in_array($type, array('documents', 'groups', 'types', 'users'))) {
			throw new BadMethodCallException("Unknown type {$type}.");
		}
		return $this->_returnList($this->_request($this->_baseUrl() . "/{$type}/index", array(
			'httpMethod' => 'GET'
		)));
	}

	/**
	 * Enumerates properties of documents.
	 *
	 * @param string $type Either `'tags'`, `'indexes'`.
	 * @return mixed
	 */
	public function enum($type) {
		if (!in_array($type, array('tags', 'indexes'))) {
			throw new BadMethodCallException("Unknown type {$type}.");
		}
		return $this->_returnList($this->_request($this->_baseUrl() . "/documents/enum/{$type}", array(
			'httpMethod' => 'GET'
		)));
	}

	/**
	 * Get a single document by id.
	 *
	 * @param string $id The id of the document.
	 * @return mixed
	 */
	public function get($id) {
		return $this->_get(array('conditions' => compact('id')));
	}

	/**
	 * Get on ore multiple documents using provided options.
	 *
	 * @see get()
	 * @see __call()
	 * @param array $options An array of options:
	 *              - conditions: An array of fields and values.
	 *              - limit: An integer to limit the number of retrived documents.
	 * @return mixed
	 */
	protected function _get(array $options = array()) {
		$default = array('conditions' => array(), 'limit' => null);
		$options += $default;

		$allowedFields = array('tag', 'type', 'index');

		if ($options['limit']) {
			$query['limit'] = $options['limit'];
		}
		$query['httpMethod'] = 'GET';

		if (isset($options['conditions']['id'])) {
			$action = 'get';
			$query['id'] = $options['conditions']['id'];
		} else {
			$field = key($options['conditions']);

			if (!in_array($field, $allowedFields)) {
				throw new Exception("Cannot get document by `{$field}`");
			}

			$action = "get-by-{$field}";
			$query['key'] = $options['conditions'][$field];
		}

		$response = $this->_request($this->_baseUrl() . "/documents/{$action}", $query);
		return $this->_returnSingle($response);
	}

	/**
	 * Deletes a document by its ID.
	 *
	 * @param string $id ID of the document to delete.
	 * @param int $force
	 * @return mixed
	 */
	public function delete($id, $force = 0) {
		$response = $this->_request($this->_baseUrl() . '/documents/delete', array(
			'httpMethod' => 'POST',
			'id' => $id,
			'force' => $force
		));
		return $this->_returnSingle($response);
	}

	/**
	 * API request: Grants permission to document.
	 *
	 * @param string $actionName
	 * @param string $id
	 * @param string $userId
	 * @param string $groupId
	 * @return mixed
	 */
	public function grantPermission($actionName, $id, $userId, $groupId=null) {

		$responseType = $this->responseType();
		$this->responseType('json');

		$response = $this->_find($id);

		if (is_null($response)) {
			return $this->_returnSingle($response);
		}

		$response = json_decode($response, true);
		$fields = $response['body']['data'];

		$fields['actions'][$actionName] = array(
			'title' => $actionName,
			'users' => !empty($userId) ? array($userId) : array(),
			'groups' => empty($groupId) ? array() : array($groupId),
		);

		// modified is autoset by CC
		unset($fields['modified']);

		$document = json_encode($fields);

		// reset response type
		$this->responseType($responseType);

		$response = $this->_request($this->_baseUrl() . '/documents/edit', array(
			'httpMethod' => 'POST',
			'id' => $id,
			'json' => $document
		));
		return $this->_returnSingle($response);
	}

	/**
	 * API request: Creates a new type.
	 *
	 * @param string $title
	 * @param array $fields
	 * @return mixed
	 */
	public function addType($title, array $fields = array()) {
		$fields['title'] = $title;
		$document = $this->_build($fields);

		$responseType = $this->responseType();
		$this->responseType('array');

		$response = $this->_request($this->_baseUrl() . '/types/create', array(
			'httpMethod' => 'POST',
			'json' => $document
		));
		// reset response type
		$this->responseType($responseType);

		return $response['meta']['message_type'] == 'success'
			? $response['body']['id']
			: null;
	}

	// create User
	// TODO implement like addType()
	public function addUser($name, $permissions = array(), $data = array()) {
		throw new Exception('Not implemented');
	}

	// create Group
	// TODO implement like addType()
	public function addGroup($name, $permissions = array(), $data = array()) {
		throw new Exception('Not implemented');
	}

	/**
	 * API request: Deletes a type.
	 *
	 * @param string $id
	 * @param int $force
	 * @return mixed
	 */
	public function deleteType($id, $force=0) {
		$response = $this->_request($this->_baseUrl() . '/types/delete', array(
			'httpMethod' => 'POST',
			'id' => $id,
			'force' => $force
		));
		return $this->_returnSingle($response);
	}

	public function checkLogin($username, $password) {
		throw new Exception('Not implemented');
	}

	protected function _baseUrl() {
		return "http://{$this->_host}/{$this->_app}";
	}

	/**
	 * Requests CC API.
	 *
	 * @param string $url
	 * @param array $options
	 *              - auth
	 *              - httpMethod
	 *              - id
	 *              - typeId
	 *              - json
	 *              - force
	 *              - key
	 *              - limit
	 *              - startKey
	 *              - startKeyDocid
	 *              - desc
	 * @return mixed
	 */
	protected function _request($url, $options = array()) {
		$default = array(
			'auth' => true,
			'httpMethod' => 'GET',
			'id' => null,
			'json' => '',
			'typeId' => null,
			'force' => 0,
			'key' => null,
			'limit' => 10,
			'startKey' => null,
			'startKeyDocId' => null,
			'desc' => null
		);
		$options += $default;
		extract($options);

		if ($auth) {
			if (empty($_SESSION['oauth:key']) || empty($_SESSION['oauth:secret'])) {
				$this->_auth();
			}
			$accessConsumer = new OAuthConsumer($_SESSION['oauth:key'], $_SESSION['oauth:secret']);

			$authReq = OAuthRequest::from_consumer_and_token(
				$this->_consumer,
				$accessConsumer,
				$httpMethod,
				$url,
				array(
					'userid' => $this->_userId,
					'id' => $id,
					'typeid' => $typeId,
					'json' => $json,
					'force'=> $force,
					'key' => $key,
					'limit' => $limit,
					'startkey'=> $startKey,
					'startkey_docid' => $startKeyDocId,
					'desc' => $desc
			));
			$authReq->sign_request($this->_sigMethod, $this->_consumer, $accessConsumer);

			$result = $httpMethod == 'POST'
				? $this->_curl($authReq->get_normalized_http_url(), 'POST', $authReq->to_postdata())
				: $this->_curl($authReq->to_url(), 'GET');
		} else {
			$result = $this->_curl($url, 'GET');
		}

		switch ($this->_responseType) {
			default:
			case 'json':
				return $result;
				break;
			case 'array':
				return json_decode($result, true);
				break;
			case 'object':
				return json_decode($result);
				break;
		}
	}

	/**
	 * CURL wrapper.
	 *
	 * @param string $url
	 * @param string $httpMethod
	 * @param string $postFields
	 * @return string JSON
	 */
	protected function _curl($url, $httpMethod, $postFields = null) {
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_USERAGENT, 'CloudContent Client (curl); PHP ' . phpversion());

		if ($httpMethod == 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
		}

		if ($postFields) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);

		if ($result) {
			$this->_messages[] = "Request `{$httpMethod} {$url}` succeeded.";
		} else {
			$this->_errors[] = "Request `{$httpMethod} {$url}` failed.";
		}
		curl_close($ch);
		return $result;
	}

	/**
	 * Authorizes against webservice (saves key and secret in session).
	 *
	 * @return void
	 */
	protected function _auth() {
		$this->_messages[] = "Auth against service.";

		$reqReq = OAuthRequest::from_consumer_and_token(
			$this->_consumer,
			null,
			'POST',
			"http://{$this->_host}/auth/request/token",
			array()
		);
		$reqReq->sign_request($this->_sigMethod, $this->_consumer, null);

		$result = $this->_curl($reqReq->get_normalized_http_url(), 'POST', $reqReq->to_postdata());
		$result = json_decode($result, true);

		$tokenConsumer = new OAuthConsumer(
			$result['body']['data']['key'],
			$result['body']['data']['secret']
		);

		$accReq = OAuthRequest::from_consumer_and_token(
			$this->_consumer,
			$tokenConsumer,
			'POST',
			"http://{$this->_host}/auth/access/token",
			array()
		);
		$accReq->sign_request($this->_sigMethod, $this->_consumer, $tokenConsumer);

		$result = $this->_curl($accReq->get_normalized_http_url(), 'POST', $accReq->to_postdata());
		$result = json_decode($result, true);

		$_SESSION['oauth:key'] = $result['body']['data']['key'];
		$_SESSION['oauth:secret'] = $result['body']['data']['secret'];

		$this->_messages[] = "Authed with key `{$_SESSION['oauth:key']}` and secret `{$_SESSION['oauth:secret']}`.";
	}

	/**
	 * Builds a CloudContent document. This function "knows" the CC basic schema and
	 * therefore returns a valid CC document.
	 *
	 * @param array The document data.
	 * @return string JSON
	 */
	protected function _build(array $data) {
		$meta['title'] = isset($data['title'])
			? $data['title']
			: 'no title ' . time();// title-type combination must be unique in CC

		$meta['description'] = isset($data['description'])
			? $data['description']
			: null;

		$document['meta'] = $meta;

		if (isset($data['meta'])) {

			$document['meta'] = array_merge_recursive($document['meta'], $data['meta']);
			unset($data['meta']);
		}

		$document['data'] = $data;
		$document['actions'] = array();

		return json_encode($document);
	}

	/**
	 * Return a "list" response.
	 *
	 * @param mixed $response
	 * @return mixed
	 * @fixme maybe you'd like to filter the full API response; response type must be switched here
	 */
	protected function _returnList($response) {
		return $response;
	}

	/**
	 * Return a "single" response.
	 *
	 * @param mixed $response
	 * @return mixed
	 * @fixme maybe you'd like to filter the full API response; response type must be switched here
	 */
	protected function _returnSingle($response) {
		return $response;
	}

	/**
	 * API request: Finds a document and returns full API response/message.
	 *
	 * @param string $id
	 * @return mixed
	 */
	protected function _find($id) {
		return $this->_request($this->_baseUrl() . '/documents/get', array(
			'httpMethod' => 'GET',
			'id' => $id
		));
	}
}

?>