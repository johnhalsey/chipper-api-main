<?php

use App\Models\Favorite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->morphs('favoriteable');
        });

        Favorite::each(function ($favourite) {
            $favourite->favoriteable_id = $favourite->post_id;
            $favourite->favoriteable_type = 'App\Models\Post';
            $favourite->save();
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->dropColumn('post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->after('user_id');
        });

        Favorite::each(function ($favourite) {
            $favourite->post_id = $favourite->favoriteable_id;
            $favourite->save();
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->dropColumn('favoriteable_id');
            $table->dropColumn('favoriteable_type');
        });
    }
};
