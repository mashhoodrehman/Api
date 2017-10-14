<?php

namespace App\Traits;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class UserAccess.
 */
trait UserAccess
{
    /**
     * Checks if the user has a Role by its name or id.
     *
     * @param string $nameOrId Role name or id.
     *
     * @return bool
     */
    public function getUser($id)
    {
        $user = $JWTAuth->toUser($id);
        return $user;
    }

    
}
