<?php

namespace Saq\StaticHelperBundle\Helper;

/**
 * class: StrSaq
 * Хелпер по работе со строками
 * -----------------------------------------------------
 * 'str testing' => 'туТ раЗный <b>bold</b> tExt'
 * 'caseLower' => 'тут разный <b>bold</b> text'
 * 'caseUp' => 'ТУТ РАЗНЫЙ <B>BOLD</B> TEXT'
 * 'ucFirst' => 'Тут разный <b>bold</b> text'
 * 'ucFirstOnly' => 'ТуТ раЗный <b>bold</b> tExt'
 * 'ucWords' => 'Тут Разный <B>Bold</b> Text'
 * 'cutText 15' => 'туТ раЗный bold ...'
 * -----------------------------------------------------
 * @date 21.07.2017
 * @author Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package Saq\StaticHelperBundle\Helper
 */
class StrSaq 
{
	/**
	 * Отдаем текущий класс.
	 * @return StrSaq
	 */
	public static function that()
	{
		return new self;
	}

	/**
	 * Проверяем наличие текста в строке
	 * @param string $str Строка, в которой ищем
	 * @param string $search То, что ищем
	 * @return bool
	 */
	public static function isInStr($str, $search)
	{
		$pos = strripos($str, $search);
		return $pos !== false;
	}


	/**
	 * Перевод всей строки в нижний регистр
	 * 'CASE LOWER' -> 'case lower'
	 * @param $str
	 * @return string
	 */
	static public function caseLower($str)
	{
		$str = trim($str);
		$str = mb_convert_case($str, MB_CASE_LOWER, 'UTF-8');
		return $str;
	}

	/**
	 * Перевод всей строки в верхний регистр
	 * 'case up' -> 'CASE UP'
	 * @param string $str
	 * @return string
	 */
	static public function caseUp($str)
	{
		$str = trim($str);
		$str = mb_convert_case($str, MB_CASE_UPPER, 'UTF-8');
		return $str;
	}

	/**
	 * Преобразует в верхний регистр первый символ каждого слова в строке
	 * 'caSe uP' -> 'Case Up'
	 * @param string $str
	 * @return string
	 */
	static public function ucWords($str)
	{
		$str = trim($str);
		$str = mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
		return $str;
	}

	/**
	 * Преобразует первый символ строки в верхний регистр, все остальное в нижний.
	 * 'caSe uP' -> 'Case up'
	 * @param string $str
	 * @return string
	 */
	static public function ucFirst($str)
	{
		$str = trim($str);
		$str = mb_convert_case(mb_substr($str, 0, 1, 'UTF-8'), MB_CASE_UPPER, 'UTF-8') .
			mb_convert_case(mb_substr($str, 1, mb_strlen($str), 'UTF-8'), MB_CASE_LOWER, 'UTF-8');
		return $str;
	}

	/**
	 * Преобразует первый символ строки в верхний регистр.
	 * 'caSe uP' -> 'CaSe uP'
	 * @param string $str
	 * @return string
	 */
	static public function ucFirstOnly($str)
	{
		$str = trim($str);
		$str = (mb_convert_case(mb_substr($str, 0, 1, 'UTF-8'), MB_CASE_UPPER, 'UTF-8') .
			mb_substr($str, 1, mb_strlen($str), 'UTF-8'));
		return $str;
	}

	/**
	 * Обрезка текста по словам
	 *
	 * @param string $text - исходный текст
	 * @param int $length - максимальная длина получаемого текста
	 * @param bool $addDots
	 *
	 * @return string
	 */
	public static function cutText($text, $length, $addDots = false)
	{
		$str = preg_replace('#\~q\~#isUu', '\'', $text);
		$str = strip_tags($str);
		if (mb_strlen($str, 'UTF-8') <= $length) {
			return $text;
		}

		$str = mb_substr($str, 0, $length + 1);
		$str = trim($str, "!,.-_");
		$str = mb_substr($str, 0, mb_strrpos($str, ' '));

		if ($addDots) {
			$addDots = is_bool($addDots) ? ' ...' : $addDots;
			$str = $str . $addDots;
		}

		return $str;
	}
}

