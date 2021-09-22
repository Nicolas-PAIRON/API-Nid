<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210530141615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_bill (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(120) NOT NULL, town VARCHAR(58) NOT NULL, postcode VARCHAR(8) NOT NULL, country VARCHAR(33) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD address_bill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494169E56D FOREIGN KEY (address_bill_id) REFERENCES address_bill (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494169E56D ON user (address_bill_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494169E56D');
        $this->addSql('DROP TABLE address_bill');
        $this->addSql('DROP INDEX UNIQ_8D93D6494169E56D ON user');
        $this->addSql('ALTER TABLE user DROP address_bill_id');
    }
}
