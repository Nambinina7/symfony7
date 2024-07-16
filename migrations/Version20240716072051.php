<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240716072051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert admin user and emails for changing and forgetting passwords';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO `user` (`id`, `image`, `email`, `roles`, `password`, `position`, `description`, `first_name`, `last_name`, `last_change_request`, `created_at`, `updated_at`) VALUES
        (1, NULL, 'admin@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$6EXyXJAyAnrn31N9XVCqU.NKVQJyBoNnQoShW9NL3H6suxYS4H08y', 'admin', 'admin', 'John', 'Doe', '2024-07-11 14:15:35', '2024-07-11 12:15:36', '2024-07-11 12:15:36')");

        $this->addSql("INSERT INTO `mail` (`id`, `name`, `subject`, `body`, `parameters`, `created_at`, `updated_at`) VALUES
        (1, 'Change password', 'Cliquez sur le lien suivant pour modifier votre mot de passe', '<p><a href=\"URL_LINK\">Modification mot de passe</a></p>', '[\"URL_LINK\", \"Modification mot de passe\"]', '2024-07-11 14:02:36', '2024-07-12 13:50:55'),
        (2, 'Forgot password', 'Cliquez sur le lien suivant pour reseter votre mot de passe', '<p><a href=\"URL_LINK\">Reset mot de passe</a></p>', '[\"URL_LINK\", \"Reset mot de passe\"]', '2024-07-11 14:07:01', '2024-07-12 13:51:20')");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM `user` WHERE `id` = 1");

        $this->addSql("DELETE FROM `mail` WHERE `id` IN (1, 2)");

    }
}
