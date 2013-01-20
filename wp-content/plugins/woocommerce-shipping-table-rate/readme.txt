=== WooCommerce Table Rate Shipping ===

Table rate shipping lets you define rates depending on location, price, weight, or item count. This plugin requires WooCommerce to be installed.

== Usage ==

Table rates can be administered from WooCommerce > Settings > Shipping Methods

= Setting up rates =

Each table rate is made up of the following attributes:

* Countries/state - List of countries the rate can apply to.
* Zip/Postcode - Postcode(s) the rate is for. Comma separated. Use a * as a wildcard. E.g. P* would match postcodes starting with 'p'
* Exclude Zip/Postcode - Postcode(s) the rate is NOT for. Comma separated. Use a * as a wildcard. E.g. P* would match postcodes starting with 'p'
* Condition - The condition to use against the destination
* Min - Min criteria for the condition
* Max - Max criteria for the condition
* Cost - Cost of the shipping excluding tax
* Label - Label for the shipping method shown to the user
* Priority - If selected, if this rate matches then it will be the only method shown. Rates at the top of the table get priority (drag and drop to reorder rates)