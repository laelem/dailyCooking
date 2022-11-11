<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106093615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_ingredient_portion_number (
            id INT AUTO_INCREMENT NOT NULL, 
            recipe_ingredient_id INT NOT NULL, 
            quantity_type_id INT DEFAULT NULL, 
            portion_number SMALLINT UNSIGNED DEFAULT NULL, 
            quantity_number NUMERIC(6, 2) UNSIGNED DEFAULT NULL, 
            comment VARCHAR(255) DEFAULT NULL, 
            INDEX IDX_A6C620063CAF64A (recipe_ingredient_id), 
            INDEX IDX_A6C6200636F84596 (quantity_type_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE recipe_ingredient_portion_number 
            ADD CONSTRAINT FK_A6C620063CAF64A FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe_ingredient (id)
        ');

        $this->addSql('ALTER TABLE recipe_ingredient_portion_number 
            ADD CONSTRAINT FK_A6C6200636F84596 FOREIGN KEY (quantity_type_id) REFERENCES quantity_type (id)
        ');

        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1336F84596');

        $this->addSql('DROP INDEX IDX_22D1FE1336F84596 ON recipe_ingredient');

        $this->addSql('ALTER TABLE recipe_ingredient 
            DROP quantity_type_id, 
            DROP portion_number, 
            DROP quantity_number, 
            DROP comment
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredient_portion_number DROP FOREIGN KEY FK_A6C620063CAF64A');
        $this->addSql('ALTER TABLE recipe_ingredient_portion_number DROP FOREIGN KEY FK_A6C6200636F84596');
        $this->addSql('DROP TABLE recipe_ingredient_portion_number');
        $this->addSql('ALTER TABLE recipe_ingredient ADD quantity_type_id INT DEFAULT NULL, ADD portion_number SMALLINT UNSIGNED DEFAULT NULL, ADD quantity_number NUMERIC(6, 2) UNSIGNED DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1336F84596 FOREIGN KEY (quantity_type_id) REFERENCES quantity_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_22D1FE1336F84596 ON recipe_ingredient (quantity_type_id)');
    }
}
