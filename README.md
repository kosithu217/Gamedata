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

**Game World** - Making learning fun through interactive Flash games! 


## 🚀 API Documentation

The application provides a comprehensive REST API for all major functionalities. All API endpoints are prefixed with `/api/v1/`.

### Base URL
```
http://localhost:8000/api/v1/
```

### Authentication
The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token-here}
```

---

## 🔐 Authentication Endpoints

### Register User
**POST** `/register`

Register a new user account.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "date_of_birth": "1995-01-15",
    "phone": "+1234567890",
    "address": "123 Main St, City",
    "class_levels": ["Grade 5", "Grade 6"]
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "student",
            "class_levels": ["Grade 5", "Grade 6"],
            "is_active": true,
            "created_at": "2024-01-15T10:30:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Login User
**POST** `/login`

Authenticate user and get access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "student",
            "class_levels": ["Grade 5", "Grade 6"],
            "is_active": true,
            "expires_at": "2024-12-31T23:59:59.000000Z",
            "last_login_at": "2024-01-15T10:30:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Get User Profile
**GET** `/profile` 🔒

Get current user's profile information.

**Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "student",
            "class_levels": ["Grade 5", "Grade 6"],
            "date_of_birth": "1995-01-15",
            "phone": "+1234567890",
            "address": "123 Main St, City",
            "is_active": true,
            "expires_at": "2024-12-31T23:59:59.000000Z",
            "last_login_at": "2024-01-15T10:30:00.000000Z",
            "created_at": "2024-01-15T10:30:00.000000Z",
            "updated_at": "2024-01-15T10:30:00.000000Z"
        }
    }
}
```

### Update User Profile
**PUT** `/profile` 🔒

Update current user's profile information.

**Request Body:**
```json
{
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123",
    "phone": "+1234567891",
    "address": "456 Oak St, City"
}
```

### Logout User
**POST** `/logout` 🔒

Logout current user and revoke current token.

**Response (200):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### Logout All Devices
**POST** `/logout-all` 🔒

Logout user from all devices and revoke all tokens.

---

## 🎮 Game Endpoints

### Get All Games
**GET** `/games`

Get paginated list of games with filtering options.

**Query Parameters:**
- `category_id` (optional): Filter by category ID
- `search` (optional): Search in title and description
- `is_featured` (optional): Filter featured games (true/false)
- `per_page` (optional): Items per page (1-100, default: 15)
- `page` (optional): Page number

