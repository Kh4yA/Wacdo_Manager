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
     * Role verifier qu'un utilisateur et connecter et s'assure qu'il a le role necessaire
     * @param string $statut ( Le statut a verfier )
     * @return string la route de redirection
     * @throws ForbiddenPage Si l'utilisateur n'a pas le statut requis.
     */
    protected function ensureStatus($statut)
    {
        if(!Session::isconnected()){
            return $this->redirectToRoute('/');
        }elseif ( Session::session_userconnect()->get('status') !== $statut) {
            throw new ForbiddenPage();
        }
    }
}
