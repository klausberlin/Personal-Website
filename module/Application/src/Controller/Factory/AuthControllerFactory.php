<?php
/**
 * User: Alice in wonderland
 * Date: 31.05.2017
 * Time: 14:55
 */

namespace Application\Controller\Factory;


use Application\Controller\AuthController;
use Application\Service\AuthManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $sm, $requestedName, array $options = null)
    {
        /*$entityManager = $container->get('doctrine.entitymanager.orm_default');*/
        $authManager = $sm->get(AuthManager::class);
        /* $authService = $container->get(\Zend\Authentication\AuthenticationService::class);*/

        return new AuthController($authManager);
    }
}