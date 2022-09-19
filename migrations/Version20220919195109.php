<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919195109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE recipe_step (
                id INT AUTO_INCREMENT NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                description TEXT NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE ingredient_category CHANGE position position DOUBLE PRECISION UNSIGNED NOT NULL');
        $this->addSql('
            ALTER TABLE recipe 
            DROP steps, 
            CHANGE default_portion_number default_portion_number SMALLINT UNSIGNED DEFAULT NULL
        ');
        $this->addSql('ALTER TABLE recipe_ingredient CHANGE portion_number portion_number SMALLINT UNSIGNED DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recipe_step');
        $this->addSql('ALTER TABLE recipe ADD steps TEXT DEFAULT NULL, CHANGE default_portion_number default_portion_number SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient_category CHANGE position position DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredient CHANGE portion_number portion_number SMALLINT DEFAULT NULL');
    }
}
