# Egypt Elite Tours – Child Theme

Custom child theme for [egyptet.com](https://egyptet.com) built on the Travil parent theme.

## Features

- Custom phone number field in WP Travel Engine checkout
- Maximum group size limit (20 travellers) with user warning
- Start date hint in booking modal
- Continue button text customization

## Requirements

- WordPress
- Travil Parent Theme
- WP Travel Engine Plugin

## Installation

1. Upload the child theme folder to `/wp-content/themes/`
2. Activate from **Appearance → Themes**

## Files

- `functions.php` — All customizations and hooks
  
## Customizations

### Phone Number Field
Adds a phone number field to the checkout billing form and saves it to the booking post meta.

### Group Size Limit
Limits the total number of travellers (Adults + Children) to a maximum of 20. Displays a warning message if the user tries to exceed the limit.

### Start Date Hint
Shows a hint in the booking modal informing users to select the start date only.

