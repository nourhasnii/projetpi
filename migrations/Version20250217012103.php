<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217012103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course CHANGE categories_id categories_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course_file DROP FOREIGN KEY FK_443DEB16591CC992');
        $this->addSql('ALTER TABLE course_file ADD CONSTRAINT FK_443DEB16591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course CHANGE categories_id categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE course_file DROP FOREIGN KEY FK_443DEB16591CC992');
        $this->addSql('ALTER TABLE course_file ADD CONSTRAINT FK_443DEB16591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }
}
