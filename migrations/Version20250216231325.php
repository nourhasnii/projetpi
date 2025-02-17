<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216231325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_file DROP FOREIGN KEY FK_443DEB16591CC992');
        $this->addSql('ALTER TABLE course_file DROP size, CHANGE course_id course_id INT NOT NULL, CHANGE file_path file_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE course_file ADD CONSTRAINT FK_443DEB16591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_file DROP FOREIGN KEY FK_443DEB16591CC992');
        $this->addSql('ALTER TABLE course_file ADD size INT NOT NULL, CHANGE course_id course_id INT DEFAULT NULL, CHANGE file_name file_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE course_file ADD CONSTRAINT FK_443DEB16591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
    }
}
