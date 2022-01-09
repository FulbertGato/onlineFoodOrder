<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220109122042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detail_commande_produit (detail_commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_FD11CFABEDE14305 (detail_commande_id), INDEX IDX_FD11CFABF347EFB (produit_id), PRIMARY KEY(detail_commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detail_commande_produit ADD CONSTRAINT FK_FD11CFABEDE14305 FOREIGN KEY (detail_commande_id) REFERENCES detail_commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detail_commande_produit ADD CONSTRAINT FK_FD11CFABF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE detail_commande_produit');
    }
}
