<?php

namespace App\Controllers;

use App\Exceptions\ForbiddenPage;
use App\Utils\Session;

class BaseController
{
    /**
     * role : gere le rendu de la vue
     * @param string $viewPath ( chemin a utiliser )
     * @param array $data ( tableau associatif des donnée a passer a la vue )
     * @return string string
     */
    public function render($viewPath, $data = []): string
    {
        ob_start();
        extract($data);
        require VIEW_PATH . $viewPath . ".php";
        return ob_get_clean();
    }
    /**
     * Role : rediriger une vers une route
     * @param $viewPath ( chemin a utiliser )
     * @return string
     */
    public function redirectToRoute($viewPath): void
    {
        header("Location: /{$viewPath}");
    }
    /**
     * Vérifie qu'un utilisateur est connecté et s'assure qu'il a un des statuts requis.
     * @param array|string $statuts (Le ou les statuts à vérifier)
     * @return string la route de redirection
     * @throws ForbiddenPage Si l'utilisateur n'a pas un des statuts requis.
     */
    protected function ensureStatus($statuts)
    {
        if (!Session::isconnected()) {
            return $this->redirectToRoute('/');
        }
        if (!is_array($statuts)) {
            $statuts = [$statuts];
        }
        if (!in_array(Session::session_userconnect()->get('status'), $statuts)) {
            throw new ForbiddenPage();
        }
    }
        /**
     * Définit les en-têtes CORS pour autoriser l'accès à l'API.
     */
    protected function CORSHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

}
