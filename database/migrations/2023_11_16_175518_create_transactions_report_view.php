<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsReportView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW transaction_reports
            AS
                SELECT trans.trans_ym,trans.trans_month, trans.trans_year, paid_query.total_paid, outstanding_query.total_outstanding, overdue_query.total_overdue
                FROM
                    (SELECT
                        DATE_FORMAT(transactions.due_on,'%c') AS trans_month,
                        DATE_FORMAT(transactions.due_on,'%Y') AS trans_year,
                        DATE_FORMAT(transactions.due_on,'%Y-%c') AS trans_ym
                    FROM transactions) AS trans
                LEFT JOIN (
                    SELECT
                        SUM(transactions.amount) AS total_paid,
                        DATE_FORMAT(transactions.due_on,'%c') AS paid_month,
                        DATE_FORMAT(transactions.due_on,'%Y') AS paid_year
                    FROM transactions
                    WHERE transactions.status_id = '1'
                    GROUP BY
                        paid_month, paid_year
                ) AS paid_query
                    ON paid_query.paid_month = trans.trans_month AND paid_query.paid_year = trans.trans_year
                LEFT JOIN (
                    SELECT
                        SUM(transactions.amount) AS total_outstanding,
                        DATE_FORMAT(transactions.due_on,'%c') AS outstanding_month,
                        DATE_FORMAT(transactions.due_on,'%Y') AS outstanding_year
                    FROM transactions
                    WHERE transactions.status_id = '2'
                    GROUP BY
                        outstanding_month, outstanding_year
                ) AS outstanding_query
                    ON outstanding_query.outstanding_month = trans.trans_month AND outstanding_query.outstanding_year = trans.trans_year
                LEFT JOIN (
                    SELECT
                        SUM(transactions.amount) AS total_overdue,
                        DATE_FORMAT(transactions.due_on,'%c') AS overdue_month,
                        DATE_FORMAT(transactions.due_on,'%Y') AS overdue_year
                    FROM transactions
                    WHERE transactions.status_id = '3'
                    GROUP BY
                        overdue_month, overdue_year
                ) AS overdue_query
                    ON overdue_query.overdue_month = trans.trans_month AND overdue_query.overdue_year = trans.trans_year
                GROUP BY
                    trans_ym,trans.trans_month, trans.trans_year, paid_query.total_paid, outstanding_query.total_outstanding, overdue_query.total_overdue
                ORDER BY
                    trans.trans_year, trans.trans_month

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            DROP VIEW IF EXISTS transaction_reports
        ");
    }
}
