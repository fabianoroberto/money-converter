<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503204837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, code VARCHAR(10) NOT NULL, photo_filename VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, price_pound_value INT DEFAULT NULL, price_shilling_value INT DEFAULT NULL, price_pence_value INT DEFAULT NULL, UNIQUE INDEX UNIQ_23A0E6677153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_article (catalog_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', article_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_D88F34ACC3C66FC (catalog_id), INDEX IDX_D88F34A7294869C (article_id), PRIMARY KEY(catalog_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalog_article ADD CONSTRAINT FK_D88F34ACC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE catalog_article ADD CONSTRAINT FK_D88F34A7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_article DROP FOREIGN KEY FK_D88F34A7294869C');
        $this->addSql('ALTER TABLE catalog_article DROP FOREIGN KEY FK_D88F34ACC3C66FC');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE catalog');
        $this->addSql('DROP TABLE catalog_article');
    }
}
