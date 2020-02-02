<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903105217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_question_answer_deleted ADD id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user_token CHANGE survey_id survey_id INT DEFAULT NULL, CHANGE finished_survey_at finished_survey_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE answer CHANGE next_question_id next_question_id INT DEFAULT NULL, CHANGE text text VARCHAR(255) DEFAULT NULL, CHANGE additional_text additional_text VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer CHANGE next_question_id next_question_id INT DEFAULT NULL, CHANGE text text VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE additional_text additional_text VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_question_answer_deleted MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user_question_answer_deleted DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_question_answer_deleted DROP id');
        $this->addSql('ALTER TABLE user_token CHANGE survey_id survey_id INT DEFAULT NULL, CHANGE finished_survey_at finished_survey_at DATETIME DEFAULT \'NULL\'');
    }
}
