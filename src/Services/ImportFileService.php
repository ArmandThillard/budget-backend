<?php

namespace App\Services;

class ImportFileService
{
    private const SQL_PATH = __DIR__ . '/../Sql/import/';

    public static function loadData(string $csv, int $fileId): void
    {
        $host = $_ENV['DATABASE_HOST'];
        $dbname = $_ENV['DATABASE_DBNAME'];
        $user = $_ENV['DATABASE_USER'];
        $pwd = $_ENV['DATABASE_PASSWORD'];

        $dbconn = pg_connect("host=$host dbname=$dbname user=$user password=$pwd")
            or die('Connexion impossible : ' . pg_last_error());

        $error_msg = 'Échec de la requête : ';

        pg_query($dbconn, file_get_contents(self::SQL_PATH . 'export_csv_table_create.sql'));
        self::copyCsv($csv);
        pg_query($dbconn, file_get_contents(self::SQL_PATH . 'parent_categories_insert.sql'))
            or die($error_msg . pg_last_error());
        pg_query($dbconn, file_get_contents(self::SQL_PATH . 'categories_insert.sql'))
            or die($error_msg . pg_last_error());
        pg_query($dbconn, file_get_contents(self::SQL_PATH . 'suppliers_insert.sql'))
            or die($error_msg . pg_last_error());
        pg_query($dbconn, file_get_contents(self::SQL_PATH . 'accounts_insert.sql'))
            or die($error_msg . pg_last_error());
        pg_query($dbconn, self::updateTransactions($fileId)) or die($error_msg . pg_last_error());

        // Ferme la connexion
        pg_close($dbconn);
    }

    /**
     * PSQL command to load CSV file to database.
     *
     * @param string $filename CSV filename to copy.
     *
     * @throws Exception When psql command's exit code is 1.
     */
    private static function copyCsv(string $filename): void
    {
        $cmd = "(psql budget -c \"\copy export_csv FROM '/home/ath/budget/budget-backend/var/csv/$filename' WITH DELIMITER ';' HEADER CSV;\") 2>&1";

        $output = null;
        $exit_code = null;
        exec($cmd, $output, $exit_code);

        if ($exit_code == 1) {
            throw new Exception("An error occured while copying $filename to database : " . serialize($output));
        }
    }

    private static function updateTransactions(int $fileId): string
    {
        return <<<SQL
            -- Transactions
            INSERT INTO transaction (transaction_id, date_op, date_val, label, category_id, supplier_id, amount, account_id, comment, pointed, need, file_id)
            SELECT
                nextval('transaction_seq') AS transaction_id,
                TO_DATE(export_csv.dateop, 'DD/MM/YYYY') AS dateop,
                TO_DATE(export_csv.dateval, 'DD/MM/YYYY') AS dateval,
                export_csv.label,
                category.category_id,
                COALESCE(supplier.supplier_id, 0) AS supplier_id,
                REGEXP_REPLACE(export_csv.amount,'(-?)(\\d*)(\\s?)(\\d*)(,?)(\\d*)', '\\1\\2\\4.\\6')::float AS amount,
                account.account_id,
                COALESCE(export_csv.comment, '') AS comment,
                CASE WHEN export_csv.pointer = 'Non' THEN false ELSE true END AS pointed,
                false AS need,
                $fileId AS file_id
            FROM export_csv
            JOIN account ON account.num = export_csv.accountnum
            LEFT JOIN supplier ON supplier.label = export_csv.supplierfound
            JOIN category ON category.label = export_csv.category AND category.parent_category_id IS NOT NULL
            ;
        SQL;
    }
}
