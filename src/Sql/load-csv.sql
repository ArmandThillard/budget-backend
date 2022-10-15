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
FROM '/var/www/symfony_docker/var/csv/export-operations-19-09-2022_14-07-52.csv'
WITH
    DELIMITER ';'
    HEADER CSV
;