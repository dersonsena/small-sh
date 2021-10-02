<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use phpDocumentor\Reflection\Types\Void_;

final class Url extends AbstractMigration
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
        $table = $this->table('urls');
        $table
            ->addColumn('uuid', 'string', [
                'limit' => 36
            ])
            ->addColumn('user_id', 'binary', [
                'limit' => 16,
                'null' => true
            ])
            ->addColumn('long_url', 'text')
            ->addColumn('short_url_path', 'string', [
                'limit' => 15
            ])
            ->addColumn('type', 'enum', [
                'values' => ['RANDOM','CUSTOM'],
                'default' => 'RANDOM'
            ])
            ->addColumn('economy_rate', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'default' => 0.00
            ])
            ->addColumn('meta', 'json', [
                'null' => true
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->addIndex(['uuid', 'short_url_path'], [
                'unique' => true
            ])
            ->create();

            $table->changeColumn('id', 'binary', [
                'limit' => 16,
                'signed' => false
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('urls')->drop()->save();
    }
}
