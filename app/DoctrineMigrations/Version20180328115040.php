<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328115040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("CREATE EVENT `delete_keys` ON SCHEDULE EVERY 1 HOUR ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM rkey WHERE rkey.id NOT IN( SELECT rkey FROM lockkey) AND rkey.id NOT IN (SELECT rkey FROM employeekey)");
        $this->addSql("SET global event_scheduler = ON;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("DROP EVENT IF EXISTS `delete_keys` ");
        $this->addSql("SET global event_scheduler = OFF;");

    }
}
