<?php

namespace CleverCoders\Bundle\AdminBundle\Admin;

/**
 * Interface AdminInterface
 */
interface AdminInterface
{
    /**
     * Entity class this admin intends to manage
     *
     * @return string
     */
    public function getEntityClass(): string;

    /**
     * Corresponds with admin form class
     *
     * @return string
     */
    public function getFormClass(): string;

    /**
     * Root url path to this admin resource
     *
     * @return string
     */
    public function getRootPath(): string;

    /**
     * Title of this admin
     *
     * @return string
     */
    public function getTitle(): string;
}
