<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180216115948 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
       $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
       $this->addSql("INSERT INTO `user_types` (`id`, `type`) VALUES (NULL, 'ROLE_ADMIN'), (NULL, 'ROLE_KEY_ADDER'), (NULL, 'ROLE_USER'), (NULL, 'ROLE_LOCK_ADDER'); ");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    
	$this->addSql(" SET FOREIGN_KEY_CHECKS = 0;");
	$this->addSql("TRUNCATE `user_types`;");
	$this->addSql(" SET FOREIGN_KEY_CHECKS = 1;");
    }
}
