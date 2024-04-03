<?php declare(strict_types=1);
/**
 * 
 *  Installer to handle setup tasks. E.g. Creating the database table if needed
 * 
 *  @author JacobSeated
 */


namespace OrionLabels\installer;

use \OrionLabels\Service\PropertyHandlerService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OrionLabelsInstaller {

   private ContainerInterface $container;

   function __construct(ContainerInterface $container) {
        $this->container = $container;
   }

   public function install() {
      /** @var entityRepository $propertyGroupRepository */
      $propertyGroupRepository = $this->container->get('property_group.repository');

      /** @var SystemConfigService $systemConfigService */
      $systemConfigService = $this->container->get(SystemConfigService::class);

      $propertyHandlerService = new PropertyHandlerService($propertyGroupRepository, $systemConfigService);
      $propertyHandlerService->createOrionProperties();
   }

}