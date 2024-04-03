<?php
/**
 *  Test-service to add and remove properties
 * 
 *  @author JacobSeated
 * 
 */
declare(strict_types=1);

namespace OrionLabels\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class PropertyHandlerService
{
    private EntityRepository $propertyGroupRepository;
    private SystemConfigService $systemConfigService;

    public function __construct(EntityRepository $propertyGroupRepository, SystemConfigService $systemConfigService)
    {
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->systemConfigService = $systemConfigService;
    }

    public function createOrionProperties()
    {
        $context = $this->createContext();

        if (null === $this->systemConfigService->get('OrionLabels.config.OrionLabelsPropertyGroupId')) {
            $groupId = Uuid::randomHex();
            $discountId = Uuid::randomHex();
            $this->systemConfigService->set('OrionLabels.config.OrionLabelsPropertyGroupId', $groupId);
            $this->systemConfigService->set('OrionLabels.config.OrionLabelsPropertyDiscountId', $discountId);
        } else {
            $groupId = $this->systemConfigService->get('OrionLabels.config.OrionLabelsPropertyGroupId');
            $discountId = $this->systemConfigService->get('OrionLabels.config.OrionLabelsPropertyDiscountId');

            // If the groupId was already created, perhaps from previous installation, verrify the group still exists
            // and if not, re-create it
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('id', $groupId));
            if (null !== ($this->propertyGroupRepository->search($criteria, $context))->first()) {
                return;
            }
            $groupId = Uuid::randomHex();
            $discountId = Uuid::randomHex();
        }

        // Only create the group if it does not exist
        $this->propertyGroupRepository->create([
            [
                'id' => $groupId,
                'name' => 'OrionLabels',
                'options' => [[
                    'id' => $discountId,
                    'name' => 'OnDiscount'
                ]]
            ]
        ], $context);
    }

    /**
     * Create a new context based on language and default values
     * @param string $languageId 
     * @return Context 
     */
    private function createContext(string $languageId = Defaults::LANGUAGE_SYSTEM)
    {
        return new Context(
            new SystemSource(),
            [],
            Defaults::CURRENCY,
            [$languageId],
        );
    }
}
