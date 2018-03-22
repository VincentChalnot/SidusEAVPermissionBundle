<?php
/*
 * This file is part of the Sidus/EAVPermissionBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\EAVPermissionBundle\Security;

/**
 * Lists all permissions attributes
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class Permission
{
    public const LIST = 'list';
    public const READ = 'read';
    public const CREATE = 'create';
    public const EDIT = 'edit';
    public const DELETE = 'delete';
    public const PUBLISH = 'publish';

    /** @var array */
    protected static $permissions = [
        self::LIST,
        self::READ,
        self::CREATE,
        self::EDIT,
        self::DELETE,
        self::PUBLISH,
    ];

    /**
     * @return array
     */
    public static function getPermissions()
    {
        return self::$permissions;
    }
}
