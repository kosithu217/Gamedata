# Edu Game Kabar- Flash Games Website

A comprehensive web application for educational Flash games built with Laravel, featuring Ruffle Player integration for Flash game playback, multi-language support (English/Myanmar), and a complete admin panel.

## Features

### 🎮 Game Management
- Upload and manage .swf Flash game files
- Ruffle Player integration for modern Flash game playback
- Game categorization by class levels (Grade 1-10)
- Game thumbnails and descriptions
- Play count tracking
- Featured games system

### 📝 Blog System
- Full CRUD blog functionality
- Category-based organization
- Multi-language content support
- Featured posts
- View count tracking
- Rich content management

### 👥 User Management
- Student registration and authentication
- Single session enforcement (one login per user)
- Class level-based categorization
- Admin user management
- User activity tracking

### 🌐 Multi-Language Support
- English and Myanmar language support
- Dynamic language switching
- Localized content for games and blog posts

### 🛡️ Admin Panel
- Complete dashboard with statistics
- Category management (CRUD)
- Game management with file uploads
- Blog post management
- User management
- Session monitoring

### 🔒 Security Features
- Single session per user enforcement
- Admin role-based access control
- CSRF protection
- Secure file uploads
- Session management

## Technology Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Bootstrap 5, Font Awesome, Custom CSS
- **Database**: MySQL
- **Flash Player**: Ruffle Player (WebAssembly)
- **File Storage**: Laravel Storage with symbolic links
- **Authentication**: Laravel built-in authentication
- **Localization**: Laravel localization system

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- Node.js and npm (for frontend assets)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel_game
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=game
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Build Frontend Assets**
   ```bash
   npm run build
   ```

9. **Start the Development Server**
   ```bash
   php artisan serve
   ```

The application will be available at `http://localhost:8000`

## Default Accounts

After running the seeders, you can use these accounts:

### Admin Account
- **Email**: admin@gameworld.com
- **Password**: password123

### Student Account
- **Email**: student@gameworld.com
- **Password**: password123

## File Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── ...             # Public controllers
│   │   └── Middleware/         # Custom middleware
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── lang/                  # Language files (en, mm)
│   └── views/                 # Blade templates
├── routes/
│   └── web.php               # Web routes
└── storage/
    └── app/public/           # File uploads
        ├── games/            # SWF game files
        ├── thumbnails/       # Game thumbnails
        └── blog/            # Blog images
```

## Usage Guide

### For Students
1. Register with your class level and personal information
2. Browse games by category or search
3. Click on games to view details and play
4. Read blog posts for educational content
5. Only one active session allowed per account

### For Administrators
1. Access admin panel at `/admin`
2. Manage categories for different class levels
3. Upload and manage Flash games (.swf files)
4. Create and manage blog posts
5. Monitor user accounts and sessions
6. View dashboard statistics

### Game Upload Process
1. Go to Admin Panel → Games → Add Game
2. Fill in game details (title, description, category)
3. Upload .swf file and thumbnail image
4. Set game dimensions and featured status
5. Publish the game

### Multi-Language Content
- Add content in both English and Myanmar
- Users can switch languages using the language switcher
- Content automatically displays in user's selected language

## Key Features Explained

### Single Session Management
- Users can only be logged in from one device/browser at a time
- Automatic logout when logging in from another location
- Session tracking and management in admin panel

### Ruffle Player Integration
- Modern WebAssembly-based Flash player
- No browser plugins required
- Fullscreen support
- Error handling and loading states

### Category System
- Games and blog posts organized by class levels
- Color-coded categories for visual organization
- Easy filtering and browsing

### File Management
- Secure file uploads with validation
- Organized storage structure
- Automatic thumbnail generation support
- Storage linking for public access

## Configuration

### Language Configuration
Add new languages by:
1. Creating language files in `resources/lang/`
2. Adding language options to the switcher
3. Updating the LocaleMiddleware

### Game Categories
Modify categories in `database/seeders/CategorySeeder.php` and re-run:
```bash
php artisan db:seed --class=CategorySeeder
```

### File Upload Limits
Configure in `php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 50M
```

## Security Considerations

- All file uploads are validated and stored securely
- CSRF protection on all forms
- Role-based access control for admin functions
- Session security with single login enforcement
- Input validation and sanitization

## Troubleshooting

### Common Issues

1. **Storage Link Issues**
   ```bash
   php artisan storage:link
   ```

2. **Permission Issues**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

3. **Database Connection**
   - Check `.env` database credentials
   - Ensure MySQL service is running

4. **Flash Games Not Loading**
   - Verify Ruffle Player CDN is accessible
   - Check file paths and storage links
   - Ensure .swf files are properly uploaded

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:
- Check the troubleshooting section
- Review Laravel documentation
- Create an issue in the repository

---

**Game World** - Making learning fun through interactive Flash games! 🎮📚# Gamedata
