<?php

// controller Manager qui gere l'interface des manager

namespace App\Controllers;


class ManagerController extends BaseController
{
    public function displayInterfaceManager()
    {
        $this->ensureStatus('MANAGER');
        return $this->render('manager/interface');
    }
}