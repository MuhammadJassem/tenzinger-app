<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240627232607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add transportation data';
    }

    public function up(Schema $schema): void
    {
        $amounts = [
            ['d9e8ed10-bfb7-4ffc-af44-004d287eb602', 'BIKE', 0, 4, 0.50],
            ['daaa3d55-7ef1-4009-bfe9-0017369f1940', 'BIKE', 5, 10, 1.00],
            ['d9e8ed10-bfb7-4ffc-af44-004d287eb600', 'BIKE', 11, null, 0.50],
            ['eb65865b-9870-446f-a561-3a683c24aa1e', 'BUS', 0, null, 0.25],
            ['ec907816-30fd-45b1-8613-451ae10c57c9', 'TRAIN', 0, null, 0.25],
            ['efeefca4-df58-41d8-a18c-079df5605971', 'CAR', 0, null, 0.10],
        ];
        foreach($amounts as $row) {
            $this->addSql('INSERT INTO `transportation_type` (`id`, `code`, `min_distance`, `max_distance`, `cost`) VALUES (?, ?, ?, ?, ?);', $row);
        }
    }

    public function down(Schema $schema): void
    {
    }
}
