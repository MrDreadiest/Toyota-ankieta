<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190828113608 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dealer (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_token (token VARCHAR(10) NOT NULL, survey_id INT DEFAULT NULL, first_name LONGTEXT NOT NULL, last_name LONGTEXT NOT NULL, finished_survey_at DATETIME DEFAULT NULL, INDEX IDX_BDF55A63B3FE509D (survey_id), PRIMARY KEY(token)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, next_question_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, type INT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), INDEX IDX_DADD4A251CF5F25E (next_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B6F7494EB3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_question_answer (token VARCHAR(10) NOT NULL, answer_id INT NOT NULL, answered_at DATETIME NOT NULL, text_answer LONGTEXT DEFAULT NULL, INDEX IDX_CF5C09A2AA334807 (answer_id), PRIMARY KEY(token, answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_token ADD CONSTRAINT FK_BDF55A63B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251CF5F25E FOREIGN KEY (next_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EB3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE user_question_answer ADD CONSTRAINT FK_CF5C09A2AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_question_answer DROP FOREIGN KEY FK_CF5C09A2AA334807');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251CF5F25E');
        $this->addSql('ALTER TABLE user_token DROP FOREIGN KEY FK_BDF55A63B3FE509D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EB3FE509D');
        $this->addSql('DROP TABLE dealer');
        $this->addSql('DROP TABLE user_token');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE user_question_answer');
        $this->addSql('DROP TABLE survey');
    }
}
