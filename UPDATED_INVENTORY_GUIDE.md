# Updated Inventory System - Working with Existing Structure

## Your Current Database Structure ✅

You already have a **better normalized structure**:

### Existing Tables:

1. **`ingredients`** - Master ingredient data

   - ingredient_id
   - ingredient_name
   - unit
   - unit_cost
   - stock_quantity
   - low_stock_threshold

2. **`product_ingredients`** - Junction table (links products to ingredients)
   - product_ingredient_id
   - product_id (FK → products)
   - ingredient_id (FK → ingredients)
   - quantity_needed

This is a **BETTER design** than storing ingredients directly! ✅

---

## What You Need to Do

### Step 1: Run the SQL Update

Execute this file in your database:

```
database/update_inventory_system.sql
```

This will:

- ✅ Add `max_stock` column to products
- ✅ Add `ingredients_total_cost` column to products
- ✅ Add `last_stock_update` column to products
- ✅ Create `stock_history` table for audit trail
- ✅ Calculate existing ingredient costs for all products

### Step 2: Use the New API Files

I created two NEW API files that work with your existing structure:

**GET ingredients:**

```
api/products/get_ingredients_relational.php
```

- Use: `?product_id=1` to get ingredients for product 1
- Returns: List of ingredients with calculated costs from your existing tables

**SAVE ingredients:**

```
api/products/save_ingredients_relational.php
```

- POST JSON:

```json
{
  "product_id": 1,
  "ingredients": [
    {
      "ingredient_id": 1,
      "quantity_needed": 2.5
    },
    {
      "ingredient_id": 2,
      "quantity_needed": 0.8
    }
  ]
}
```

- Automatically updates `products.ingredients_total_cost`

---

## What's Different from Original Plan

### Original Schema (inventory_system_schema.sql)

❌ Created a NEW product_ingredients table with embedded ingredient data

- Stored ingredient_name, unit, unit_price directly in product_ingredients
- Less normalized

### Your Existing Schema ✅ BETTER!

✅ Uses relational structure with junction table

- `ingredients` table = master ingredient data
- `product_ingredients` table = links products to ingredients
- More normalized, easier to maintain

---

## SQL Changes Summary

### New Columns Added to `products`:

```sql
ALTER TABLE products ADD COLUMN max_stock INT(11) DEFAULT 50;
ALTER TABLE products ADD COLUMN ingredients_total_cost DECIMAL(10,2) DEFAULT 0.00;
ALTER TABLE products ADD COLUMN last_stock_update TIMESTAMP NULL DEFAULT NULL;
```

### New Table `stock_history`:

```sql
CREATE TABLE stock_history (
  history_id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT FK → products,
  previous_stock INT,
  new_stock INT,
  change_amount INT,
  change_type ENUM('increase','decrease','update','restock','order'),
  updated_by INT FK → users,
  updated_at TIMESTAMP,
  notes TEXT
);
```

---

## Testing the API

### Get Ingredients for Product:

```javascript
fetch("api/products/get_ingredients_relational.php?product_id=1")
  .then((r) => r.json())
  .then((data) => {
    console.log(data.data.ingredients);
    console.log("Total Cost: " + data.data.total_cost);
  });
```

### Save Ingredients:

```javascript
fetch("api/products/save_ingredients_relational.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    product_id: 1,
    ingredients: [
      { ingredient_id: 1, quantity_needed: 2.5 },
      { ingredient_id: 2, quantity_needed: 0.8 },
    ],
  }),
})
  .then((r) => r.json())
  .then((data) => console.log(data));
```

---

## Next Steps

1. ✅ **Execute SQL:** Run `database/update_inventory_system.sql`
2. ✅ **Test API:** Use the \_relational.php files
3. ✅ **Update Frontend:** Follow INVENTORY_IMPLEMENTATION_GUIDE.md
4. ⏳ **Modify update_stocks.php:** Use get_ingredients_relational.php in JavaScript

---

## File Overview

| File                                           | Purpose                                      | Status    |
| ---------------------------------------------- | -------------------------------------------- | --------- |
| `database/update_inventory_system.sql`         | SQL to run (3 ALTERs + 1 CREATE + 1 UPDATE)  | ✅ Ready  |
| `api/products/get_ingredients_relational.php`  | GET ingredients (works with your structure)  | ✅ Ready  |
| `api/products/save_ingredients_relational.php` | POST ingredients (works with your structure) | ✅ Ready  |
| `api/products/get_ingredients.php`             | OLD - doesn't match your DB                  | ⚠️ Ignore |
| `api/products/save_ingredients.php`            | OLD - doesn't match your DB                  | ⚠️ Ignore |
| `database/inventory_system_schema.sql`         | OLD - wrong structure                        | ⚠️ Ignore |

---

## Your Advantage

Your existing relational structure is **better** because:

1. ✅ No duplicate ingredient data across products
2. ✅ Update ingredient cost once, affects all products
3. ✅ Easy to add new ingredients to inventory
4. ✅ Can track ingredient stock levels separately
5. ✅ Proper normalization (3NF)

Just run the SQL update and use the new \_relational.php API files!
