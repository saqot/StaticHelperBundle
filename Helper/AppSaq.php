<?php

namespace Saq\StaticHelperBundle\Helper;

use Saq\StaticHelperBundle\SaqStaticHelperBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\VarDumper\VarDumper;

/**
 * class:  AppSaq
 * -----------------------------------------------------
 * @author  Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\StaticHelperBundle\Helper
 * -----------------------------------------------------
 * 03.08.2017
 */
class AppSaq 
{
	private static $oContainer;
	private static $oTranslator;
	private static $oDoctrine;
	private static $oEntityManager;
	private static $oRequestStack;

	/**
	 * @return ContainerInterface
	 */
	public static function getContainer()
	{
		if (!self::$oContainer) {
			self::$oContainer = SaqStaticHelperBundle::getContainer();
		}
		return self::$oContainer;
	}

	/**
	 * Получение значений из параметров
	 * @param string $name
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public static function getParameter($name)
	{
		return self::getContainer()->getParameter($name);
	}

	/**
	 * @return IdentityTranslator
	 * @throws \LogicException
	 */
	public static function getTranslator()
	{
		if (!self::$oTranslator) {
			if (!self::getContainer()->has('translator')) {
				throw new \LogicException('The translator is not found');
			}
			self::$oTranslator = self::getContainer()->get('translator');
		}
		return self::$oTranslator;
	}

	/**
	 * @return Registry
	 * @throws \LogicException
	 */
	public static function getDoctrine()
	{
		if (!self::$oDoctrine) {
			if (!self::getContainer()->has('doctrine')) {
				throw new \LogicException('The DoctrineBundle is not registered');
			}
			self::$oDoctrine = self::getContainer()->get('doctrine');
		}

		return self::$oDoctrine;
	}

	/**
	 * @param      $objectName
	 * @param null $managerName
	 * @return ObjectRepository
	 */
	public static function getRepository($objectName, $managerName = null)
	{
		return self::getDoctrine()->getRepository($objectName, $managerName);
	}

	/**
	 * @param null $name
	 * @return EntityManager
	 */
	public static function em($name = null)
	{
		$key = $name ? $name : 'default';

		if (empty(self::$oEntityManager[$key])) {
			self::$oEntityManager[$key] = self::getDoctrine()->getManager($name);
		}

		return self::$oEntityManager[$key];
	}

	/**
	 * @return object|RequestStack
	 * @throws \LogicException
	 */
	public static function getRequestStack()
	{
		if (!self::$oRequestStack) {
			if (!self::getContainer()->has('request_stack')) {
				throw new \LogicException('The request_stack is not found');
			}
			self::$oRequestStack = self::getContainer()->get('request_stack');
		}

		return self::$oRequestStack;
	}


	/**
	 * Хелпер Дамепра от Symfony, передавать можно любое количество парметров
	 * @param $params
	 * @return mixed
	 */
	public static function dump(...$params)
	{
		if ($params) {
			$params = (count($params) <= 1) ? $params[0] : $params;
		} else {
			$params = '';
		}
		return VarDumper::dump($params);
	}

	/**
	 * Хелпер Дамепра от Symfony, передавать можно любое количество парметров
	 * Оборнут в exit , для остановки дальнейшего вывода кода
	 * @param $params
	 */
	public static function dumpExit(...$params)
	{
		exit(self::dump($params));
	}
}

