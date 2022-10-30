<?php

class ImportFileService
{
    public static function loadData(string $csv, int $fileId): void
    {

        $dbconn = pg_connect("host=localhost dbname=budget user=postgres password=postgres")
            or die('Connexion impossible : ' . pg_last_error());

        $result = pg_query(self::copyCsv($csv));

        $result = pg_query(self::updateParentsCategories()) or die('Échec de la requête : ' . pg_last_error());
        $result = pg_query(self::updateCategories()) or die('Échec de la requête : ' . pg_last_error());
        $result = pg_query(self::updateSuppliers()) or die('Échec de la requête : ' . pg_last_error());
        $result = pg_query(self::updateAccounts()) or die('Échec de la requête : ' . pg_last_error());
        $result = pg_query(self::updateTransactions($fileId)) or die('Échec de la requête : ' . pg_last_error());

        // Libère le résultat
        pg_free_result($result);

        // Ferme la connexion
        pg_close($dbconn);
    }

    /**
     * SQL statements uploading csv data to temp table.
     * 
     * @param string $filename CSV filename to copy.
     * 
     * @return string SQL statement
     */
    private static function copyCsv(string $filename): string
    {
        return <<<SQL
            DROP TABLE IF EXISTS export_csv
            ;

            CREATE TEMP TABLE export_csv (
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

            COPY export_csv
            FROM '/var/www/budget/var/csv/$filename'
            WITH
                DELIMITER ';'
                HEADER CSV
            ;
    
        SQL;
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
                LEFT JOIN category ON category.label = export_csv.category
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
                nextval('transaction_seq'),
                TO_DATE(export_csv.dateop, 'DD/MM/YYYY'),
                TO_DATE(export_csv.dateval, 'DD/MM/YYYY'),
                export_csv.label,
                category.category_id,
                supplier.supplier_id,
                REGEXP_REPLACE(export_csv.amount,'(-?)(\\d*)(\\s?)(\\d*)(,?)(\\d*)', '\\1\\2\\4.\\6')::float,
                account.account_id,
                export_csv.comment,
                CASE WHEN export_csv.pointer = 'Non' THEN false ELSE true END,
                false AS need,
                $fileId
            FROM export_csv
            JOIN account ON account.num = export_csv.accountnum
            JOIN supplier ON supplier.label = export_csv.supplierfound
            JOIN category ON category.label = export_csv.category
            ;
        SQL;
    }
}
