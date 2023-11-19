-- Categories
WITH new_categories AS (
	SELECT DISTINCT category, parents.category_id AS parent_category_id
	FROM export_csv
	LEFT JOIN category ON category.label = export_csv.category AND category.parent_category_id IS NOT NULL
	JOIN category AS parents ON parents.label = export_csv.categoryparent
	WHERE category.category_id IS NULL
)
INSERT INTO category (label, category_id, parent_category_id)
SELECT
	new_categories.category,
	nextval('category_seq'),
	new_categories.parent_category_id
FROM new_categories
;