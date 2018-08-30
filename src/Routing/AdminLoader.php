<?php

namespace CleverCoders\Bundle\AdminBundle\Routing;

use CleverCoders\Bundle\AdminBundle\Admin\AdminInterface;
use CleverCoders\Bundle\AdminBundle\Controller\AdminController;
use CleverCoders\Bundle\AdminBundle\Manager\AdminManagerInterface;
use CleverCoders\Bundle\AdminBundle\Model\RouteType;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Inflector\Inflector;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class AdminLoader
 */
class AdminLoader extends Loader
{
    const TYPE = 'clever_admin';

    /**
     * @var AdminManagerInterface
     */
    private $manager;

    /**
     * @var bool
     */
    private $isLoaded = false;


    /**
     * AdminLoader constructor.
     * @param AdminManagerInterface $manager
     */
    public function __construct(AdminManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $routes = new RouteCollection();
        foreach ($this->manager->all() as $class => $admin) {
            $this->buildRoutes($routes, $admin);
        }

        $this->isLoaded = true;

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return self::TYPE === $type;
    }

    /**
     * @param RouteCollection $routeCollection
     * @param AdminInterface  $admin
     */
    private function buildRoutes(RouteCollection $routeCollection, AdminInterface $admin): void
    {
        $this
            ->buildIndexRoute($routeCollection, $admin)
            ->buildCreateRoute($routeCollection, $admin)
            ->buildUpdateRoute($routeCollection, $admin)
            ->buildDeleteRoute($routeCollection, $admin)
        ;
    }

    /**
     * @param RouteCollection $routeCollection
     * @param AdminInterface  $admin
     * @return AdminLoader
     */
    private function buildIndexRoute(RouteCollection $routeCollection, AdminInterface $admin): AdminLoader
    {
        $rootPath = $admin->getRootPath();
        $defaults = $this->getDefaults($admin, [
            '_controller' => sprintf('%s::indexAction', AdminController::class),
        ]);
        $indexRoute = new Route($rootPath, $defaults);
        $routeCollection->add($this->generateAdminName($rootPath, RouteType::INDEX, true), $indexRoute);

        return $this;
    }

    /**
     * @param RouteCollection $routeCollection
     * @param AdminInterface  $admin
     * @return AdminLoader
     */
    private function buildCreateRoute(RouteCollection $routeCollection, AdminInterface $admin): AdminLoader
    {
        $path = sprintf('%s/create', $admin->getRootPath());
        $defaults = $this->getDefaults($admin, [
            '_controller' => sprintf('%s::createAction', AdminController::class),
        ]);
        $createRoute = new Route($path, $defaults);
        $createRoute->setMethods([Request::METHOD_GET, Request::METHOD_POST]);
        $routeCollection->add($this->generateAdminName($path, RouteType::CREATE, false), $createRoute);

        return $this;
    }

    /**
     * @param RouteCollection $routeCollection
     * @param AdminInterface  $admin
     * @return AdminLoader
     */
    private function buildUpdateRoute(RouteCollection $routeCollection, AdminInterface $admin): AdminLoader
    {
        $path = sprintf('%s/create', $admin->getRootPath());
        $defaults = $this->getDefaults($admin, [
            '_controller' => sprintf('%s::updateAction', AdminController::class),
        ]);
        $createRoute = new Route($path, $defaults);
        $createRoute->setMethods([Request::METHOD_GET, Request::METHOD_PUT]);
        $routeCollection->add($this->generateAdminName($path, RouteType::UPDATE, false), $createRoute);

        return $this;
    }

    /**
     * @param RouteCollection $routeCollection
     * @param AdminInterface  $admin
     * @return AdminLoader
     */
    private function buildDeleteRoute(RouteCollection $routeCollection, AdminInterface $admin): AdminLoader
    {
        $path = sprintf('%s/create', $admin->getRootPath());
        $defaults = $this->getDefaults($admin, [
            '_controller' => sprintf('%s::deleteAction', AdminController::class),
        ]);
        $createRoute = new Route($path, $defaults);
        $createRoute->setMethods([Request::METHOD_DELETE]);
        $routeCollection->add($this->generateAdminName($path, RouteType::DELETE, false), $createRoute);

        return $this;
    }

    /**
     * @param AdminInterface $admin
     * @param array          $defaults
     * @return array
     */
    private function getDefaults(AdminInterface $admin, array $defaults): array
    {
        return array_merge([
            '_admin' => get_class($admin),
        ], $defaults);
    }


    /**
     * @param string $path
     * @param string $type
     * @param bool   $isCollectionRoute
     *
     * @return string
     */
    private function generateAdminName(string $path, string $type, bool $isCollectionRoute): string
    {
        if (false === $isCollectionRoute) {
            $rootParts = explode('/', $path);
            $resource  = array_pop($rootParts);
            $namePartial = strtolower(implode('_', $rootParts));
            $namePartial .= (!empty($namePartial) ? '_' : '').Inflector::singularize($resource);
        } else {
            $namePartial = str_replace('/', '_', strtolower($path));
        }

        return sprintf('admin_%s_%s', $namePartial, $type);
    }
}
