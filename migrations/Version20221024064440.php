<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024064440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (permID INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(permID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_user_structure (permission_id INT NOT NULL, user_structure_id INT NOT NULL, INDEX IDX_64F7DB3AFED90CCA (permission_id), INDEX IDX_64F7DB3AA5F87C89 (user_structure_id), PRIMARY KEY(permission_id, user_structure_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6ACCF62EE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_partenaire (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, phone VARCHAR(20) NOT NULL, partenaire_name VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_9598659FE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_structure (id INT AUTO_INCREMENT NOT NULL, user_partenaire_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(100) NOT NULL, structure_name VARCHAR(100) NOT NULL, phone VARCHAR(20) NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_6FE1BA0EE7927C74 (email), INDEX IDX_6FE1BA0E7650BEFC (user_partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permission_user_structure ADD CONSTRAINT FK_64F7DB3AFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (permID)');
        $this->addSql('ALTER TABLE permission_user_structure ADD CONSTRAINT FK_64F7DB3AA5F87C89 FOREIGN KEY (user_structure_id) REFERENCES user_structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_structure ADD CONSTRAINT FK_6FE1BA0E7650BEFC FOREIGN KEY (user_partenaire_id) REFERENCES user_partenaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission_user_structure DROP FOREIGN KEY FK_64F7DB3AFED90CCA');
        $this->addSql('ALTER TABLE permission_user_structure DROP FOREIGN KEY FK_64F7DB3AA5F87C89');
        $this->addSql('ALTER TABLE user_structure DROP FOREIGN KEY FK_6FE1BA0E7650BEFC');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE permission_user_structure');
        $this->addSql('DROP TABLE user_admin');
        $this->addSql('DROP TABLE user_partenaire');
        $this->addSql('DROP TABLE user_structure');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
