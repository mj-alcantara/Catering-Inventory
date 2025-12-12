
-- Fixed SQL Views File

DROP VIEW IF EXISTS `v_order_summary`;
CREATE ALGORITHM=UNDEFINED 
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER 
VIEW `v_order_summary` AS 
SELECT 
    o.order_id AS order_id,
    o.order_number AS order_number,
    o.customer_name AS customer_name,
    o.customer_email AS customer_email,
    o.customer_phone AS customer_phone,
    o.total_amount AS total_amount,
    o.payment_method AS payment_method,
    o.payment_status AS payment_status,
    o.order_status AS order_status,
    o.delivery_date AS delivery_date,
    o.created_at AS created_at,
    COUNT(oi.order_item_id) AS total_items,
    SUM(oi.quantity) AS total_quantity
FROM orders o
LEFT JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id;

DROP VIEW IF EXISTS `v_top_products`;
CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `v_top_products` AS 
SELECT 
    p.product_id AS product_id,
    p.product_name AS product_name,
    p.category AS category,
    COUNT(oi.order_item_id) AS times_ordered,
    SUM(oi.quantity) AS total_quantity_sold,
    SUM(oi.subtotal) AS total_revenue
FROM products p
JOIN order_items oi ON p.product_id = oi.product_id
JOIN orders o ON oi.order_id = o.order_id
WHERE o.order_status <> 'cancelled'
GROUP BY p.product_id
ORDER BY total_revenue DESC;
