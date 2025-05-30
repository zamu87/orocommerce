<?php

namespace Oro\Bundle\ProductBundle\Migrations\Schema\v1_34;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityConfigBundle\Entity\ConfigModel;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\ExtendOptionsManager;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Adds the column for SearchTerm::$productCollectionSegment field
 */
class AddProductCollectionSegmentToSearchTerm implements Migration, ExtendExtensionAwareInterface
{
    private ExtendExtension $extendExtension;

    #[\Override]
    public function setExtendExtension(ExtendExtension $extendExtension): void
    {
        $this->extendExtension = $extendExtension;
    }

    #[\Override]
    public function up(Schema $schema, QueryBag $queries): void
    {
        $owningSideTable = $schema->getTable('oro_website_search_search_term');
        $associationName = 'productCollectionSegment';
        $relationName = $this->extendExtension->getNameGenerator()->generateRelationColumnName($associationName, '_id');
        if ($owningSideTable->hasColumn($relationName)) {
            return;
        }

        $inverseSideTable = $schema->getTable('oro_segment');

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $owningSideTable,
            $associationName,
            $inverseSideTable,
            'id',
            [
                ExtendOptionsManager::MODE_OPTION => ConfigModel::MODE_READONLY,
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'without_default' => true,
                    'on_delete' => 'SET NULL',
                ],
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_FALSE],
                'view' => ['is_displayable' => false],
                'form' => ['is_enabled' => false],
            ]
        );
    }
}
