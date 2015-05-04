<?php
 /***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Hans Höchtl <jhoechtl@gmail.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Onedrop\Customer\Api\Product\Aspects;


use AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface;

/**
 * @Aspect
 */
class JsonHandlingAspect {
	/**
	 * @Pointcut("call(\Onedrop\Customer\Api\Product\Servlets\*Servlet->do*())")
	 */
	public function allServletDoMethods() {}

	/**
	 * @Around("pointcut(allServletDoMethods())")
	 */
	public function jsonHandlingAdvice(MethodInvocationInterface $methodInvocation)
	{
		// get servlet method params to local refs
		$parameters = $methodInvocation->getParameters();
		/** @var \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface $servletRequest */
		$servletRequest = $parameters['servletRequest'];
		/** @var \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse */
		$servletResponse = $parameters['servletResponse'];

		// try to handle request processing
		try {
			// only if request has valid json
			echo $servletRequest->getMethod();
			if ($servletRequest->getMethod() !== 'GET') {
				if (!is_object(json_decode($servletRequest->getBodyContent()))) {
					throw new \Exception('Invalid request format', 400);
				}
				// set json parsed object into data property of servlet object
				$methodInvocation->getContext()->data = json_decode(
					$servletRequest->getBodyContent()
				);
			}
			// call orig function
			$responseJsonObject = $methodInvocation->proceed();
		} catch(\Exception $e) {
			$servletResponse->setStatusCode(
				$e->getCode() ? $e->getCode() : 400
			);
			// create error json response object
			$responseJsonObject = new \stdClass();
			$responseJsonObject->error = new \stdClass();
			$responseJsonObject->error->message = nl2br($e->getMessage());
		}
		// add json encoded string to response body stream
		$servletResponse->appendBodyStream(json_encode($responseJsonObject));
	}
}
