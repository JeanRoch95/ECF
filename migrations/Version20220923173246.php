<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923173246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_structure DROP FOREIGN KEY FK_6FE1BA0E7650BEFC');
        $this->addSql('DROP INDEX IDX_6FE1BA0E7650BEFC ON user_structure');
        $this->addSql('ALTER TABLE user_structure DROP user_partenaire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_structure ADD user_partenaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_structure ADD CONSTRAINT FK_6FE1BA0E7650BEFC FOREIGN KEY (user_partenaire_id) REFERENCES user_partenaire (id)');
        $this->addSql('CREATE INDEX IDX_6FE1BA0E7650BEFC ON user_structure (user_partenaire_id)');
    }
}
