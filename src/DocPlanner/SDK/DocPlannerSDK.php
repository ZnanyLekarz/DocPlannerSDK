<?php
/**
 * Author: Łukasz Barulski
 * Date: 23.04.13 17:37
 */
namespace DocPlanner\SDK;

use DocPlanner\SDK\Base\BaseSDK;
use DocPlanner\SDK\Base\Parameter;
use DocPlanner\SDK\Core\Doctor;
use DocPlanner\SDK\Core\Marketing;
use DocPlanner\SDK\Core\User;
use DocPlanner\SDK\Core\Geo;
use DocPlanner\SDK\Core\Visit;

class DocPlannerSDK
{
	const RESULT_TYPE_RESULT_OBJECT = BaseSDK::RESULT_TYPE_RESULT_OBJECT;
	const RESULT_TYPE_ARRAY = BaseSDK::RESULT_TYPE_ARRAY;

	/**
	 * @var Marketing
	 */
	public $marketing;

	/**
	 * @var Doctor
	 */
	protected $doctor;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var Geo
	 */
	protected $geo;

	/**
	 * @var Visit
	 */
	protected $visit;

	/**
	 * @var BaseSDK
	 */
	protected $baseSDK;

	/**
	 * @var Base\Parameter
	 */
	protected $parameter;

	/**
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $resultType
	 */
	public function __construct($consumerKey, $consumerSecret, $resultType = self::RESULT_TYPE_RESULT_OBJECT)
	{
		$this->baseSDK = new BaseSDK($consumerKey, $consumerSecret, $resultType);
		$this->baseSDK->setToken(false, "");
		$this->parameter = new Parameter();
	}

	/**
	 * @return Doctor
	 */
	public function doctor()
	{
		if (null === $this->doctor)
		{
			$this->doctor = new Doctor($this->baseSDK, $this->parameter);
		}
		return $this->doctor;
	}

	/**
	 * @return User
	 */
	public function user()
	{
		if (null === $this->user)
		{
			$this->user = new User($this->baseSDK, $this->parameter);
		}
		return $this->user;
	}

	/**
	 * @return Geo
	 */
	public function geo()
	{
		if (null === $this->geo)
		{
			$this->geo = new Geo($this->baseSDK, $this->parameter);
		}
		return $this->geo;
	}

	/**
	 * @return Visit
	 */
	public function visit()
	{
		if (null === $this->visit)
		{
			$this->visit = new Visit($this->baseSDK, $this->parameter);
		}
		return $this->visit;
	}

	/**
	 * @return Marketing
	 */
	public function marketing()
	{
		if (null === $this->marketing)
		{
			$this->marketing = new Marketing($this->baseSDK, $this->parameter);
		}
		return $this->marketing;
	}

	/**
	 * @param $token
	 * @param $token_secret
	 *
	 * @return void
	 */
	public function setToken($token, $token_secret)
	{
		$this->baseSDK->setToken($token, $token_secret);
	}

	/**
	 * @return array
	 */
	public function getToken()
	{
		return $this->baseSDK->getToken();
	}
}