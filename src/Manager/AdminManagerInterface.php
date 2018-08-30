<?php

namespace CleverCoders\Bundle\AdminBundle\Manager;

use CleverCoders\Bundle\AdminBundle\Admin\AdminInterface;
use phootwork\collection\Map;

/**
 * Class AdminManager
 */
interface AdminManagerInterface
{
    /**
     * Register a new admin
     *
     * @param AdminInterface $admin
     */
    public function register(AdminInterface $admin);

    /**
     * Return all admin classes registered
     *
     * @return Map
     */
    public function all(): Map;
}