<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180217122634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $pass='$2y$13$nSHPWKM.sp6mv9iosnKLlOcNXVbG/ZJlS0Vy7hzSymGoxZAwC1Q/S';
        $this->addSql("INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES (NULL, 'an21', '$pass', '2'); ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $this->addSql(" DELETE FROM `users` WHERE `users`.`username` = 'an21';");

    }
}
