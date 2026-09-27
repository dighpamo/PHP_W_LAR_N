-- Миграция 001: разрешить NULL в articles.category_id
-- Причина: категория для статьи стала необязательной.
-- Применение:
--   mysql -u php_w_lar_n_user -p php_w_lar_n < database/migrations/001_make_category_id_nullable.sql

ALTER TABLE articles
    MODIFY COLUMN category_id INT UNSIGNED NULL;