**Example:** `/games?category_id=1&search=math&is_featured=true&per_page=10`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "games": [
            {
                "id": 1,
                "title": "Math Adventure",
                "title_mm": "သင်္ချာစွန့်စားခန်း",
                "slug": "math-adventure",
                "description": "Fun math learning game",
                "thumbnail": "games/thumbnails/math-adventure.jpg",
                "swf_file_path": "games/math-adventure.swf",
                "width": 800,
                "height": 600,
                "is_featured": true,
                "plays_count": 150,
                "category": {
                    "id": 1,
                    "name": "Grade 5",
                    "slug": "grade-5",
                    "color": "#007bff"
                },
                "created_at": "2024-01-15T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 15,
            "total": 75,
            "from": 1,
            "to": 15
        }
    }
}
```

### Get Featured Games
**GET** `/games/featured`

Get featured games list.

**Query Parameters:**
- `limit` (optional): Number of games to return (1-50, default: 6)

### Get Popular Games
**GET** `/games/popular`

Get most played games.

**Query Parameters:**
- `limit` (optional): Number of games to return (1-50, default: 10)

### Get Single Game
**GET** `/games/{slug}`

Get detailed information about a specific game.

**Response (200):**
```json
{
    "success": true,
    "data": {
        "game": {
            "id": 1,
            "title": "Math Adventure",
            "title_mm": "သင်္ချာစွန့်စားခန်း",
            "slug": "math-adventure",
            "description": "Fun math learning game",
            "thumbnail": "games/thumbnails/math-adventure.jpg",
            "swf_file_path": "games/math-adventure.swf",
            "width": 800,
            "height": 600,
            "is_featured": true,
            "plays_count": 150,
            "category": {
                "id": 1,
                "name": "Grade 5",
                "slug": "grade-5",
                "color": "#007bff"
            }
        }
    }
}
```

### Play Game
**POST** `/games/{slug}/play`

Record game play and get play URL.

**Response (200):**
```json
{
    "success": true,
    "message": "Game play recorded",
    "data": {
        "game": {
            "id": 1,
            "plays_count": 151
        },
        "play_url": "http://localhost:8000/storage/games/math-adventure.swf"
    }
}
```

### Get Games by Category
**GET** `/games/category/{categorySlug}`

Get games filtered by category.

**Query Parameters:**
- `per_page` (optional): Items per page (1-100, default: 15)
- `search` (optional): Search in games

---

## 📂 Category Endpoints

### Get All Categories
**GET** `/categories`

Get list of all active categories.

**Query Parameters:**
- `is_active` (optional): Filter by active status (true/false)
- `per_page` (optional): Enable pagination with items per page

**Response (200):**
```json
{
    "success": true,
    "data": {
        "categories": [
            {
                "id": 1,
                "name": "Grade 5",
                "name_mm": "၅ တန်း",
                "slug": "grade-5",
                "description": "Games for Grade 5 students",
                "color": "#007bff",
                "sort_order": 5,
                "is_active": true,
                "games_count": 25
            }
        ]
    }
}
```

### Get Single Category
**GET** `/categories/{slug}`

Get detailed information about a specific category.

### Create Category (Admin Only)
**POST** `/categories` 🔒👑

Create a new category.

**Request Body:**
```json
{
    "name": "Grade 11",
    "name_mm": "၁၁ တန်း",
    "description": "Games for Grade 11 students",
    "color": "#28a745",
    "sort_order": 11,
    "is_active": true
}
```

### Update Category (Admin Only)
**PUT** `/categories/{id}` 🔒👑

Update an existing category.

### Delete Category (Admin Only)
**DELETE** `/categories/{id}` 🔒👑

Delete a category (only if no games are associated).

---

## 📝 Blog Endpoints

### Get All Blog Posts
**GET** `/blogs`

Get paginated list of blog posts with filtering.

**Query Parameters:**
- `category_id` (optional): Filter by category ID
- `search` (optional): Search in title and content
- `is_featured` (optional): Filter featured posts (true/false)
- `per_page` (optional): Items per page (1-100, default: 15)

**Response (200):**
```json
{
    "success": true,
    "data": {
        "blogs": [
            {
                "id": 1,
                "title": "Learning Tips",
                "title_mm": "သင်ခန်းစာနည်းလမ်းများ",
                "slug": "learning-tips",
                "content": "Here are some great learning tips...",
                "featured_image": "blog/learning-tips.jpg",
                "is_featured": true,
                "views_count": 250,
                "category": {
                    "id": 1,
                    "name": "Grade 5",
                    "slug": "grade-5"
                },
                "created_at": "2024-01-15T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 15,
            "total": 45
        }
    }
}
```

### Get Featured Blog Posts
**GET** `/blogs/featured`

Get featured blog posts.

**Query Parameters:**
- `limit` (optional): Number of posts to return (1-50, default: 6)

### Get Single Blog Post
**GET** `/blogs/{slug}`

Get detailed information about a specific blog post.

### Get Blog Posts by Category
**GET** `/blogs/category/{categorySlug}`

Get blog posts filtered by category.

### Create Blog Post (Admin Only)
**POST** `/blogs` 🔒👑

Create a new blog post.

**Request Body (multipart/form-data):**
```json
{
    "title": "New Learning Method",
    "title_mm": "သင်ခန်းစာနည်းလမ်းအသစ်",
    "content": "Content of the blog post...",
    "content_mm": "ဘလော့ပို့စ်၏အကြောင်းအရာ...",
    "category_id": 1,
    "featured_image": "image_file.jpg",
    "is_featured": true,
    "is_active": true
}
```

### Update Blog Post (Admin Only)
**PUT** `/blogs/{id}` 🔒👑

Update an existing blog post.

### Delete Blog Post (Admin Only)
**DELETE** `/blogs/{id}` 🔒👑

Delete a blog post.

---

## 👥 User Management Endpoints (Admin Only)

### Get All Users
**GET** `/users` 🔒👑

Get paginated list of users with filtering.

**Query Parameters:**
- `role` (optional): Filter by role (admin/student)
- `is_active` (optional): Filter by active status (true/false)
- `search` (optional): Search in name, email, phone
- `per_page` (optional): Items per page (1-100, default: 15)

**Response (200):**
```json
{
    "success": true,
    "data": {
        "users": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "role": "student",
                "class_levels": ["Grade 5", "Grade 6"],
                "phone": "+1234567890",
                "is_active": true,
                "expires_at": "2024-12-31T23:59:59.000000Z",
                "last_login_at": "2024-01-15T10:30:00.000000Z",
                "created_at": "2024-01-15T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 15,
            "total": 75
        }
    }
}
```

### Get Single User
**GET** `/users/{id}` 🔒👑

Get detailed information about a specific user.

### Update User
**PUT** `/users/{id}` 🔒👑

Update user information.

**Request Body:**
```json
{
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "role": "student",
    "class_levels": ["Grade 5", "Grade 6", "Grade 7"],
    "is_active": true,
    "expires_at": "2024-12-31T23:59:59"
}
```

### Delete User
**DELETE** `/users/{id}` 🔒👑

Delete a user (cannot delete admin users).

### Activate User
**POST** `/users/{id}/activate` 🔒👑

Activate a user account.

### Deactivate User
**POST** `/users/{id}/deactivate` 🔒👑

Deactivate a user account and revoke all tokens.

### Extend User Expiry
**POST** `/users/{id}/extend-expiry` 🔒👑

Extend user account expiry date.

**Request Body:**
```json
{
    "expires_at": "2025-12-31T23:59:59"
}
```
OR
```json
{
    "extend_days": 90
}
```

---

## 📊 Response Format

All API responses follow a consistent format:

### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        // Response data here
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

---

## 🔒 Authentication & Authorization

### Symbols Legend:
- 🔒 = Requires authentication (Bearer token)
- 👑 = Requires admin role
- No symbol = Public endpoint

### Token Usage:
```bash
# Include in request headers
Authorization: Bearer 1|abc123def456...
```

### Access Control:
- **Students**: Can only access games and content from their assigned class levels
- **Admins**: Full access to all endpoints and management functions
- **Guests**: Limited access to featured content only

---

## 🚨 Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## 📝 Example API Usage

### JavaScript/Fetch Example:
```javascript
// Login
const loginResponse = await fetch('/api/v1/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password123'
    })
});

const loginData = await loginResponse.json();
const token = loginData.data.token;

// Get games with authentication
const gamesResponse = await fetch('/api/v1/games?per_page=10', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});

const gamesData = await gamesResponse.json();
```

### cURL Examples:
```bash
# Register user
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Get games with authentication
curl -X GET http://localhost:8000/api/v1/games \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# Play a game
curl -X POST http://localhost:8000/api/v1/games/math-adventure/play \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

🎮📚# Game Data API - Complete REST API for Educational Flash Games Platform
