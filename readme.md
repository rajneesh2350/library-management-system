## Setup Instructions

1. **Create database** using the SQL schema provided
2. **Update configuration** in `config/database.php`
3. **Set proper file permissions** (755 for directories, 644 for files)
4. **Access the system** via browser

This complete Library Management System includes:
- ✅ Professional UI with Bootstrap
- ✅ Responsive design
- ✅ SweetAlert for notifications
- ✅ Font Awesome icons
- ✅ Complete CRUD operations
- ✅ Inventory management
- ✅ Transaction tracking
- ✅ Dashboard with statistics
- ✅ DataTables for sorting/filtering
- ✅ Proper validation
- ✅ Database triggers for automation
 
The system is production-ready and can be deployed on any PHP/MySQL hosting platform.


# Library Management System
A comprehensive web-based Library Management System built with PHP, MySQL, Bootstrap, and JavaScript.
## Features
### Member Management
- Add, edit, delete library members
- Auto-generate member IDs (LIB-MEM-XXXXX)
- Track membership types (Standard, Premium, VIP)
- Email uniqueness validation
### Book Management
- Add, edit, delete books
- Auto-generate book IDs (BOOK-XXXXX)
- Track book inventory (total and available copies)
- Automatic status updates based on availability
### Transaction Management
- Issue books to members with due dates
- Return books
- Automatic inventory updates
- Transaction history tracking
### Dashboard
- Real-time statistics
- Recent transactions display
- Visual indicators for book availability
## Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Frameworks**: Bootstrap 4, jQuery
- **Libraries**: DataTables, SweetAlert2, Font Awesome
## Installation Guide
### Prerequisites
- XAMPP/WAMP/LAMP stack
- PHP 7.4 or higher
- MySQL 5.7 or higher
### Step-by-Step Installation
1. **Clone the repository**
```bash
git clone https://github.com/yourusername/library-management-system.git
# For XAMPP
cp -r library-management-system /opt/lampp/htdocs/
# For WAMP
cp -r library-management-system C:\wamp64\www\
Create Database
Open phpMyAdmin
Create new database: library_management_system
Import sql/database.sql
Configure Database
Open config/database.php
Update database credentials:
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'library_management_system');
define('BASE_URL', 'http://localhost/library-management-system/');
chmod -R 755 library-management-system/
library-management-system/
├── config/             # Database configuration
├── assets/            # CSS, JS, images
│   ├── css/
│   ├── js/
│   └── images/
├── includes/          # Header, footer, sidebar
├── modules/           # Feature modules
│   ├── members/       # Member management
│   ├── books/         # Book management
│   └── transactions/  # Transaction management
├── api/               # API endpoints
├── sql/               # Database schema
├── dashboard.php      # Main dashboard
└── index.php          # Landing page
Save changes
Managing Books
Add Book
Navigate to Books
Click "Add New Book"
Enter book details
Set total copies
Issue Book
Click "Issue Book" in sidebar
Select member and book
Set due date
Submit
Return Book
Click "Return Book" in sidebar
Select issued transaction
Set return date
Submit
Viewing Reports
Dashboard shows key statistics
Transaction history shows all activities
Search and filter available in all tables
Database Schema
library_members
Stores member information
Auto-generated member IDs
Email uniqueness enforced
books
Stores book inventory
Auto-generated book IDs
Tracks available copies
transactions
Records all book issues/returns
Updates book availability automatically
Maintains transaction history
Features in Detail
Auto-generation
Member IDs: LIB-MEM-XXXXX
Book IDs: BOOK-XXXXX
Transaction IDs: TXN-XXXXX
Validation
Email format validation
Unique email constraint
Due date validation
Return date validation
Inventory Management
Available copies automatically update
Book status updates based on availability
Cannot issue unavailable books
Security Features
SQL injection prevention (mysqli real escape)
Session management
XSS protection
Input validation
Performance Optimization
Indexed database tables
Optimized queries
DataTables for efficient listing
Responsive design for all devices
Browser Support
Chrome (latest)
Firefox (latest)
Safari (latest)
Edge (latest)
Opera (latest)
Troubleshooting
Database Connection Error
Check database credentials in config/database.php
Ensure MySQL service is running
Verify database exists
404 Errors
Check BASE_URL in config
Ensure correct file permissions
Verify .htaccess configuration
Form Submission Issues
Check PHP error logs
Enable error reporting during development
Contributing
Fork the repository
Create feature branch (git checkout -b feature/AmazingFeature)
Commit changes (git commit -m 'Add AmazingFeature')
Push to branch (git push origin feature/AmazingFeature)
Open Pull Request
License
This project is licensed under the MIT License.
Support
For support, email: support@libraryms.com
Acknowledgments
Bootstrap Team
jQuery Team
DataTables
SweetAlert2
Font Awesome
Version History
v1.0.0 (2024)
Initial release
Basic member, book, transaction management
Dashboard and reporting
Future Enhancements
Fine calculation for late returns
Email notifications
Barcode scanning
Advanced reporting
API for mobile app
Multi-branch support
User roles and permissions
