<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419105427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, poste VARCHAR(255) NOT NULL, candidat_nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, type VARCHAR(255) NOT NULL, date_debut VARCHAR(255) NOT NULL, date_fin VARCHAR(255) DEFAULT NULL, salaire VARCHAR(255) NOT NULL, INDEX IDX_603499931B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE demande_conge (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, type_conge VARCHAR(255) NOT NULL, date_debut VARCHAR(255) NOT NULL, date_fin VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_D80610611B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, date_embauche VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE timesheet (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, date VARCHAR(255) NOT NULL, heures_travail VARCHAR(255) NOT NULL, heures_sup VARCHAR(255) NOT NULL, INDEX IDX_77A4E8D41B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat ADD CONSTRAINT FK_603499931B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande_conge ADD CONSTRAINT FK_D80610611B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timesheet ADD CONSTRAINT FK_77A4E8D41B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat DROP FOREIGN KEY FK_603499931B65292
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande_conge DROP FOREIGN KEY FK_D80610611B65292
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timesheet DROP FOREIGN KEY FK_77A4E8D41B65292
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE candidature
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contrat
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE demande_conge
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employe
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE timesheet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
