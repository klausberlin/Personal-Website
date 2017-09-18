<?php
/**
 * User: Alice in wonderland
 * Date: 15.06.2017
 * Time: 19:32
 */

namespace Application\Service\Factory;


use Application\Service\AuthAdapter;
use Application\Service\AuthManager;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as Adapter;

class AuthenticationServiceFactory
{

    public function __invoke(ContainerInterface $sm, $requestedName, array $options = null)
    {

        //create the service

        //get the database connection
        $dbAdapter = $sm->get('db_connection');

        //set the Adapter, the table user and the needed parameters for the authentication
        $authAdapter = new Adapter(
            $dbAdapter,
            'user',
            'username',
            'password',
            "SHA1(?)"
        );

        // Instantiate the AuthManager service and inject dependencies to its constructor.
        return new AuthenticationService(null,$authAdapter);
    }
}