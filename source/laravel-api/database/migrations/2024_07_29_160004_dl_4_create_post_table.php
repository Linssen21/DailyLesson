<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->unsigned()->comment('post id');
            $table->unsignedBigInteger('author_id')->nullable()->comment('user id');
            $table->foreign('author_id')->references('id')->on('users');
            $table->text('content')->nullable()->comment('content of the post');
            $table->string('title', 255)->comment('title of the post');
            $table->string('excerpt', 255)->nullable()->comment('excerpt of the post');
            $table->integer('status')->default('2')->comment('0: DRAFT, 1: PUBLISH, 2: PENDING, 3: DELETED');
            $table->string('type', 255)->default('post')->comment('type of the post, i.e slide, post, etc');
            $table->string('slug', 255)->unique()->comment('slug of the post');
            $table->integer('like_count')->default(0)->comment('the number of likes of a post');
            $table->timestamp('published_at')->nullable()->comment('published date');
            $table->timestamps();
        });

        if (Schema::hasTable('posts')) {
            Schema::create('post_meta', function (Blueprint $table) {
                $table->id()->unsigned()->comment('post meta id');
                $table->unsignedBigInteger('post_id')->nullable()->comment('post id');
                $table->foreign('post_id')->references('id')->on('posts')->onDelete('set null');
                $table->string('meta_key', 255)->comment('post meta key');
                $table->text('meta_value')->comment('post meta value');
                $table->integer('is_deleted')->default(0)->comment('Deleted value 1: True 0: False');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_meta', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });
        Schema::dropIfExists('post_meta');
        Schema::dropIfExists('posts');
    }
};
