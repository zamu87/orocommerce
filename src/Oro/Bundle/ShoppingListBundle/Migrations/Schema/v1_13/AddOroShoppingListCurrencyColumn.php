<?php

namespace Oro\Bundle\ShoppingListBundle\Migrations\Schema\v1_13;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\ShoppingListBundle\Entity\ShoppingList;

/**
 * Adds "currency" column for {@see ShoppingList::$currency} field
 * to save the currency that was used during shopping list creation.
 */
class AddOroShoppingListCurrencyColumn implements Migration, OrderedMigrationInterface
{
    #[\Override]
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_shopping_list');

        if (!$table->hasColumn('currency')) {
            $table->addColumn('currency', 'string', ['notnull' => false, 'length' => 3]);
        }
    }

    #[\Override]
    public function getOrder()
    {
        return 1;
    }
}
