<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code'); //code invoices in fagr
            $table->integer('invoice_type');//-,مرتجع استلام وتسليم  /
            $table->date('invoice_date');
            $table->foreignId('location_id')->constrained('locations')->onDelete('restrict');
            $table->foreignId('employee_id')->references('id')->on('admins')->onDelete('restrict');
            $table->foreignId('supplier_id')->nullable()->references('id')->on('suppliers')->onDelete('restrict');
            $table->foreignId('customer_id')->nullable()->references('id')->on('customers')->onDelete('restrict');
            $table->foreignId('created_by')->nullable()->references('id')->on('admins')->onDelete('set null');
            $table->integer('invoice_status')->default(1);//مرتجع -تحت الاستلام وتحت توصيل والتسليم ومخزن 
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
