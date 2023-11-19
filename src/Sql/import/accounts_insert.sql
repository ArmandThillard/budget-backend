-- Accounts
WITH new_accounts AS (
	SELECT DISTINCT accountnum, accountlabel
	FROM export_csv
	LEFT JOIN account ON account.num = export_csv.accountnum
	WHERE account.account_id IS NULL
)
INSERT INTO account (account_id, num, label)
SELECT
	nextval('account_seq'),
	new_accounts.accountnum,
	new_accounts.accountlabel
FROM new_accounts
;
