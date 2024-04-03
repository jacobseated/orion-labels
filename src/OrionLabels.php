<?php declare(strict_types=1);
/**
 *  Example Plugin to allow adding labels to products using the "OrionLabels" property group.
 *  The plugin automatically creates a property and group that can then be added to whatever products.
 *  
 *  The label is added visually by extending box-standard.html.twig. Not ideal, but this is just for testing purposes anyway..
 * 
 *  @author JacobSeated
 * 
 */

namespace OrionLabels;

use Shopware\Core\Framework\Plugin;

use OrionLabels\installer\OrionLabelsInstaller;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;

class OrionLabels extends Plugin
{

    public function activate(ActivateContext $activateContext): void
    {
        $installer = new OrionLabelsInstaller($this->container);
        $installer->install();
    }
}