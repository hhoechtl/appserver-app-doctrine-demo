<?php

namespace Onedrop\Customer\Api\Product\Servlets;

use AppserverIo\Psr\Servlet\Http\HttpServlet;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use Onedrop\Customer\App\Common\Entities\Category\Category;

/**
 * @Route(name="category", urlPattern={"/category.do", "/category.do*"}, initParams={})
 */
class CategoryServlet extends HttpServlet
{

    /**
     * The user processor instance.
     *
     * @var \Onedrop\Customer\Api\Product\Services\CategoryProcessor
     * @EnterpriseBean(name="CategoryProcessor")
     */
    protected $categoryProcessor;

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

        $categories = $this->categoryProcessor->findAll();
        $categoryArray = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $categoryArray[] = [
                'id' => $category->getId(),
                'parent' => ($category->getParent()) ? $category->getParent()->getId() : 0,
                'title' => $category->getTitle()
            ];
        }

        // append the Hello World! to the body stream
		return ['categories' => $categoryArray];
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
