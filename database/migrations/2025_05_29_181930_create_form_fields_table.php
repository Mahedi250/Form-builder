<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('label');
            $table->enum('type', [
                'text', 'email', 'textarea', 'number', 'select',
                'checkbox', 'radio', 'date', 'password'
            ]);
            $table->string('placeholder')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('order_index')->default(0);
            $table->json('validation_rules')->nullable();
            $table->json('meta')->nullable(); // for future UI configs
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};

