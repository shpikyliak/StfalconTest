<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029164508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_threshold_check ADD rate_privat DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE currency_threshold_check RENAME COLUMN rate TO rate_mono');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE currency_threshold_check ADD rate DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE currency_threshold_check DROP rate_mono');
        $this->addSql('ALTER TABLE currency_threshold_check DROP rate_privat');
    }
}
