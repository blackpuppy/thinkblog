<?php
namespace Api\Controller;

use Home\Model\BaseModel;
use Think\Controller\RestController;

/**
 * API控制器基类。
 */
class BaseController extends RestController
{
	/**
	 * 读取POST输入。
	 * @return array POST输入。
	 */
	public function getPostInput()
	{
		return json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
	}
}
