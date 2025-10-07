# Hando Student Reminder System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange.svg" alt="MySQL Version">
  <img src="https://img.shields.io/badge/Bootstrap-5.3.3-purple.svg" alt="Bootstrap Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 About This Project

**Hando Student Reminder System** is a comprehensive web-based application designed specifically for students and administrators at Nasarawa State University, Keffi. Built with Laravel 10 and modern web technologies, this system helps students stay organized, never miss important deadlines, and boost their academic productivity.

### 🎯 Project Overview

This project was developed as a final year Computer Science project to address the common challenges students face in managing academic responsibilities. The system provides:

- **Smart Task Management** - Create, organize, and track assignments, exams, meetings, and personal tasks
- **Intelligent Reminders** - Receive timely notifications via email, SMS, and in-app alerts
- **Role-Based Access Control** - Separate interfaces for students and administrators
- **Beautiful User Interface** - Modern, responsive design with intuitive navigation
- **Real-Time Updates** - Live notifications and status tracking

## 🚀 Features

### 👨‍🎓 For Students
- **Personal Dashboard** - Overview of upcoming tasks and deadlines
- **Task Management** - Create, edit, and categorize academic tasks
- **Smart Notifications** - Choose how and when to receive reminders
- **Progress Tracking** - Monitor task completion and academic performance
- **Calendar Integration** - Visual calendar view of all activities
- **Profile Management** - Customize settings and preferences

### 👨‍💼 For Administrators
- **Admin Dashboard** - System overview and analytics
- **User Management** - Manage student accounts and permissions
- **Task Oversight** - Monitor all student activities and progress
- **System Analytics** - View statistics and engagement metrics
- **Notification Management** - Send system-wide announcements
- **Profile Administration** - Manage admin profile and settings

### 🌟 Key Capabilities
- ✅ **Multi-Notification Channels** (Email, SMS, In-App)
- ✅ **Recurring Tasks** (Daily, Weekly, Monthly, Yearly)
- ✅ **Priority Levels** (Low, Medium, High)
- ✅ **File Attachments** (Documents, Images, PDFs)
- ✅ **Status Tracking** (Pending, In Progress, Completed, Overdue)
- ✅ **Responsive Design** (Desktop, Tablet, Mobile)
- ✅ **Real-Time Notifications**
- ✅ **Secure Authentication**
- ✅ **Role-Based Permissions**

## 🛠️ Technology Stack

- **Backend**: Laravel 10 (PHP 8.2)
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5.3.3, Blade Templates
- **Authentication**: Laravel Sanctum
- **Icons**: Material Design Icons (MDI)
- **Fonts**: Inter Google Font
- **Deployment**: Ready for shared hosting

## 📋 System Requirements

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & npm (for asset compilation)
- Web server (Apache/Nginx)

## ⚡ Quick Start

### 1. Installation

```bash
# Clone the repository
git clone <repository-url>
cd student-remindersystem

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Database Setup

```bash
# Create database and update .env file with your credentials

# Run migrations
php artisan migrate

# Seed the database (creates admin and test users)
php artisan db:seed
```

### 3. Default Accounts

After seeding, you can login with:

**Admin Account:**
- Email: `admin@nsuk.edu.ng`
- Password: `password123`

**Student Account:**
- Email: `student@nsuk.edu.ng`
- Password: `password123`

### 4. Asset Compilation

```bash
# Compile assets for production
npm run build

# Or for development with hot reload
npm run dev
```

### 5. Start the Application

```bash
# Start Laravel development server
php artisan serve
```

Visit `http://localhost:8000` to see the application!

## 📁 Project Structure

```
student-remindersystem/
├── app/                    # Application core (Controllers, Models, etc.)
│   ├── Http/Controllers/   # Request handlers
│   ├── Models/            # Database models
│   └── Middleware/        # Custom middleware
├── database/              # Database migrations and seeders
├── public/               # Public web assets
│   ├── assets/          # CSS, JS, images
│   └── index.php        # Entry point
├── resources/           # Views and assets
│   ├── views/          # Blade templates
│   └── css|js/         # Source assets
├── routes/             # Route definitions
└── storage/           # File storage
```

## 🎨 User Interface

The application features a modern, responsive design with:

- **Landing Page** - Professional homepage showcasing features
- **Authentication** - Clean login/register forms
- **Student Dashboard** - Task management and calendar view
- **Admin Dashboard** - System administration and analytics
- **Mobile-First Design** - Works perfectly on all devices

## 🔒 Security Features

- **CSRF Protection** - Laravel's built-in security
- **Password Hashing** - Secure password storage
- **Role-Based Access** - Admin vs Student permissions
- **Input Validation** - Server-side data validation
- **Session Management** - Secure user sessions

## 📧 Contact Information

**Project Developer:** John Intentsi Bawai Rudeh
- **Email:** [your-email@nsuk.edu.ng](mailto:your-email@nsuk.edu.ng)
- **Student ID:** NSU/NAS/CMP/0854/18/19
- **Department:** Computer Science
- **Institution:** Nasarawa State University, Keffi

**Project Supervisor:** Dr. Binyamin A. Ajayi

## 📚 Academic Context

This project was developed as part of the requirements for the award of **Bachelor of Science (BSc.) in Computer Science** at Nasarawa State University, Keffi, Nigeria.

**Project Title:** Design and Implementation of Hando Student Reminder System

**Academic Year:** 2023/2024

## 🤝 Contributing

This is an academic project, but suggestions and improvements are welcome. Please contact the developer for any contributions or questions.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](https://opensource.org/licenses/MIT) file for details.

## 🙏 Acknowledgments

- **Laravel Framework** - For the excellent web application framework
- **Bootstrap Team** - For the responsive CSS framework
- **Nasarawa State University** - For the academic support and resources
- **Department of Computer Science** - For guidance and supervision

---

<p align="center">Built with ❤️ for students, by a student</p>
