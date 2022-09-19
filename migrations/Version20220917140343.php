<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220917140343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ADD default_quantity_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870B9058DC1 FOREIGN KEY (default_quantity_type_id) REFERENCES quantity_type (id)');
        $this->addSql('CREATE INDEX IDX_6BAF7870B9058DC1 ON ingredient (default_quantity_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870B9058DC1');
        $this->addSql('DROP INDEX IDX_6BAF7870B9058DC1 ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP default_quantity_type_id');
    }
}
