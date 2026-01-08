# Custom Reviews Display

A customizable WordPress plugin for displaying customer reviews with beautiful card layouts.

## Features

- **Easy Backend Management** - Add reviews with header, body, and reviewer name
- **Flexible Display Options** - Use shortcodes to place reviews anywhere
- **Customizable Styling** - Control layout, fonts, colors, and card appearance
- **Star Ratings** - Optional 1-5 star rating system
- **Featured Reviews** - Highlight special reviews
- **Responsive Design** - Looks great on desktop, tablet, and mobile
- **Card-Based Layout** - Modern, clean card design
- **Multiple Layout Options** - Choose number of columns and spacing

## Installation

1. Upload the `custom-reviews-display` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Reviews > Display Settings** to configure your display options
4. Start adding reviews under **Reviews > Add New**

## Usage

### Adding a Review

1. Go to **Reviews > Add New** in your WordPress admin
2. Enter an internal reference title (not displayed publicly)
3. Fill in the review details:
   - **Review Header/Title** - The main title of the review
   - **Review Body** - The full review text
   - **Reviewer Name** - Name of the person who gave the review
   - **Star Rating** - Optional 1-5 star rating
   - **Featured** - Check to highlight this review
4. Click **Publish**

### Displaying Reviews

Use the `[custom_reviews]` shortcode in any post, page, or widget:

```
[custom_reviews]
```

#### Shortcode Parameters

- `limit` - Number of reviews to show (default: all)
  ```
  [custom_reviews limit="6"]
  ```

- `columns` - Number of columns (overrides settings)
  ```
  [custom_reviews columns="4"]
  ```

- `featured_only` - Show only featured reviews
  ```
  [custom_reviews featured_only="true"]
  ```

- `orderby` - Order by: date, rand, title
  ```
  [custom_reviews orderby="rand"]
  ```

- `order` - ASC or DESC (default: DESC)
  ```
  [custom_reviews order="ASC"]
  ```

#### Example Combinations

```
[custom_reviews limit="6" featured_only="true"]
[custom_reviews columns="3" orderby="rand" limit="9"]
[custom_reviews featured_only="true" columns="2"]
```

### Customization Options

Go to **Reviews > Display Settings** to customize:

#### Layout Settings
- Number of columns (1-6)
- Gap between cards
- Mobile columns

#### Card Styling
- Border radius
- Card padding
- Background color
- Shadow effects
- Border style and color

#### Typography
- Header font size and family
- Body font size and family
- Name font size and family
- Custom font support

#### Colors
- Text color
- Header color
- Name color
- Star rating color

## Custom Fonts

To use custom fonts:

1. Load your custom font in your theme (via functions.php or theme settings)
2. Enter the font name in the settings (e.g., "Montserrat, sans-serif")
3. The plugin will apply the font to the selected elements

## Styling Tips

### Using Custom Fonts

If you have custom fonts loaded in your theme, enter them in the font family fields like:

```
Montserrat, sans-serif
Roboto, Arial, sans-serif
"Your Custom Font", Georgia, serif
```

### Card Hover Effects

Cards automatically lift and enhance shadow on hover for better interactivity.

### Featured Reviews

Featured reviews display with:
- Orange border
- "⭐ Featured" badge
- Automatically stand out from regular reviews

## File Structure

```
custom-reviews-display/
├── custom-reviews-display.php (main plugin file)
├── includes/
│   ├── class-reviews-cpt.php (custom post type)
│   ├── class-reviews-admin.php (admin interface)
│   ├── class-reviews-settings.php (settings page)
│   └── class-reviews-shortcode.php (shortcode handler)
├── admin/
│   ├── css/admin-style.css
│   └── js/admin-script.js
├── public/
│   └── css/reviews-style.css
└── README.md
```

## Frequently Asked Questions

**Q: Can I have different settings for different shortcode instances?**
A: Currently, you can override the `columns` parameter per shortcode. Other styling is global.

**Q: How do I change the order of reviews?**
A: Use the `orderby` parameter. Options: `date` (newest first), `rand` (random), `title` (alphabetical).

**Q: Can I add images to reviews?**
A: Not in this version. The plugin focuses on text-based reviews for simplicity.

**Q: Will this work with any theme?**
A: Yes! The plugin is designed to work with any WordPress theme.

**Q: Can I import reviews from Google or other platforms?**
A: Not currently. Reviews are added manually for full control over content and quality.

## Support

For issues, questions, or feature requests, please create an issue on GitHub.

## Changelog

### 1.0.0
- Initial release
- Custom post type for reviews
- Shortcode display with customization options
- Admin settings page
- Star rating support
- Featured review functionality
- Responsive card layout
- Color and typography customization

## Credits

Created by Cliff Cordes

## License

GPL v2 or later
