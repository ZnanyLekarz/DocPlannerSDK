<?php
/**
 * Author: Łukasz Barulski
 * Date: 24.04.13 16:31
 */
namespace DocPlanner\SDK\Model\Doctor;

use DocPlanner\SDK\Base\Result;

class Calendars extends Result
{
	public $id;
	public $address_id;
	public $status;
	public $visit_time;
	public $hours;
	public $require_pesel;
	public $email;
	public $phone;
	/**
	 * @var array
	 */
	public $visits = [];
}