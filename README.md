# Rev Affiliate Widget Plugin

## Description
The Rev Affiliate Widget Plugin is a WordPress plugin that allows you to display affiliate links for sportsbook odds on your WordPress website. It provides a widget that can be added to your site to show affiliate details and customize the display of odds information.

## Installation

1. Download the plugin files and upload them to your WordPress plugins directory (usually `/wp-content/plugins/`).
2. Activate the "Rev Affiliate Widget" plugin through the WordPress admin panel.

## Usage

1. After activating the plugin, go to your WordPress Widgets page (`Appearance > Widgets`).
2. You will find a widget named "Rev Affiliate Widget" that you can add to your sidebar or other widgetized areas.
3. Configure the widget settings, including your affiliate link, the type of content to display, and the template size.
4. Save the widget settings.

## Widget Settings

- **Affiliate Link**: Enter your affiliate link URL here. This is the link that users will be redirected to when they click on the widget.

- **Content Type**: Choose whether you want to display single-game odds or odds for an entire league.

- **Sport/Game Selection**: Depending on your content type, you can select a specific sport or game. The options available will vary based on your selection.

- **Template Size**: Select the template size for the widget.

- **When Game Ends**: Choose what to display when a game ends, either show the next game or a banner.

- **Preview Options**: You can preview the widget as a pop-up or copy the generated code to embed it on your website.

## Ajax Integration
The plugin uses AJAX to update the widget content without reloading the entire page. The `update_my_variable` function handles the AJAX request to fetch and display the odds information.

## Additional Notes
- The plugin uses transients to cache API responses for 300 seconds (5 minutes) to reduce server load.
- The JavaScript `custom-script.js` file is used for client-side interactions in the widget configuration.

## License
This plugin is released under the [GNU General Public License, version 2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html).

## Support
For support or questions, please contact the plugin author.

## Author
Pardhan singh

## Changelog
- Version 1.0: Initial release.

