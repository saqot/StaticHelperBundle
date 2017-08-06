<?php

namespace Saq\StaticHelperBundle\Helper;

use Monolog\Logger;
use Saq\StaticHelperBundle\SaqStaticHelperBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\VarDumper\VarDumper;
use Twig_Environment;

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
	private static $oLogger;
	private static $oKernel;
	private static $oSession;
	private static $oTwig;
	private static $oContainer;
	private static $oTranslator;
	private static $oDoctrine;
	private static $oEntityManager;
	private static $oRequestStack;

	/**
	 * @return KernelInterface|Kernel
	 */
	public static function getKernel()
	{
		if (!self::$oKernel) {
			if (!self::getContainer()->has('kernel')) {
				throw new \LogicException('The KernelInterface is not found');
			}
			self::$oKernel = self::getContainer()->get('kernel');
		}

		return self::$oKernel;
	}

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
	 * @return Twig_Environment
	 */
	public static function getTwig()
	{
		if (!self::getContainer()->has('twig')) {
			throw new \LogicException('The twig is not found');
		}
		if (!self::$oTwig) {
			self::$oTwig = self::getContainer()->get('twig');
		}

		return self::$oTwig;
	}

	/**
	 * @return Session
	 */
	public static function getSession()
	{
		if (!self::$oSession) {
			if (!self::getContainer()->has('Session')) {
				throw new \LogicException('The Session is not found');
			}
			self::$oSession = self::getContainer()->get('session');
		}

		return self::$oSession;
	}

	/**
	 * @return Logger
	 */
	public static function getLogger()
	{
		if (!self::$oLogger) {
			if (!self::getContainer()->has('logger')) {
				throw new \LogicException('The Monolog/Logger is not found');
			}
			self::$oLogger = self::getContainer()->get('logger');
		}

		return self::$oLogger;
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

