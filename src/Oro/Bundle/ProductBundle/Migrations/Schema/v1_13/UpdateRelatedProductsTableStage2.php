<?php

namespace Oro\Bundle\ProductBundle\Migrations\Schema\v1_13;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class UpdateRelatedProductsTableStage2 implements Migration, OrderedMigrationInterface
{
    #[\Override]
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_product_related_products');

        $table->addIndex(['related_item_id'], 'idx_oro_product_related_products_related_item_id', []);
        $table->addUniqueIndex(['product_id', 'related_item_id'], 'idx_oro_product_related_items_unique');

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_product'),
            ['product_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_product'),
            ['related_item_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    #[\Override]
    public function getOrder()
    {
        return 2;
    }
}
