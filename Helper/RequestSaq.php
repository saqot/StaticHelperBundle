<?php

namespace Saq\StaticHelperBundle\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * class: RequestSaq
 * хелпер по работе с Request данными
 * -----------------------------------------------------
 * @date    23.07.2017
 * @author  Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package Saq\StaticHelperBundle\Helper
 */
class RequestSaq
{
	public static $oRequestStack;
	private static $sfRequest;
	private static $aVars = [];

	/**
	 * Отдаем текущий класс.
	 * @return RequestSaq
	 */
	public static function that()
	{
		return new self;
	}

	/**
	 * Получаем экземпляр класса "Request"
	 * на основе текущих значений глобальных переменных PHP
	 * @return object|Request
	 */
	public static function getRequest() {
		if (!self::$sfRequest) {
			if (!$sfRequest = self::getRequestStack()->getCurrentRequest()) {
				$sfRequest = new Request();
			}
			self::$sfRequest = $sfRequest;
		}

		return self::$sfRequest;
	}

	/**
	 * @return object|RequestStack
	 * @throws \LogicException
	 */
	public static function getRequestStack()
	{
		if (!self::$oRequestStack) {
			if (!AppSaq::getContainer()->has('request_stack')) {
				throw new \LogicException('The request_stack is not found');
			}
			self::$oRequestStack = AppSaq::getContainer()->get('request_stack');
		}

		return self::$oRequestStack;
	}

	/**
	 * Проверяем на AJAX запрос
	 * @return bool
	 */
	public static function isAjax()
	{
		if (self::get('asJson')) {
			return true;
		}
		if (self::getRequest()->isXmlHttpRequest()) {
			return true;
		}

		return false;
	}

	/**
	 * @return null|string
	 */
	public static function getCurRoute()
	{
		return self::getRequest()->get('_route');
	}

	/**
	 * Проверка на наличие укзанного роута по его имени
	 * <code>
	 * // examples
	 *     * $route = 'app.homepage.index';
	 *     * RequestSaq::hasRoute('app.homepage.index'); // true
	 *     * RequestSaq::hasRoute('app.homepage'); // true
	 *     * RequestSaq::hasRoute('homepage.index'); // true
	 *     * RequestSaq::hasRoute('homepageindex'); // false
	 * </code>
	 * @param string $method <p>
	 *                       Имя роута, можно не полностью указывать
	 *                       </p>
	 * @return bool
	 */
	public static function hasRoute($method)
	{
		return StrSaq::isInStr(self::getCurRoute(), $method);
	}

	/**
	 * - Получаем файл из запроса
	 * todo тут еще надо будет доработать по проверке валидности файла.
	 * @param     $name
	 * @param int $filter
	 * @param int $flags
	 * @return mixed
	 */
	public static function getFiles($name = null, $filter = FILTER_SANITIZE_STRING, $flags = FILTER_FLAG_STRIP_LOW)
	{
		if ($name) {
			$files = static::getRequest()->files->get($name);

			return self::filter($files, $filter, $flags);
		} else {
			return static::getRequest()->files->all();
		}
	}

	/**
	 * Получаем строку в JSON формате и отдаем массивом
	 * @param        $name
	 * @param string $default
	 *
	 * @return mixed
	 */
	public static function getJson($name, $default = ''){
		$val = self::get($name, $default);
		return json_decode(html_entity_decode($val), true);
	}

	/**
	 * Получение значения переменных из любых Request запросов
	 * @param null $name
	 * @param null $default
	 * @param int  $filter
	 * @param int  $flags
	 * @return mixed|null
	 */
	public static function get($name = null, $default = null, $filter = FILTER_UNSAFE_RAW, $flags = FILTER_FLAG_ENCODE_LOW)
	{
		if (!self::$aVars) {

			if (!empty(self::getRequest()->attributes->all())) {
				self::$aVars = array_merge(self::$aVars, self::getRequest()->attributes->all());
			}
			if (!empty(self::getRequest()->query->all())) {
				self::$aVars = array_merge(self::$aVars, self::getRequest()->query->all());
			}
			if (!empty(self::getRequest()->request->all())) {
				self::$aVars = array_merge(self::$aVars, self::getRequest()->request->all());
			}
			// обрезаем пустые символы
			self::$aVars = ArraySaq::trimmed(self::$aVars);
		}
		if ($name) {
			$result = ArraySaq::get(self::$aVars, $name);
		} else {
			$result = self::$aVars;
		}

		if ($result) {
			return self::filter($result, $filter, $flags);
		}

		return $default;

	}

	private static function filter($var, $filter, $flags, $array = false)
	{
		if ($array or is_array($var)) {
			return filter_var_array($var, $filter);
		} else {
			return filter_var($var, $filter, $flags);
		}
	}

}

