<?php

namespace Saq\StaticHelperBundle\Helper;

/**
 * class: ArraySaq
 * Хелпер по работе с массивами
 * -----------------------------------------------------
 * @date 20.07.2017
 * @author Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package Saq\StaticHelperBundle\Helper
 */
class ArraySaq
{
	/**
	 * Отдаем текущий класс.
	 * @return ArraySaq
	 */
	public static function that()
	{
		return new self;
	}

	/**
	 * Приводим значения массива к строке
	 * наприер ['key' => 2] в ['key' => '2']
	 * @param array $arr
	 * @return array
	 */
	public static function toStrVal(array $arr)
	{
		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				$arr[$k] = static::toStrVal($v);
			} else {
				if (is_object($v)) {

				} else {
					$arr[$k] = strval($v);
				}

			}
		}

		return $arr;
	}

	/**
	 * Вернуть значение из многоуровневого массива, используя синтаксис имени с точкой.
	 *
	 * $array = ['names' => ['joe' => ['programmer']]];
	 * $value = get($array, 'names.joe');
	 * $value = get($array, 'names.john', 'default');
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = null)
	{
		if (is_null($key)) {
			return $array;
		}

		if (isset($array[$key])) {
			return $array[$key];
		}

		foreach (explode('.', $key) as $segment) {
			if (! is_array($array) || ! array_key_exists($segment, $array)) {
				return $default;
			}

			if (empty($array[$segment])) {
				return $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Сделать многоуровневый массив одноуровневым, объединяя вложенные массивы с помощью точки в именах.
	 * $array = array('foo' => array('bar' => 'baz'));
	 * $array = array_dot($array);
	 * array('foo.bar' => 'baz');
	 * @param $array
	 * @param string $prepend
	 * @return array
	 */
	public static function dot($array, $prepend = '')
	{
		$results = [];

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$results = array_merge($results, static::dot($value, $prepend.$key.'.'));
			} else {
				$results[$prepend.$key] = $value;
			}
		}

		return $results;
	}

	/**
	 * сортировка по значению, возвращает первый элемент
	 * @param $array
	 * @return null
	 */
	public static function getFirstElement($array){
		$arrayEl = null;
		if ($array and is_array($array)) {
			sort($array);
			reset($array);
			$arrayEl = array_shift($array);
		}

		return $arrayEl;
	}

	/**
	 * сортировка по ключу, возвращает первый элемент
	 *
	 * @param $array
	 * @return null
	 */
	public static function getFirstKeyElement($array){
		$arrayEl = null;
		if ($array and is_array($array)) {
			ksort($array);
			reset($array);
			$arrayEl = array_shift($array);
		}

		return $arrayEl;
	}

	/**
	 * @param array $vars
	 * @param null|string $charlist [optional] <p>
	 *                              default: " \t\n\r\0\x0B"
	 *                              </p>
	 * @return array
	 */
	public static function trimmed(array $vars, $charlist = null)
	{
		foreach ($vars as $k => $v) {
			if (is_array($v)) {
				$vars[$k] = static::trimmed($v);
			} else {
				$vars[$k] = trim($v, $charlist);
			}
		}
		return $vars;
	}
}

