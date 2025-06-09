<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFullNameToStudentsTable extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE students
            ADD COLUMN full_name VARCHAR(255)
            GENERATED ALWAYS AS (
                CONCAT_WS(' ', 
                    first_name, 
                    IF(middle_name IS NOT NULL AND middle_name != '', CONCAT(LEFT(middle_name, 1), '.'), NULL), 
                    last_name, 
                    extension_name
                )
            ) STORED
        ");
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
}
