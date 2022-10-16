<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221016093529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE stock_ingredient (
                id INT AUTO_INCREMENT NOT NULL, 
                ingredient_id INT NOT NULL, 
                quantity_type_id INT DEFAULT NULL, 
                quantity_number NUMERIC(6, 2) UNSIGNED DEFAULT NULL, 
                comment VARCHAR(255) DEFAULT NULL, 
                expires_at DATE DEFAULT NULL, 
                stock_status VARCHAR(50) DEFAULT NULL, 
                INDEX IDX_C5E68FDC933FE08C (ingredient_id), 
                INDEX IDX_C5E68FDC36F84596 (quantity_type_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE stock_ingredient ADD CONSTRAINT FK_C5E68FDC933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE stock_ingredient ADD CONSTRAINT FK_C5E68FDC36F84596 FOREIGN KEY (quantity_type_id) REFERENCES quantity_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_ingredient DROP FOREIGN KEY FK_C5E68FDC933FE08C');
        $this->addSql('ALTER TABLE stock_ingredient DROP FOREIGN KEY FK_C5E68FDC36F84596');
        $this->addSql('DROP TABLE stock_ingredient');
    }
}
