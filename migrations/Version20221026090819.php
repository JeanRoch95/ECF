<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026090819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission_user_structure DROP FOREIGN KEY FK_64F7DB3ACAA3A018');
        $this->addSql('ALTER TABLE permission_user_structure DROP FOREIGN KEY FK_64F7DB3AFED90CCA');
        $this->addSql('DROP INDEX IDX_64F7DB3ACAA3A018 ON permission_user_structure');
        $this->addSql('DROP INDEX `primary` ON permission_user_structure');
        $this->addSql('ALTER TABLE permission_user_structure CHANGE userStructure_id user_structure_id INT NOT NULL');
        $this->addSql('ALTER TABLE permission_user_structure ADD CONSTRAINT FK_64F7DB3AA5F87C89 FOREIGN KEY (user_structure_id) REFERENCES user_structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_user_structure ADD CONSTRAINT FK_64F7DB3AFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (permID) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_64F7DB3AA5F87C89 ON permission_user_structure (user_structure_id)');
        $this->addSql('ALTER TABLE permission_user_structure ADD PRIMARY KEY (permission_id, user_structure_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission_user_structure DROP FOREIGN KEY FK_64F7DB3AA5F87C89');
        $this->addSql('ALTER TABLE permission_user_structure DROP FOREIGN KEY FK_64F7DB3AFED90CCA');
        $this->addSql('DROP INDEX IDX_64F7DB3AA5F87C89 ON permission_user_structure');
        $this->addSql('DROP INDEX `PRIMARY` ON permission_user_structure');
        $this->addSql('ALTER TABLE permission_user_structure CHANGE user_structure_id userStructure_id INT NOT NULL');
        $this->addSql('ALTER TABLE permission_user_structure ADD CONSTRAINT FK_64F7DB3ACAA3A018 FOREIGN KEY (userStructure_id) REFERENCES user_structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_user_structure ADD CONSTRAINT FK_64F7DB3AFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (permID)');
        $this->addSql('CREATE INDEX IDX_64F7DB3ACAA3A018 ON permission_user_structure (userStructure_id)');
        $this->addSql('ALTER TABLE permission_user_structure ADD PRIMARY KEY (permission_id, userStructure_id)');
    }
}
