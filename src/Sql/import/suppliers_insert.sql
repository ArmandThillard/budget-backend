-- Suppliers
WITH new_suppliers AS (
	SELECT DISTINCT supplierfound
	FROM export_csv
	LEFT JOIN supplier ON supplier.label = export_csv.supplierfound
	WHERE supplier.supplier_id IS NULL
	AND supplierfound IS NOT NULL
)
INSERT INTO supplier (supplier_id, label, name)
SELECT
	nextval('supplier_seq'),
	new_suppliers.supplierfound, 
	new_suppliers.supplierfound
FROM new_suppliers
;