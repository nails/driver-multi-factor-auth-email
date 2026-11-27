<?php

namespace Nails\MFA\Driver\Authentication\Email\Admin\Permission\Settings;

use Nails\Admin\Interfaces\Permission;

class Settings implements Permission
{
    public function label(): string
    {
        return 'Can manage MFA: Email settings';
    }

    public function group(): string
    {
        return 'Settings';
    }
}
