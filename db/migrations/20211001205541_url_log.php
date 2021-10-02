<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UrlLog extends AbstractMigration
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
        $table = $this->table('urls_logs');
        $table
            ->addColumn('uuid', 'string', [
                'limit' => 36
            ])
            ->addColumn('url_id', 'binary', [
                'limit' => 16
            ])
            ->addColumn('meta', 'json', [
                'null' => true
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->addIndex(['uuid'], [
                'unique' => true
            ])
            ->create();

            $table->changeColumn('id', 'binary', [
                'limit' => 16,
                'signed' => false
            ])
            ->update();

            $table->addForeignKey('url_id', 'urls', ['id'], [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('urls_logs')->drop()->save();
    }
}
