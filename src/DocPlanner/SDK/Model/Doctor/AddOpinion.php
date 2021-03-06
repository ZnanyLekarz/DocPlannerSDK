<?php
/**
 * Author: Łukasz Barulski
 * Date: 24.04.13 16:31
 */
namespace DocPlanner\SDK\Model\Doctor;

use DocPlanner\SDK\Base\Result;

/**
 * Class AddOpinion
 * @package DocPlanner\SDK\Model\Doctor
 */
class AddOpinion extends Result
{
	public $added;
	public $add_signature;
	public $sig_type;
}