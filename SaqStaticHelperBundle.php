<?php

namespace Saq\StaticHelperBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\VarDumper\VarDumper;

/**
 * class: SaqStaticHelperBundle
 * -----------------------------------------------------
 * @date 19.07.2017
 * @author Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package Saq\StaticHelperBundle
 */
class SaqStaticHelperBundle extends Bundle
{
	private static $containerInstance = null;

	public function setContainer(ContainerInterface $container = null)
	{
		parent::setContainer($container);
		self::$containerInstance = $container;
	}

	/**
	 * @return ContainerInterface
	 */
	public static function getContainer()
	{
		return self::$containerInstance;
	}

	/**
	 * Хелпер Дамепра от Symfony, передавать можно любое количество парметров
	 * Оборнут в exit , для остановки дальнейшего вывода кода
	 * @param $params
	 */
	public static function dumpExit(...$params)
	{
		$params = (count($params) == 1) ? $params[0] : $params;
		exit(VarDumper::dump($params));
	}
}
