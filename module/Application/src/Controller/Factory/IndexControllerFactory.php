<?php
/**
 * User: Alice in wonderland
 * Date: 31.05.2017
 * Time: 14:55
 */

namespace Application\Controller\Factory;


use Application\Controller\IndexController;
use Application\Service\MailSender;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null)
    {
        $mailSender = $serviceManager->get(MailSender::class);

        // Instantiate the controller and inject dependencies
        $indexController =  new IndexController($mailSender);
        return $indexController;
    }
}