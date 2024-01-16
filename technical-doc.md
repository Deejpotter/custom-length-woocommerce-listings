# Plugin Technical Documentation

## Plugin Structure

The plugin, named "Custom Length WooCommerce Listings," is structured to enhance WooCommerce by allowing custom length selections for products, specifically aimed at 40 Series extrusions. The plugin's directory and file structure is as follows:

- **Root Directory (`custom-length-woocommerce-listings`)**: Contains the main plugin file, README, and other configuration files.
  - `custom-length-woocommerce-listings.php`: The main plugin file containing the plugin header and initialization logic.
  - `class-wapf.php`: Core class file for initializing and orchestrating major functionalities.
  - `readme.md` and `technical-doc.md`: Documentation files.

- **Includes Directory (`includes/`)**: Houses all PHP logic scripts required by the plugin.
  - `api/`: Functions related to API operations.
  - `classes/`: Contains classes defining the logic and data structure of the plugin.
  - `controllers/`: Controller classes manage business logic and user requests.
  - `models/`: Model classes interact with the database and manage data.

- **Views Directory (`views/`)**: Contains the view files for user interfaces.
  - `admin/`: PHP files for the admin-side interface, like settings pages.
  - `frontend/`: PHP files for front-end displays, such as custom product fields.

## Development Plan

### Custom Features

1. **Custom Length Field**:
   - A custom field will be added to the product pages, allowing customers to select desired lengths for 40 Series extrusions.

2. **Price Calculation**:
   - Implement logic to dynamically calculate the price based on custom length selections.

### Stock Management

1. **Adjusting Stock Levels**:
   - Post-order logic to adjust stock levels based on the custom lengths ordered.

2. **Leftover Management**:
   - A system to track and manage leftover pieces from custom cuts.

### Admin Interface

1. **Stock Overview**:
   - An admin interface to view real-time stock levels, including full-length and leftover extrusions.

2. **Order Management Enhancements**:
   - Enhanced order management to include custom length details.

### Testing and Refinement

- Regular functional and compatibility testing.
- Focus on user experience and intuitive interfaces.

### Documentation and Code Comments

- Continuous update of `readme.md` and `technical-doc.md` with comprehensive documentation.

### Refactoring and Optimization

- Regular code optimization and adherence to WordPress and WooCommerce coding standards.
