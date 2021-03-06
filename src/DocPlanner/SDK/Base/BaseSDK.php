<?php
/**
 * Author: Łukasz Barulski
 * Date: 22.04.13 15:13
 */
namespace DocPlanner\SDK\Base;

use DocPlanner\SDK\Base\ServiceDescription\DocPlanner;
use DocPlanner\SDK\Base\Parameter;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\Response;
use Guzzle\Service\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Class BaseSDK
 * @package DocPlanner\SDK\Base
 */
class BaseSDK
{
	const TOKEN = 'token';
	const TOKEN_SECRET = 'token_secret';
	const RESULT_TYPE_ARRAY = 'array';
	const RESULT_TYPE_RESULT_OBJECT = 'result';

	/**
	 * @var string
	 */
	protected $consumerKey;

	/**
	 * @var string
	 */
	protected $consumerSecret;

	/**
	 * @var string|null
	 */
	protected $token;

	/**
	 * @var string|null
	 */
	protected $tokenSecret;

	/**
	 * @var string
	 */
	protected $resultType;

	/**
	 * @var \Guzzle\Service\Client
	 */
	protected $client;

	/**
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $resultType
	 */
	public function __construct($consumerKey, $consumerSecret, $resultType = self::RESULT_TYPE_RESULT_OBJECT)
	{
		$this->client = new Client();
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
		$this->client->addSubscriber($this->_getOauthPlugin());
		$this->client->setDescription($this->_getServiceDescription());
		$this->resultType = $resultType;
	}

	/**
	 * @param string|null $token
	 * @param string|null $tokenSecret
	 *
	 * @return void
	 */
	public function setToken($token, $tokenSecret)
	{
		$this->token = $token;
		$this->tokenSecret = $tokenSecret;
		$this->client->addSubscriber($this->_getOauthPlugin());
	}

	/**
	 * @return array
	 */
	public function getToken()
	{
		return [self::TOKEN => $this->token, self::TOKEN_SECRET => $this->tokenSecret];
	}

	/**
	 * @return bool
	 */
	public function tokenIsSet()
	{
		return ($this->token && $this->tokenSecret);
	}

	/**
	 * @param                 $method
	 * @param Parameter|null  $parameters
	 *
	 * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
	 * @throws DPException
	 * @return array|mixed
	 */
	public function execute($method, Parameter $parameters = null)
	{
		$params = $parameters ? $parameters->all() : [];
		if ($parameters instanceof Parameter)
		{
			$parameters->clear();
		}
		$command = $this->client->getCommand($method, $params);
		$result = null;
		try
		{
			$result = $this->client->execute($command);
		}
		catch (ClientErrorResponseException $e)
		{
			/**
			 * @var Response $response
			 */
			$response = $e->getResponse();
			if (400 === $response->getStatusCode() && strstr($response->getMessage(), 'oauth_parameters_absent=oauth_token'))
			{
				throw new DPException('You have to be logged in! Use "User::requestAccess" or "DocPlannerSDK::setToken" methods!');
			}
			throw $e;
		}

		if ($result['status'] != 'ok' && $result !== true)
		{
			throw new DPException('Unknown error! More info: ' . var_export($result, true));
		}

		$items = $result['items'];

		switch ($this->resultType)
		{
			case self::RESULT_TYPE_ARRAY:
				return $items;

			case self::RESULT_TYPE_RESULT_OBJECT:
				list($group, $class) = explode('.', $method);
				$modelClass = '\DocPlanner\SDK\Model\\' . ucfirst($group) . '\\' . ucfirst($class);
				if (false === in_array(false, array_map('is_numeric', array_keys($items))))
				{
					$result = [];
					foreach ($items as $key => $value)
					{
						$result[$key] = new $modelClass($value);
					}
					return new Result($result);
				}
				return new $modelClass($items);

			default:
				throw new DPException('Unknown result type!');
		}
	}

	/**
	 * @return OauthPlugin
	 */
	private function _getOauthPlugin()
	{
		return new OauthPlugin([
			'consumer_key'    => $this->consumerKey,
			'consumer_secret' => $this->consumerSecret,
			'token'           => $this->token,
			'token_secret'    => $this->tokenSecret,
		]);
	}

	/**
	 * @return ServiceDescription
	 */
	private function _getServiceDescription()
	{
		return DocPlanner::factory();
	}
}
