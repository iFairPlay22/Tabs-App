<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715195323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE band SET created_at = NULL WHERE 1');
        $this->addSql('UPDATE song SET created_at = NULL WHERE 1');
        $this->addSql('UPDATE user SET created_at = NULL WHERE 1');
        $this->addSql('UPDATE band SET updated_at = NULL WHERE 1');
        $this->addSql('UPDATE song SET updated_at = NULL WHERE 1');
        $this->addSql('UPDATE user SET updated_at = NULL WHERE 1');
        $this->addSql('ALTER TABLE band CHANGE created_at created_at INT DEFAULT NULL, CHANGE updated_at updated_at INT DEFAULT NULL');
        $this->addSql('ALTER TABLE song CHANGE created_at created_at INT DEFAULT NULL, CHANGE updated_at updated_at INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at INT DEFAULT NULL, CHANGE updated_at updated_at INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `band` CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `song` CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
