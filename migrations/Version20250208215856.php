<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208215856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9208F64F1');
        $this->addSql('DROP INDEX IDX_169E6FB9208F64F1 ON course');
        $this->addSql('ALTER TABLE course DROP tutor_id, DROP created_at, DROP type_cours, CHANGE description description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD tutor_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD type_cours VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9208F64F1 FOREIGN KEY (tutor_id) REFERENCES tutor (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9208F64F1 ON course (tutor_id)');
    }
}
