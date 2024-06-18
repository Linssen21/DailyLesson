<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * User table migration file.
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id()->unsigned()->comment('user role');
            $table->string('name', 100)->comment('Role name');
            $table->string('description', 255)->nullable()->comment('Role Description');
            $table->string('permissions', 255)->nullable()->comment('List of permission');
            $table->integer('deleted')->default(0)->comment('Deleted value 1: True 0: False');
            $table->timestamps();
        });

        if (Schema::hasTable('role')) {

            Schema::create('users', function (Blueprint $table) {
                $table->id()->unsigned()->comment('user id');
                $table->string('name', 255)->unique()->comment('Name of the user');
                $table->string('password', 100)->nullable()->comment('user password');
                $table->string('email', 100)->unique()->comment('user email address');
                $table->timestamp('email_verified_at')->nullable()->comment('user account verification date');
                $table->timestamp('registered_date')->nullable()->comment('user registered date');
                $table->integer('status')->default('2')->comment('0: INACTIVE, 1: ACTIVE, 2: PENDING, 3: DELETED');
                $table->string('display_name', 40)->comment('Name of user to be displayed');
                $table->unsignedBigInteger('role_id')->nullable()->comment('The assigned role for the user, if null means client users');
                $table->foreign('role_id')->references('id')->on('role')->onDelete('set null');
                $table->rememberToken();
                $table->timestamps();
            });

        }

        if (Schema::hasTable('users')) {
            Schema::create('user_meta', function (Blueprint $table) {
                $table->id()->unsigned()->comment('user meta id');
                $table->unsignedBigInteger('user_id')->nullable()->comment('user id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->string('meta_key', 255)->comment('user meta key');
                $table->text('meta_value')->comment('user meta value');
                $table->integer('deleted')->default(0)->comment('Deleted value 1: True 0: False');
                $table->timestamps();
            });
        }

        Schema::create('permission', function (Blueprint $table) {
            $table->id()->unsigned()->comment('user permission');
            $table->string('name', 100)->comment('Role name');
            $table->string('key', 255)->nullable()->comment('Key of the permission');
            $table->string('description', 255)->nullable()->comment('description of permission');
            $table->integer('deleted')->default(0)->comment('Deleted value 1: True 0: False');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_meta');
        Schema::dropIfExists('role');
        Schema::dropIfExists('permission');
    }
};
