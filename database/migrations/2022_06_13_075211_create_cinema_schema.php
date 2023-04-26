<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // Create movies table
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });

        // Create showrooms table
        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create shows table
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('showroom_id')->constrained('showrooms')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });

        // Create seat_types table
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('premium_percentage', 5, 2);
            $table->timestamps();
        });

        // Create seats table
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->foreignId('seat_type_id')->constrained('seat_types')->onDelete('cascade');
            $table->integer('number');
            $table->timestamps();
        });

        // Create bookings table
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
            $table->string('user_name');
            $table->string('user_email');
            $table->timestamps();
        });

        // Create booking_seats table
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // Drop booking_seats table
        Schema::dropIfExists('booking_seats');

        // Drop bookings table
        Schema::dropIfExists('bookings');

        // Drop seats table
        Schema::dropIfExists('seats');

        // Drop seat_types table
        Schema::dropIfExists('seat_types');

        // Drop shows table
        Schema::dropIfExists('shows');

        // Drop showrooms table
        Schema::dropIfExists('showrooms');

        // Drop movies table
        Schema::dropIfExists('movies');

    }
}
