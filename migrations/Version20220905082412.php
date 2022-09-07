<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905082412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE tag (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE ingredient_tag (
                id INT AUTO_INCREMENT NOT NULL,
                ingredient_id INT NOT NULL,
                tag_id INT NOT NULL,
                INDEX IDX_8CC02FFB933FE08C (ingredient_id),
                INDEX IDX_8CC02FFBBAD26311 (tag_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE ingredient_tag ADD CONSTRAINT FK_8CC02FFB933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE ingredient_tag ADD CONSTRAINT FK_8CC02FFBBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE ingredient ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787012469DE2 FOREIGN KEY (category_id) REFERENCES ingredient_category (id)');
        $this->addSql('CREATE INDEX IDX_6BAF787012469DE2 ON ingredient (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_tag DROP FOREIGN KEY FK_8CC02FFB933FE08C');
        $this->addSql('ALTER TABLE ingredient_tag DROP FOREIGN KEY FK_8CC02FFBBAD26311');
        $this->addSql('DROP TABLE ingredient_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787012469DE2');
        $this->addSql('DROP INDEX IDX_6BAF787012469DE2 ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP category_id');
    }
}
