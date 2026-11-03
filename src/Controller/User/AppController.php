<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\AppController as BaseAppController;

/**
 * Shared presentation for the User area. Access control is added separately.
 */
class AppController extends BaseAppController
{
    /**
     * Initialize the User layout.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('user');
    }
}
