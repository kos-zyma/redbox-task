<?php
namespace Redbox\Linkedin\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface    $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $quoteItemTable = $installer->getTable('quote');
        $salesOrderItemTable = $installer->getTable('sales_order');

        $columns = [
            'linkedin_profile' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'comment' => 'Linkedin Profile',
            ],
        ];

        $connection = $installer->getConnection();
        foreach ($columns as $name => $definition) {
            $connection->addColumn($quoteItemTable, $name, $definition);
            $connection->addColumn($salesOrderItemTable, $name, $definition);
        }

        $installer->endSetup();
    }
}