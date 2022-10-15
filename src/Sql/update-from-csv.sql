
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

-- Transactions
INSERT INTO transaction (transaction_id, date_op, date_val, label, category_id, supplier_id, amount, account_id, comment, pointed, need, file_id)
SELECT
	nextval('transaction_seq'),
	TO_DATE(export_csv.dateop, 'DD/MM/YYYY'),
	TO_DATE(export_csv.dateval, 'DD/MM/YYYY'),
	export_csv.label,
	category.category_id,
	supplier.supplier_id,
	REPLACE(export_csv.amount, ',', '.')::float,
	account.account_id,
	export_csv.comment,
	CASE WHEN export_csv.pointer = 'Non' THEN false ELSE true END,
	false AS need,
	0
FROM export_csv
JOIN account ON account.num = export_csv.accountnum
JOIN supplier ON supplier.label = export_csv.supplierfound
JOIN category ON category.label = export_csv.category
;
