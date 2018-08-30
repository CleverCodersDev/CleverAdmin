<?php

namespace CleverCoders\Bundle\AdminBundle\Manager;

use CleverCoders\Bundle\AdminBundle\Admin\AdminInterface;
use phootwork\collection\Map;

/**
 * Class AdminManager
 */
class AdminManager implements AdminManagerInterface
{
    /**
     * @var Map
     */
    private $registry;


    /**
     * AdminManager constructor.
     */
    public function __construct()
    {
        $this->registry = new Map();
    }

    /**
     * @param AdminInterface $admin
     * @return AdminManagerInterface
     */
    public function register(AdminInterface $admin): AdminManagerInterface
    {
        $this->registry->set(get_class($admin), $admin);

        return $this;
    }

    /**
     * @return Map
     */
    public function all(): Map
    {
        return $this->registry;
    }
}
