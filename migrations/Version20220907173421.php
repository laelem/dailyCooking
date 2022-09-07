<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220907173421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_tag DROP FOREIGN KEY FK_8CC02FFB933FE08C');
        $this->addSql('ALTER TABLE ingredient_tag DROP FOREIGN KEY FK_8CC02FFBBAD26311');
        $this->addSql('DROP TABLE ingredient_tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient_tag (id INT AUTO_INCREMENT NOT NULL, ingredient_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_8CC02FFB933FE08C (ingredient_id), INDEX IDX_8CC02FFBBAD26311 (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ingredient_tag ADD CONSTRAINT FK_8CC02FFB933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ingredient_tag ADD CONSTRAINT FK_8CC02FFBBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
