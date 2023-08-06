<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426191328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE warehouse_item (id INT AUTO_INCREMENT NOT NULL, warehouse_id INT NOT NULL, item_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_C07125CA5080ECDE (warehouse_id), INDEX IDX_C07125CA126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE warehouse_item ADD CONSTRAINT FK_C07125CA5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE warehouse_item ADD CONSTRAINT FK_C07125CA126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_item DROP FOREIGN KEY FK_C07125CA5080ECDE');
        $this->addSql('ALTER TABLE warehouse_item DROP FOREIGN KEY FK_C07125CA126F525E');
        $this->addSql('DROP TABLE warehouse_item');
    }
}
