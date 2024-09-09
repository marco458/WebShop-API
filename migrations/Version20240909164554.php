<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909164554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_3AF34668727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_products (category_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_6544F36312469DE2 (category_id), INDEX IDX_6544F3634584665A (product_id), PRIMARY KEY(category_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_lists (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, price_list_id INT DEFAULT NULL, price NUMERIC(12, 4) NOT NULL, sku VARCHAR(255) NOT NULL, INDEX IDX_4B270381A76ED395 (user_id), INDEX IDX_4B2703814584665A (product_id), INDEX IDX_4B2703815688DED7 (price_list_id), UNIQUE INDEX user_product_unique_constraint (user_id, product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_buyers (id INT AUTO_INCREMENT NOT NULL, original_user_id INT DEFAULT NULL, made_order_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, INDEX IDX_8A6DADB721EE7D62 (original_user_id), UNIQUE INDEX UNIQ_8A6DADB767C9CABD (made_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_modifiers (id INT AUTO_INCREMENT NOT NULL, applied_to_order_id INT NOT NULL, name VARCHAR(255) NOT NULL, percentage DOUBLE PRECISION NOT NULL, discount TINYINT(1) DEFAULT 0 NOT NULL, activate_if_price_greater_than DOUBLE PRECISION DEFAULT NULL, INDEX IDX_642E858BC20F5378 (applied_to_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, price_without_modifiers NUMERIC(12, 4) NOT NULL, final_price NUMERIC(12, 4) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_products (order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_749C879C8D9F6D38 (order_id), INDEX IDX_749C879C4584665A (product_id), PRIMARY KEY(order_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_lists (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(12, 4) NOT NULL, sku VARCHAR(255) NOT NULL, INDEX IDX_23EF97C54584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price NUMERIC(12, 4) NOT NULL, sku VARCHAR(255) NOT NULL, published TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token_key VARCHAR(255) NOT NULL, refresh_token_key VARCHAR(255) NOT NULL, expires_at DATETIME DEFAULT NULL, refresh_expires_at DATETIME DEFAULT NULL, last_active_date DATETIME NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_AA5A118EA76ED395 (user_id), UNIQUE INDEX tokenkey_UNIQUE (token_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT 0 NOT NULL, login_token VARCHAR(255) DEFAULT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_reset_token_expires_at DATETIME DEFAULT NULL, activation_token VARCHAR(255) DEFAULT NULL, activation_token_expires_at DATETIME DEFAULT NULL, email_confirmed_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories_products ADD CONSTRAINT FK_6544F36312469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_products ADD CONSTRAINT FK_6544F3634584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contract_lists ADD CONSTRAINT FK_4B270381A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contract_lists ADD CONSTRAINT FK_4B2703814584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE contract_lists ADD CONSTRAINT FK_4B2703815688DED7 FOREIGN KEY (price_list_id) REFERENCES price_lists (id)');
        $this->addSql('ALTER TABLE order_buyers ADD CONSTRAINT FK_8A6DADB721EE7D62 FOREIGN KEY (original_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE order_buyers ADD CONSTRAINT FK_8A6DADB767C9CABD FOREIGN KEY (made_order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_modifiers ADD CONSTRAINT FK_642E858BC20F5378 FOREIGN KEY (applied_to_order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_lists ADD CONSTRAINT FK_23EF97C54584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE tokens ADD CONSTRAINT FK_AA5A118EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE categories_products DROP FOREIGN KEY FK_6544F36312469DE2');
        $this->addSql('ALTER TABLE categories_products DROP FOREIGN KEY FK_6544F3634584665A');
        $this->addSql('ALTER TABLE contract_lists DROP FOREIGN KEY FK_4B270381A76ED395');
        $this->addSql('ALTER TABLE contract_lists DROP FOREIGN KEY FK_4B2703814584665A');
        $this->addSql('ALTER TABLE contract_lists DROP FOREIGN KEY FK_4B2703815688DED7');
        $this->addSql('ALTER TABLE order_buyers DROP FOREIGN KEY FK_8A6DADB721EE7D62');
        $this->addSql('ALTER TABLE order_buyers DROP FOREIGN KEY FK_8A6DADB767C9CABD');
        $this->addSql('ALTER TABLE order_modifiers DROP FOREIGN KEY FK_642E858BC20F5378');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879C8D9F6D38');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879C4584665A');
        $this->addSql('ALTER TABLE price_lists DROP FOREIGN KEY FK_23EF97C54584665A');
        $this->addSql('ALTER TABLE tokens DROP FOREIGN KEY FK_AA5A118EA76ED395');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_products');
        $this->addSql('DROP TABLE contract_lists');
        $this->addSql('DROP TABLE order_buyers');
        $this->addSql('DROP TABLE order_modifiers');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_products');
        $this->addSql('DROP TABLE price_lists');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('DROP TABLE users');
    }
}
