<?php

namespace Saq\StaticHelperBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

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
	 * Получаем экземпляр класса "Request" Symfony
	 * на основе текущих значений глобальных переменных PHP
	 * @return Request
	 */
	public static function sfRequest()
	{
		if (!self::$sfRequest) {
			if (php_sapi_name() == 'cli') {
				self::$sfRequest = new Request();
			} else {
				self::$sfRequest = AppSaq::getRequestStack()->getCurrentRequest();
			}
		}

		return self::$sfRequest;
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
		if (self::sfRequest()->isXmlHttpRequest()) {
			return true;
		}

		return false;
	}

	/**
	 * @return null|string
	 */
	public static function getCurRoute()
	{
		return self::sfRequest()->get('_route');
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
			$files = static::sfRequest()->files->get($name);

			return self::filter($files, $filter, $flags);
		} else {
			return static::sfRequest()->files->all();
		}
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

			if (!empty(self::sfRequest()->attributes->all())) {
				self::$aVars = array_merge(self::$aVars, self::sfRequest()->attributes->all());
			}
			if (!empty(self::sfRequest()->query->all())) {
				self::$aVars = array_merge(self::$aVars, self::sfRequest()->query->all());
			}
			if (!empty(self::sfRequest()->request->all())) {
				self::$aVars = array_merge(self::$aVars, self::sfRequest()->request->all());
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

