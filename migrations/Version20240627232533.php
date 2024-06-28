<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627232533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Generate Tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commuting_compensation (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', employee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', month INT NOT NULL, year INT NOT NULL, transportation_type VARCHAR(255) NOT NULL, number_of_days INT NOT NULL, commuted_distance INT NOT NULL, compensation_amount DOUBLE PRECISION NOT NULL, paid_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_46F567D48C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', employee_number INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, commuting_distance INT NOT NULL, transportation_type VARCHAR(255) NOT NULL, weekly_office_working_days DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_5D9F75A1BFA1DBC1 (employee_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transportation_type (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, min_distance INT DEFAULT NULL, max_distance INT DEFAULT NULL, cost DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commuting_compensation ADD CONSTRAINT FK_46F567D48C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commuting_compensation DROP FOREIGN KEY FK_46F567D48C03F15C');
        $this->addSql('DROP TABLE commuting_compensation');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE transportation_type');
    }
}
