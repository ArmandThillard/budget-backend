-- Categories parents
WITH new_parent_categories AS (
	SELECT DISTINCT categoryparent
	FROM export_csv
	LEFT JOIN category ON category.label = export_csv.categoryparent
	WHERE category.category_id IS NULL
)
INSERT INTO category (label, category_id)
SELECT
	categoryparent,
	nextval('category_seq')
FROM new_parent_categories
;