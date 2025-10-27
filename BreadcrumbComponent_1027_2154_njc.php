<?php
// 代码生成时间: 2025-10-27 21:54:46
namespace App\Component;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class BreadcrumbComponent
{
    private $requestStack;
    private $router;
    private $breadcrumbs;

    /**
     * Constructor for BreadcrumbComponent
     *
     * @param RequestStack $requestStack
     * @param RouterInterface $router
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->breadcrumbs = new ParameterBag();
    }

    /**
     * Adds a breadcrumb to the trail
     *
     * @param string $name The name of the breadcrumb
     * @param string $route The route associated with the breadcrumb
     * @param array $params The parameters for the route
     *
     * @return void
     */
    public function add($name, $route, $params = [])
    {
        $this->breadcrumbs->set($route, ['name' => $name, 'params' => $params]);
    }

    /**
     * Builds the breadcrumb trail
     *
     * @return array The breadcrumb trail
     */
    public function build()
    {
        $trail = [];
        $request = $this->requestStack->getCurrentRequest();
        $path = $request->getPathInfo();

        while ($path) {
            \$params = \$this->router->match($path);
            \$route = key(\$params);

            if (\$this->breadcrumbs->has($route)) {
                \$trail[] = \$this->breadcrumbs->get($route);
                $path = isset(\$params['_parent']) ? \$params['_parent'] : '';
            } else {
                break;
            }
        }

        return \$trail;
    }

    /**
     * Renders the breadcrumb trail as HTML
     *
     * @return string The HTML representation of the breadcrumb trail
     */
    public function render()
    {
        \$trail = \$this->build();
        \$html = '<nav aria-label="Breadcrumb"><ol class="breadcrumb">';

        foreach (\$trail as \$item) {
            \$link = \$this->router->generate(\$item['route'], \$item['params']);
            \$html .= '<li class="breadcrumb-item"><a href="' . \$link . '">' . \$item['name'] . '</a></li>';
        }

        \$html .= '</ol></nav>';

        return \$html;
    }
}
