<?php
namespace Extasy\API\Infrastructure\Service;

interface UsersLoginServiceInterface
{
    /**
     * @return bool
     */
    public function isLogined( );


    public function getCurrentSession();
}