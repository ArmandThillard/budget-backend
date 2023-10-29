<?php

class ImportFileService
{
    public static function loadData(string $csv, int $fileId): void
    {
        $host = $_ENV['DATABASE_HOST'];
        $dbname = $_ENV['DATABASE_DBNAME'];
        $user = $_ENV['DATABASE_USER'];
        $pwd = $_ENV['DATABASE_PASSWORD'];

        $dbconn = pg_connect("host=$host dbname=$dbname user=$user password=$pwd")
            or die('Connexion impossible : ' . pg_last_error());

        $error_msg = 'Échec de la requête : ';

        pg_query($dbconn, self::createExportCsvTable());
        self::copyCsv($csv);
        pg_query($dbconn, self::updateParentsCategories()) or die($error_msg . pg_last_error());
        pg_query($dbconn, self::updateCategories()) or die($error_msg . pg_last_error());
        pg_query($dbconn, self::updateSuppliers()) or die($error_msg . pg_last_error());
        pg_query($dbconn, self::updateAccounts()) or die($error_msg . pg_last_error());
        pg_query($dbconn, self::updateTransactions($fileId)) or die($error_msg . pg_last_error());

        // Ferme la connexion
        pg_close($dbconn);
    }

    /**
     * SQL statements creating export_csv table used to import csv data.
     * 
     * @return string SQL statement
     */
    private static function createExportCsvTable(): string
    {
        return <<<SQL
            DROP TABLE IF EXISTS export_csv
            ;

            CREATE TABLE export_csv (
                dateOp TEXT,
                dateVal TEXT ,
                label TEXT,
                category TEXT,
                categoryParent TEXT,
                supplierFound TEXT,
                amount TEXT,
                accountNum TEXT,
                accountLabel TEXT,
                accountBalance TEXT,
                comment TEXT,
                pointer TEXT
            );

        SQL;
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

    private static function updateParentsCategories(): string
    {
        return <<<SQL
            -- Categories parents
            WITH new_parent_categories AS (
                SELECT DISTINCT categoryparent
                FROM export_csv
                LEFT JOIN category ON category.label = export_csv.categoryparent
                WHERE category.category_id IS NULL
            )
            INSERT INTO category (label, category_id)
            SELECT categoryparent, nextval('category_seq')
            FROM new_parent_categories
            ;
        SQL;
    }

    private static function updateCategories(): string
    {
        return <<<SQL
            -- Categories
            WITH new_categories AS (
                SELECT DISTINCT category, parents.category_id AS parent_category_id
                FROM export_csv
                LEFT JOIN category ON category.label = export_csv.category AND category.parent_category_id iS NOT NULL
                JOIN category AS parents ON parents.label = export_csv.categoryparent
                WHERE category.category_id IS NULL
            )
            INSERT INTO category (label, category_id, parent_category_id)
            SELECT new_categories.category, nextval('category_seq'), new_categories.parent_category_id
            FROM new_categories
            ;
        SQL;
    }

    private static function updateSuppliers(): string
    {
        return <<<SQL
            -- Suppliers
            WITH new_suppliers AS (
                SELECT DISTINCT supplierfound
                FROM export_csv
                LEFT JOIN supplier ON supplier.label = export_csv.supplierfound
                WHERE supplier.supplier_id IS NULL
                AND supplierfound IS NOT NULL
            )
            INSERT INTO supplier (supplier_id, label, name)
            SELECT nextval('supplier_seq'), new_suppliers.supplierfound,  new_suppliers.supplierfound
            FROM new_suppliers
            ;
        SQL;
    }

    private static function updateAccounts(): string
    {
        return <<<SQL
            -- Accounts
            WITH new_accounts AS (
                SELECT DISTINCT accountnum, accountlabel
                FROM export_csv
                LEFT JOIN account ON account.num = export_csv.accountnum
                WHERE account.account_id IS NULL
            )
            INSERT INTO account (account_id, num, label)
            SELECT nextval('account_seq'), new_accounts.accountnum,  new_accounts.accountlabel
            FROM new_accounts
            ;
        SQL;
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
