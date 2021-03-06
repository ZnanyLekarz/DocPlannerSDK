<?php
/**
 * Author: Łukasz Barulski
 * Date: 24.04.13 16:31
 */
namespace DocPlanner\SDK\Model\Visit;

use DocPlanner\SDK\Base\Result;

/**
 * Class Book
 * @package DocPlanner\SDK\Model\Visit
 */
class Book extends Result
{
	public $booked;
	public $visit_taken;
	public $phone_not_verified;
}