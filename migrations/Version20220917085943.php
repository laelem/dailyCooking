<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220917085943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (
          id INT AUTO_INCREMENT NOT NULL,
          category_id INT NOT NULL,
          name VARCHAR(255) NOT NULL,
          where_to_keep VARCHAR(50) DEFAULT NULL,
          INDEX IDX_6BAF787012469DE2 (category_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ingredient_ingredient_tag (
          ingredient_id INT NOT NULL,
          ingredient_tag_id INT NOT NULL,
          INDEX IDX_E277767A933FE08C (ingredient_id),
          INDEX IDX_E277767A56A8A350 (ingredient_tag_id),
          PRIMARY KEY(
            ingredient_id, ingredient_tag_id
          )
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ingredient_category (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(255) NOT NULL,
          position DOUBLE PRECISION NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ingredient_tag (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE recipe (
          id INT AUTO_INCREMENT NOT NULL,
          title VARCHAR(255) NOT NULL,
          steps TEXT NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE recipe_ingredient (
          id INT AUTO_INCREMENT NOT NULL,
          recipe_id INT NOT NULL,
          ingredient_id INT NOT NULL,
          INDEX IDX_22D1FE1359D8A214 (recipe_id),
          INDEX IDX_22D1FE13933FE08C (ingredient_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE messenger_messages (
          id BIGINT AUTO_INCREMENT NOT NULL,
          body LONGTEXT NOT NULL,
          headers LONGTEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at DATETIME NOT NULL,
          available_at DATETIME NOT NULL,
          delivered_at DATETIME DEFAULT NULL,
          INDEX IDX_75EA56E0FB7336F0 (queue_name),
          INDEX IDX_75EA56E0E3BD61CE (available_at),
          INDEX IDX_75EA56E016BA31DB (delivered_at),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE
          ingredient
        ADD
          CONSTRAINT FK_6BAF787012469DE2 FOREIGN KEY (category_id) REFERENCES ingredient_category (id)');

        $this->addSql('ALTER TABLE
          ingredient_ingredient_tag
        ADD
          CONSTRAINT FK_E277767A933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE
          ingredient_ingredient_tag
        ADD
          CONSTRAINT FK_E277767A56A8A350 FOREIGN KEY (ingredient_tag_id) REFERENCES ingredient_tag (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE
          recipe_ingredient
        ADD
          CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');

        $this->addSql('ALTER TABLE
          recipe_ingredient
        ADD
          CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787012469DE2');
        $this->addSql('ALTER TABLE ingredient_ingredient_tag DROP FOREIGN KEY FK_E277767A933FE08C');
        $this->addSql('ALTER TABLE ingredient_ingredient_tag DROP FOREIGN KEY FK_E277767A56A8A350');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13933FE08C');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_ingredient_tag');
        $this->addSql('DROP TABLE ingredient_category');
        $this->addSql('DROP TABLE ingredient_tag');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
