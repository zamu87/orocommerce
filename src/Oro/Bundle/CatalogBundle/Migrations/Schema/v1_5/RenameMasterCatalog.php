<?php

namespace Oro\Bundle\CatalogBundle\Migrations\Schema\v1_5;

use Oro\Bundle\MigrationBundle\Migration\ArrayLogger;
use Oro\Bundle\MigrationBundle\Migration\ParametrizedMigrationQuery;
use Psr\Log\LoggerInterface;

class RenameMasterCatalog extends ParametrizedMigrationQuery
{
    #[\Override]
    public function getDescription()
    {
        $logger = new ArrayLogger();
        $this->processQueries($logger, true);

        return $logger->getMessages();
    }

    #[\Override]
    public function execute(LoggerInterface $logger)
    {
        $this->processQueries($logger);
    }

    /**
     * @param LoggerInterface $logger
     * @param bool $dryRun
     */
    protected function processQueries(LoggerInterface $logger, $dryRun = false)
    {
        $localizedValueId = $this->getLocalizedValueId($logger);
        if (!$localizedValueId) {
            return;
        }

        $query = 'UPDATE oro_fallback_localization_val SET string = ? WHERE string = ? AND id = ?';
        $parameters = ['Products categories', 'Master catalog', $localizedValueId];

        $this->logQuery($logger, $query, $parameters);
        if (!$dryRun) {
            $this->connection->executeStatement($query, $parameters);
        }
    }

    /**
     * @param LoggerInterface $logger
     * @return array
     */
    protected function getLocalizedValueId(LoggerInterface $logger)
    {
        $sql = 'SELECT ct.localized_value_id as id
                FROM oro_catalog_category_title AS ct
                INNER JOIN oro_catalog_category c ON c.id = ct.category_id AND c.parent_id IS NULL
                LIMIT 1';

        $this->logQuery($logger, $sql);

        $rows = $this->connection->fetchAllAssociative($sql);

        return $rows ? $rows[0]['id'] : null;
    }
}
