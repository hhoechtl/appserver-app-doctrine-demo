<?php

namespace Onedrop\Customer\Api\Product\Servlets;

use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Demo servlet that produces random processing times.
 */
class RandomRuntimeServlet extends HttpServlet
{

    /**
     * Handles a HTTP GET request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return void
     * @throws \AppserverIo\Psr\Servlet\ServletException Is thrown if the request method is not implemented
     * @see \AppserverIo\Psr\Servlet\Http\HttpServlet::doGet()
     */
    public function doGet(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // sleep for a random time between 10 ms - 1000 ms (=== 1 sec)
        usleep($sleepFor = rand(10000, 1000000));

        // append the processing time
        $servletResponse->appendBodyStream('Slept for: ' . $sleepFor);
    }
}
