<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201025125649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE band_user (band_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D6A5361249ABEB17 (band_id), INDEX IDX_D6A53612A76ED395 (user_id), PRIMARY KEY(band_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE band_user ADD CONSTRAINT FK_D6A5361249ABEB17 FOREIGN KEY (band_id) REFERENCES `band` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE band_user ADD CONSTRAINT FK_D6A53612A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE band_user');
    }
}
