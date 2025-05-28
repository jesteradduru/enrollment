<?php

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculty_classroom', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'faculty_id');
            $table->foreignIdFor(Classroom::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_classroom');
    }
};
