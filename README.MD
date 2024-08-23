# Simple Referral System

Version: 1.2.0

A simple referral system plugin for WordPress with Gutenberg block support.

## Description

This plugin adds a basic referral system to your WordPress site. It allows users to generate referral links and track their referrals. Now with Gutenberg block support!

## Features

1. Generates unique referral links for logged-in users
2. Tracks referrals when new users register through referral links
3. Displays a list of referrals for each user
4. Stores referral information in cookies for 30 days
5. Provides a Gutenberg block for easy insertion of referral links in the block editor

## Usage

### Shortcodes

1. `[referral_link]` - Displays the referral link for the current user with a copy button
2. `[referral_list]` - Shows a list of referrals made by the current user

### Gutenberg Block

Use the "Referral Link" block in the Gutenberg editor to easily insert your referral link into posts or pages.

### Filters

- `simple_referral_copy_button_text` - Allows modification of the "Copy Link" button text

## Installation

1. Upload the plugin files to the `/wp-content/plugins/simple-referral-system` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## Changelog

### 1.2.0
- Added Gutenberg block support for easy insertion of referral links

### 1.1.0
- Added cookie functionality to store referral information for 30 days
- Improved code documentation
- Added README.md file

### 1.0.0
- Initial release

## Author

redmonkey73

## License

This project is licensed under the GPL v2 or later.