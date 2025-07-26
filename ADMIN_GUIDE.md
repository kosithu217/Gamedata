# Edu Game Kabar- Admin Panel Guide

## 🚀 Quick Start

### 1. Start the Server
```bash
php artisan serve
```
Visit: `http://127.0.0.1:8000`

### 2. Admin Login
- **URL**: `http://127.0.0.1:8000/admin`
- **Email**: `admin@gameworld.com`
- **Password**: `password123`

### 3. Student Login (for testing)
- **Email**: `student@gameworld.com`
- **Password**: `password123`

## 📋 Admin Panel Features

### 🏷️ Categories Management (`/admin/categories`)
- **View all categories** with class levels and colors
- **Create new categories** for different subjects/grades
- **Edit existing categories** (name, description, color, class level)
- **Multi-language support** (English/Myanmar)
- **Sort order** for display organization

### 🎮 Games Management (`/admin/games`)
- **Upload .swf games** (up to 50MB)
- **Add thumbnails** for visual appeal
- **Multi-language titles/descriptions**
- **Set game dimensions** (width/height)
- **Mark as featured** for homepage display
- **Category assignment** by class level
- **Track play counts** automatically

### 📝 Blog Posts Management (`/admin/blog-posts`)
- **Create educational content** for students
- **Multi-language posts** (English/Myanmar)
- **Upload featured images**
- **Category assignment** by class level
- **Publish/Draft status** control
- **Featured posts** for homepage
- **View count tracking**

### 👥 Users Management (`/admin/users`)
- **Create student accounts** with class levels
- **Create additional admin accounts**
- **Monitor user activity** and sessions
- **View online status** indicators
- **Manage account status** (active/inactive)

## 📁 File Upload Guidelines

### Games (.swf files)
- **Maximum size**: 50MB
- **Format**: .swf only
- **Storage**: `storage/app/public/games/`
- **Access**: Automatic via Ruffle Player

### Thumbnails
- **Maximum size**: 5MB
- **Formats**: JPEG, PNG, GIF
- **Recommended size**: 800x600px
- **Storage**: `storage/app/public/thumbnails/`

### Blog Images
- **Maximum size**: 5MB
- **Formats**: JPEG, PNG, GIF
- **Storage**: `storage/app/public/blog/`

## 🌐 Multi-Language Content

### Adding Content in Both Languages
1. **English fields** are required
2. **Myanmar fields** are optional but recommended
3. **Automatic language switching** based on user preference
4. **Fallback to English** if Myanmar translation not available

### Language Switching
- Users can switch languages using the top navigation
- Content automatically displays in selected language
- Categories and games show appropriate translations

## 🔒 Security Features

### Single Session Management
- **One login per user** at a time
- **Automatic logout** when logging in elsewhere
- **Session monitoring** in admin panel
- **"Account logged in elsewhere"** message

### Admin Protection
- **Role-based access** control
- **Admin middleware** protection
- **CSRF protection** on all forms
- **File upload validation**

## 📊 Sample Data Included

### Categories (6 total)
- Math Games (Grade 1-5)
- English Learning (Grade 1-8)
- Science Explorer (Grade 3-10)
- Geography Adventures (Grade 4-9)
- History Quest (Grade 5-10)
- Art & Creativity (Grade 1-8)

### Games (7 total)
- Math Adventure ⭐ Featured
- English Word Quest ⭐ Featured
- Science Lab Explorer ⭐ Featured
- Geography Challenge ⭐ Featured
- Advanced Algebra
- Creative Art Studio

### Blog Posts (2 total)
- Welcome to Game World! ⭐ Featured
- Benefits of Educational Gaming

### Users (3 total)
- Admin User (admin@gameworld.com)
- Student Demo (student@gameworld.com)
- Additional test user

## 🎯 Getting Started Checklist

### Initial Setup
- [ ] Start the server (`php artisan serve`)
- [ ] Login to admin panel
- [ ] Review existing categories
- [ ] Check sample games and blog posts

### Content Creation
- [ ] Create categories for your class levels
- [ ] Upload your first .swf game file
- [ ] Add game thumbnail and description
- [ ] Create your first blog post
- [ ] Test the game player with Ruffle

### User Management
- [ ] Create student accounts for testing
- [ ] Test single session enforcement
- [ ] Monitor user activity in admin panel

### Testing
- [ ] Test game playback with Ruffle Player
- [ ] Verify multi-language switching
- [ ] Test responsive design on mobile
- [ ] Check file upload functionality

## 🛠️ Troubleshooting

### Common Issues

**Games not loading:**
- Check .swf file is properly uploaded
- Verify Ruffle Player CDN is accessible
- Ensure storage link is created (`php artisan storage:link`)

**File upload errors:**
- Check file size limits in php.ini
- Verify storage permissions
- Ensure storage directories exist

**Admin panel not accessible:**
- Verify admin user exists
- Check middleware is properly registered
- Ensure you're logged in as admin role

**Language switching not working:**
- Check language files exist
- Verify LocaleMiddleware is registered
- Clear application cache if needed

## 📞 Support

For technical issues:
1. Check the troubleshooting section above
2. Review Laravel logs in `storage/logs/`
3. Verify database connections and migrations
4. Check file permissions and storage links

---

**Happy Gaming and Learning!** 🎮📚