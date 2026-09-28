<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Enquiries submitted from the website's /b2b-partnerships form.
// The website (dookwebsite) writes to this table; the admin panel reads it.
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_partnership_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('company_name', 160);
            $table->string('email', 180)->index();
            $table->string('mobile', 30);
            $table->string('agency_country', 80);
            $table->string('travel_type', 40)->nullable()->index();
            $table->json('destinations');
            $table->date('travel_month')->nullable();
            $table->unsignedInteger('no_of_travellers')->nullable();
            $table->string('hotel_category', 40)->nullable();
            $table->string('budget_range', 120)->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('contact_consent_at')->nullable();
            $table->string('page_url', 500)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b2b_partnership_enquiries');
    }
};
