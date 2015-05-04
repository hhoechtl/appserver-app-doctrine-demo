<?php

namespace Onedrop\Customer\Api\Product\Servlets;

use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * @Route(name="helloWorld", urlPattern={"/helloWorld.do", "/helloWorld.do*"}, initParams={})
 */
class HelloWorldServlet extends HttpServlet
{

    /**
     * The user processor instance.
     *
     * @var \Onedrop\Customer\Api\Product\Services\SampleProcessor
     * @EnterpriseBean(name="SampleProcessor")
     */
    protected $sampleProcessor;

    /**
     * The user processor instance (a SFB instance).
     *
     * @var \Onedrop\Customer\Api\Product\Services\UserProcessor
     * @EnterpriseBean(name="UserProcessor")
     */
    protected $userProcessor;

	/**
	 * Handles a HTTP GET request.
	 *
	 * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface $servletRequest The request instance
	 * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
	 *
	 * @return array|void
	 * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
	 * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doGet()
	 */
    public function doGet(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // check if we've a user logged into the system
        if ($loggedInUser = $this->userProcessor->getUserViewDataOfLoggedIn()) {
            $servletRequest->getContext()->getInitialContext()->getSystemLogger()->info(
                sprintf("Found user logged in: %s", $loggedInUser->getUsername())
            );
        }

        // log the number of samples found in the database
        $servletRequest->getContext()->getInitialContext()->getSystemLogger()->info(
            sprintf("Found %d samples", sizeof($this->userProcessor->findAll()))
        );

        // append the Hello World! to the body stream
		return ['message' => 'Hello World!', 'foo' => 'bar'];
    }

	/**
	 * This method handles a POST request, that will handle the incoming SOAP call.
	 *
	 * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
	 * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
	 *
	 * @return void
	 * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
	 * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doPost()
	 */
	public function doPost(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
	{

	}
}
