<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UuidToBin extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $this->execute("CREATE DEFINER=`smll_main`@`%.%.%.%`
        FUNCTION UUID_TO_BIN(uuid CHAR(36)) RETURNS binary(16)
        RETURN UNHEX(REPLACE(uuid, '-', ''));");
    }

    public function down(): void
    {
        $this->execute("DROP FUNCTION IF EXISTS UUID_TO_BIN");
    }
}
