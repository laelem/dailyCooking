<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220917163818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE recipe_ingredient 
            ADD quantity_type_id INT DEFAULT NULL, 
            ADD portion_number SMALLINT DEFAULT NULL, 
            ADD quantity_number SMALLINT DEFAULT NULL, 
            ADD comment VARCHAR(255) DEFAULT NULL
        ');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1336F84596 FOREIGN KEY (quantity_type_id) REFERENCES quantity_type (id)');
        $this->addSql('CREATE INDEX IDX_22D1FE1336F84596 ON recipe_ingredient (quantity_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1336F84596');
        $this->addSql('DROP INDEX IDX_22D1FE1336F84596 ON recipe_ingredient');
        $this->addSql('ALTER TABLE recipe_ingredient DROP quantity_type_id, DROP portion_number, DROP quantity_number, DROP comment');
    }
}
