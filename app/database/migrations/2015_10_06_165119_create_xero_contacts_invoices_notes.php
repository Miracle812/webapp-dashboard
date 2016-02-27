<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXeroContactsInvoicesNotes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('xero_contacts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('ContactID');
            $table->string('Name') ->nullable()->default(NULL);
            $table->string('IsSupplier') ->nullable()->default(NULL);
            $table->string('IsCustomer') ->nullable()->default(NULL);
            $table->string('DefaultCurrency') ->nullable()->default(NULL);
            $table->string('ContactStatus') ->nullable()->default(NULL);
            $table->string('UpdatedDateUTC') ->nullable()->default(NULL);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_assigned_contacts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('headquarters')->onDelete('cascade');
            $table->integer('xero_contact_id')->unsigned();
            $table->foreign('xero_contact_id')->references('id')->on('xero_contacts')->onDelete('cascade');
        });

        Schema::create('xero_contacts_addresses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('xero_contact_id')->unsigned();
            $table->foreign('xero_contact_id')->references('id')->on('xero_contacts')->onDelete('cascade');
            $table->string('AddressType') ->nullable()->default(NULL);
            $table->string('AddressLine1') ->nullable()->default(NULL);
            $table->string('AddressLine2') ->nullable()->default(NULL);
            $table->string('City') ->nullable()->default(NULL);
            $table->string('Region') ->nullable()->default(NULL);
            $table->string('Country') ->nullable()->default(NULL);
            $table->string('PostalCode') ->nullable()->default(NULL);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_invoices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('xero_contact_id')->unsigned();
            $table->foreign('xero_contact_id')->references('id')->on('xero_contacts')->onDelete('cascade');
            $table->string('InvoiceID');
            $table->string('InvoiceNumber')->nullable()->default(NULL);
            $table->string('SubTotal')->nullable()->default(NULL);
            $table->string('TotalTax')->nullable()->default(NULL);
            $table->string('Total')->nullable()->default(NULL);
            $table->string('Reference')->nullable()->default(NULL);
            $table->string('Status')->nullable()->default(NULL);
            $table->string('LineAmountTypes')->nullable()->default(NULL);
            $table->string('Type')->nullable()->default(NULL);
            $table->string('CurrencyCode')->nullable()->default(NULL);
            $table->string('AmountDue')->nullable()->default(NULL);
            $table->string('AmountPaid')->nullable()->default(NULL);
            $table->string('AmountCredited')->nullable()->default(NULL);
            $table->string('UpdatedDateUTC')->nullable()->default(NULL);
            $table->string('FullyPaidOnDate')->nullable()->default(NULL);
            $table->string('Date')->nullable()->default(NULL);
            $table->string('DueDate')->nullable()->default(NULL);
            $table->softDeletes();
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
		//
	}

}
