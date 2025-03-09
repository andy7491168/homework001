<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        DROP TABLE IF EXISTS "campaigns";
        ');
        DB::statement('
        CREATE SEQUENCE IF NOT EXISTS campaigns_id_seq;
        ');
        DB::statement('
        CREATE TABLE "campaigns" (
            "id" int8 NOT NULL DEFAULT nextval(\'campaigns_id_seq\'::regclass),
            "name" varchar(255) NOT NULL,
            "created_at" timestamptz DEFAULT CURRENT_TIMESTAMP,
            "updated_at" timestamptz DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY ("id")
        );
        ');

        DB::statement('
        DROP TABLE IF EXISTS "history_for_editable";
        ');
        DB::statement('
        CREATE SEQUENCE IF NOT EXISTS history_for_editable_id_seq;
        ');
        DB::statement('
        CREATE TABLE "history_for_editable" (
            "id" int8 DEFAULT nextval(\'history_for_editable_id_seq\'::regclass),
            "data" jsonb,
            "created_at" timestamp,
            "updated_at" timestamp
        );
        ');

        DB::statement('
        DROP TABLE IF EXISTS "invoices";
        ');
        DB::statement('
        CREATE SEQUENCE IF NOT EXISTS invoices_id_seq;
        ');
        DB::statement('
            CREATE TABLE "invoices" (
                "id" int8 NOT NULL DEFAULT nextval(\'invoices_id_seq\'::regclass),
                "invoice_number" varchar(255) NOT NULL,
                "campaigns" int4 NOT NULL,
                "issue_date" timestamp NOT NULL,
                "total_amount" numeric(10,2) NOT NULL,
                "tax_amount" numeric(10,2) NOT NULL,
                "status" varchar(20) NOT NULL DEFAULT \'pending\'::character varying,
                "payment_date" timestamp NOT NULL,
                "created_at" timestamptz NOT NULL DEFAULT CURRENT_TIMESTAMP,
                "updated_at" timestamptz NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY ("id")
            );
        ');

        DB::statement('
        DROP TABLE IF EXISTS "line_items";
        ');
        DB::statement('
        CREATE SEQUENCE IF NOT EXISTS line_items_id_seq;
        ');
        DB::statement('
            CREATE TABLE "line_items" (
                "id" int8 DEFAULT nextval(\'line_items_id_seq\'::regclass),
                "campaign_id" int8,
                "name" varchar(255),
                "booked_amount" numeric(30,15) DEFAULT 0,
                "actual_amount" numeric(30,15) DEFAULT 0,
                "adjustments" numeric(30,15) DEFAULT 0,
                "created_at" timestamptz DEFAULT CURRENT_TIMESTAMP,
                "updated_at" timestamptz DEFAULT CURRENT_TIMESTAMP
            );
        ');

        DB::statement('
        DROP TABLE IF EXISTS "line_item_invoices";
        ');
        DB::statement('
        CREATE SEQUENCE IF NOT EXISTS line_item_invoices_id_seq;
        ');
        DB::statement('
            CREATE TABLE "line_item_invoices" (
                "id" int8 NOT NULL DEFAULT nextval(\'line_item_invoices_id_seq\'::regclass),
                "campaign_id" int8 NOT NULL,
                "line_item_name" varchar(255) NOT NULL,
                "booked_amount" numeric(30,15) NOT NULL DEFAULT 0,
                "actual_amount" numeric(30,15) NOT NULL DEFAULT 0,
                "adjustments" numeric(30,15) NOT NULL DEFAULT 0,
                "created_at" timestamptz DEFAULT CURRENT_TIMESTAMP,
                "updated_at" timestamptz DEFAULT CURRENT_TIMESTAMP,
                "campaign_name" varchar(255) NOT NULL,
                "invoice_id" int8 NOT NULL,
                "line_item_id" int8 NOT NULL,
                PRIMARY KEY ("id")
            );
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 刪除資料表
        Schema::dropIfExists('campaigns');
    }
}
