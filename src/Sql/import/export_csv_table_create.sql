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