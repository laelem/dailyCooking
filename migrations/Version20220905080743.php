<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905080743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870327320D1');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF78708ACF47B4');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870987AE85A');
        $this->addSql('DROP INDEX IDX_6BAF7870987AE85A ON ingredient');
        $this->addSql('DROP INDEX IDX_6BAF78708ACF47B4 ON ingredient');
        $this->addSql('DROP INDEX IDX_6BAF7870327320D1 ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP category1_id, DROP category2_id, DROP category3_id');
        $this->addSql('ALTER TABLE ingredient_category DROP FOREIGN KEY FK_744A494F8ACF47B4');
        $this->addSql('ALTER TABLE ingredient_category DROP FOREIGN KEY FK_744A494F987AE85A');
        $this->addSql('DROP INDEX IDX_744A494F987AE85A ON ingredient_category');
        $this->addSql('DROP INDEX IDX_744A494F8ACF47B4 ON ingredient_category');
        $this->addSql('ALTER TABLE ingredient_category DROP category1_id, DROP category2_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ADD category1_id INT DEFAULT NULL, ADD category2_id INT DEFAULT NULL, ADD category3_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870327320D1 FOREIGN KEY (category3_id) REFERENCES ingredient_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF78708ACF47B4 FOREIGN KEY (category2_id) REFERENCES ingredient_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870987AE85A FOREIGN KEY (category1_id) REFERENCES ingredient_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6BAF7870987AE85A ON ingredient (category1_id)');
        $this->addSql('CREATE INDEX IDX_6BAF78708ACF47B4 ON ingredient (category2_id)');
        $this->addSql('CREATE INDEX IDX_6BAF7870327320D1 ON ingredient (category3_id)');
        $this->addSql('ALTER TABLE ingredient_category ADD category1_id INT DEFAULT NULL, ADD category2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient_category ADD CONSTRAINT FK_744A494F8ACF47B4 FOREIGN KEY (category2_id) REFERENCES ingredient_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ingredient_category ADD CONSTRAINT FK_744A494F987AE85A FOREIGN KEY (category1_id) REFERENCES ingredient_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_744A494F987AE85A ON ingredient_category (category1_id)');
        $this->addSql('CREATE INDEX IDX_744A494F8ACF47B4 ON ingredient_category (category2_id)');
    }
}
