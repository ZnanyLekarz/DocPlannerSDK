<?php
/**
 * Author: Łukasz Barulski
 * Date: 24.04.13 16:31
 */
namespace DocPlanner\SDK\Model\User;

use DocPlanner\SDK\Base\Result;

/**
 * Class ValidatePhone
 * @package DocPlanner\SDK\Model\User
 */
class ValidatePhone extends Result
{
	public $verification;
	public $process_id;
}