# Rano Floating WhatsApp Chat

A modern, minimal floating WhatsApp chat button plugin for WordPress websites. Easily add a WhatsApp chat button to engage with your visitors and improve customer communication.

![Version](https://img.shields.io/badge/version-1.0.0-green.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)
![License](https://img.shields.io/badge/license-GPL%20v2%2B-orange.svg)

## ✨ Features

- **Modern Design**: Clean, minimal floating chat button that matches any website design
- **Fully Customizable**: Control position, size, color, and animation
- **Mobile Responsive**: Optimized for both desktop and mobile devices
- **Accessibility Ready**: WCAG compliant with keyboard navigation support
- **Multiple Animations**: Choose from pulse, bounce, shake, or no animation
- **Shortcode Support**: Use `[whatsapp_chat]` shortcode anywhere in your content
- **Device Targeting**: Show/hide on mobile or desktop devices
- **SEO Friendly**: Clean, semantic HTML with proper structured data
- **Translation Ready**: Fully internationalized and translation ready
- **Analytics Integration**: Built-in Google Analytics event tracking
- **Performance Optimized**: Lightweight with minimal impact on page speed

## 🚀 Installation

### From WordPress Admin (Recommended)

1. Download the plugin zip file
2. Go to your WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the downloaded zip file
4. Click "Install Now" and then "Activate"

### Manual Installation

1. Upload the `rano-floating-whatsapp-chat` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings → WhatsApp Chat to configure the plugin

## ⚙️ Configuration

### Basic Setup

1. Navigate to **Settings → WhatsApp Chat** in your WordPress admin
2. Enter your WhatsApp phone number (with country code, without + sign)
3. Customize the default message
4. Choose button position and appearance
5. Save settings

### Settings Options

| Setting | Description | Default |
|---------|-------------|---------|
| **Phone Number** | WhatsApp number with country code | - |
| **Default Message** | Pre-filled message in WhatsApp chat | "Hello! How can I help you?" |
| **Position** | Button position on screen | Bottom Right |
| **Size** | Button size in pixels (40-100) | 60px |
| **Color** | Button background color | #25D366 |
| **Animation** | Button animation effect | Pulse |
| **Show on Mobile** | Display on mobile devices | Yes |
| **Show on Desktop** | Display on desktop devices | Yes |

## 🎯 Usage

### Automatic Display

Once configured, the chat button will automatically appear on all pages of your website according to your settings.

### Shortcode Usage

Use the shortcode to add WhatsApp links anywhere in your content:

```php
// Basic usage
[whatsapp_chat]

// With custom phone number
[whatsapp_chat phone="1234567890"]

// With custom message
[whatsapp_chat message="I need help with my order"]

// With custom link text
[whatsapp_chat text="Contact Support"]

// Complete example
[whatsapp_chat phone="1234567890" message="Hello, I need assistance" text="Get Help Now" target="_blank"]
```

### PHP Integration

For developers who want to integrate the chat button programmatically:

```php
// Get plugin settings
$settings = get_option('rfwc_settings', array());

// Check if plugin is active and configured
if (class_exists('RanoFloatingWhatsAppChat') && !empty($settings['phone_number'])) {
    // Plugin is ready to use
}

// Custom WhatsApp URL generation
$phone = '1234567890';
$message = 'Hello from my website!';
$whatsapp_url = "https://wa.me/{$phone}?text=" . urlencode($message);
```

## 🎨 Customization

### CSS Customization

You can customize the appearance by adding CSS to your theme:

```css
/* Custom button styling */
#rfwc-chat-button a {
    background: linear-gradient(45deg, #25D366, #128C7E) !important;
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4) !important;
}

/* Custom hover effects */
#rfwc-chat-button a:hover {
    transform: scale(1.2) rotate(5deg) !important;
}

/* Position adjustments */
#rfwc-chat-button.rfwc-position-bottom-right {
    bottom: 30px !important;
    right: 30px !important;
}
```

### JavaScript Hooks

The plugin provides JavaScript events for developers:

```javascript
// Listen for chat button clicks
$(document).on('rfwc_button_clicked', function(event, data) {
    console.log('WhatsApp button clicked:', data);
    // Your custom code here
});
```

## 📱 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- iOS Safari (latest)
- Chrome Mobile (latest)

## 🔧 Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- jQuery (included with WordPress)

## 🌍 Internationalization

The plugin is translation ready. Currently supported languages:

- English (default)
- Ready for translation to any language

To translate the plugin:

1. Use tools like Poedit or WordPress translation plugins
2. Translate the `.pot` file in the `/languages` directory
3. Save as `.po` and `.mo` files in the same directory

## 📊 Analytics Integration

The plugin automatically tracks button clicks if you have Google Analytics installed:

```javascript
// Google Analytics 4 (gtag)
gtag('event', 'click', {
    event_category: 'WhatsApp Chat',
    event_label: 'Floating Button'
});

// Universal Analytics (ga)
ga('send', 'event', 'WhatsApp Chat', 'click', 'Floating Button');
```

## 🐛 Troubleshooting

### Common Issues

**Button not showing up?**
- Check if phone number is configured in settings
- Verify device visibility settings (mobile/desktop)
- Check if there are CSS conflicts with your theme

**Button appears behind other elements?**
- The button uses `z-index: 9999` by default
- Add custom CSS to increase z-index if needed

**WhatsApp not opening correctly?**
- Ensure phone number includes country code without + sign
- Check if WhatsApp is installed on mobile devices

### Debug Mode

To enable debug information, add this to your `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Development Setup

1. Clone the repository
2. Make your changes
3. Test thoroughly
4. Submit a pull request

## 📝 Changelog

### Version 1.0.0
- Initial release
- Floating WhatsApp chat button
- Admin settings page
- Shortcode support
- Multiple animations
- Mobile responsive design
- Accessibility features
- Analytics integration

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 👨‍💻 Author

**Ranojit Kumar**
- GitHub: [@RanojitKumar](https://github.com/RanojitKumar)

## 🙏 Support

If you find this plugin helpful, please consider:
- ⭐ Starring the repository
- 🐛 Reporting issues
- 💡 Suggesting new features
- 🤝 Contributing to the codebase

---

**Need help?** [Open an issue](https://github.com/RanojitKumar/rano-floating-whatsapp-chat/issues) or contact us through WhatsApp using our own plugin! 😉