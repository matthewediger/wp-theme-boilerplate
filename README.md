
# WordPress Child Theme Boilerplate (TwentyTwentyFive)

A modern boilerplate for building WordPress child themes based on TwentyTwentyFive, featuring Vite for fast development and Tailwind CSS for utility-first styling.

## Features
- Based on the official TwentyTwentyFive theme
- Vite for lightning-fast development and hot module replacement
- Tailwind CSS for rapid UI development
- Organized file structure for easy customization

## Structure
Your boilerplate contains a folder named `client-twentytwentyfive-child` which holds all the child theme files. Rename `client-twentytwentyfive-child` to your desired theme folder name, ideally using your site's second-level domain (the part before .com, .net, etc.) followed by `-child` (e.g., `example-child`).

```
wp-theme-boilerplate/
└── client-twentytwentyfive-child/
    ├── src/
    │   ├── css/
    │   └── js/
    ├── functions.php
    ├── style.css
    ├── tailwind.config.js
    ├── vite.config.js
    └── ...
```

## Getting Started

### Prerequisites
- Node.js & npm
- WordPress installation with TwentyTwentyFive theme

### Installation
1. Clone this repository into your WordPress `wp-content/themes` directory:
   ```sh
   git clone https://github.com/yourusername/wp-theme-boilerplate.git
   ```
2. Rename the `theme-slug` folder to your desired theme folder name (e.g., `example-twentytwentyfive-child`).
   - Tip: For best practice, use your site's second-level domain (the part before .com, .net, etc.) followed by `-twentytwentyfive-child` (e.g., if your domain is `example.com`, use `example-twentytwentyfive-child`).
3. To quickly update all references:
   - Change all instances of `ThemePackage` to your client's second-level domain (the part before .com, .net, etc.) to match your theme's branding (e.g., if your domain is `example.com`, use `Example`).
    - Use your code editor's global find & replace feature to change all instances of `theme-slug` to your new theme slug (e.g., `example-twentytwentyfive-child`).
    - Use find & replace again to change all instances of `Theme Name` to your new theme name (e.g., `Example Twenty Twenty-Five Child`).
    - Use find & replace once more to update all instances of `ThemePackage` to your desired camelCased PHP namespace (e.g., Example).
    - Alternatively, you can run these terminal commands from your theme folder:
       ```sh
       # Replace 'theme-slug' with 'example-twentytwentyfive-child' in all files
       LC_ALL=C && LANG=C && find . -type f | xargs sed -i '' 's/theme-slug/example-twentytwentyfive-child/g'

       # Replace 'Theme Name' with 'Example Twenty Twenty-Five Child' in all files
       LC_ALL=C && LANG=C && find . -type f | xargs sed -i '' 's/Theme Name/Example Twenty Twenty-Five Child/g'

       # Replace 'ThemeNamespace' with 'ExampleTwentyTwentyFive' in all files
       LC_ALL=C && LANG=C && find . -type f | xargs sed -i '' 's/ThemeNamespace/ExampleTwentyTwentyFive/g'
       ```
4. Install dependencies:
   ```sh
   cd client-twentytwentyfive-child # or your new theme folder name (e.g., example-twentytwentyfive-child)
   npm install
   ```
5. Build assets:
   ```sh
   npm run build
   ```
6. Activate the child theme in the WordPress admin.

### Development
- Start the Vite dev server:
  ```sh
  npm run dev
  ```
- Edit your theme files and Tailwind CSS classes. Changes will be reflected instantly.

## Customization
- Edit `tailwind.config.js` to customize your Tailwind setup.
- Add custom PHP functions in `functions.php`.
- Place your custom styles in `src/css/` and scripts in `src/js/`.

## Resources
- [WordPress Child Themes](https://developer.wordpress.org/themes/advanced-topics/child-themes/)
- [TwentyTwentyFive Theme](https://wordpress.org/themes/twentytwentyfive/)
- [Vite](https://vitejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/)

## License
MIT
