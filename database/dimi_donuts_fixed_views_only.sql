-- Fixed Views for dimi_donuts

DROP VIEW IF EXISTS `v_order_summary`;
CREATE VIEW `v_order_summary` AS
SELECT 
    o.order_id,
    o.order_number,
    o.customer_name,
    o.customer_email,
    o.customer_phone,
    o.total_amount,
    o.payment_method,
    o.payment_status,
    o.order_status,
    o.delivery_date,
    o.created_at,
    COUNT(oi.order_item_id) AS total_items,
    SUM(oi.quantity) AS total_quantity
FROM orders o
LEFT JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id;

DROP VIEW IF EXISTS `v_product_stock_status`;
CREATE VIEW `v_product_stock_status` AS
SELECT 
    p.product_id,
    p.product_code,
    p.product_name,
    p.category,
    p.price,
    p.stock_quantity,
    p.low_stock_threshold,
    CASE 
        WHEN p.stock_quantity = 0 THEN 'Out of Stock'
        WHEN p.stock_quantity <= p.low_stock_threshold THEN 'Low Stock'
        ELSE 'In Stock'
    END AS stock_status,
    p.is_active
FROM products p
WHERE p.is_active = 1;

DROP VIEW IF EXISTS `v_sales_report`;
CREATE VIEW `v_sales_report` AS
SELECT 
    CAST(o.created_at AS DATE) AS order_date,
    COUNT(DISTINCT o.order_id) AS total_orders,
    SUM(o.total_amount) AS total_sales,
    AVG(o.total_amount) AS average_order_value,
    SUM(CASE WHEN o.order_status = 'delivered' THEN o.total_amount ELSE 0 END) AS delivered_sales,
    SUM(CASE WHEN o.order_status = 'cancelled' THEN o.total_amount ELSE 0 END) AS cancelled_sales
FROM orders o
GROUP BY CAST(o.created_at AS DATE)
ORDER BY order_date ASC;

DROP VIEW IF EXISTS `v_top_products`;
CREATE VIEW `v_top_products` AS
SELECT 
    p.product_id,
    p.product_name,
    p.category,
    COUNT(oi.order_item_id) AS times_ordered,
    SUM(oi.quantity) AS total_quantity_sold,
    SUM(oi.subtotal) AS total_revenue
FROM products p
JOIN order_items oi ON p.product_id = oi.product_id
JOIN orders o ON oi.order_id = o.order_id
WHERE o.order_status <> 'cancelled'
GROUP BY p.product_id
ORDER BY total_revenue DESC;
