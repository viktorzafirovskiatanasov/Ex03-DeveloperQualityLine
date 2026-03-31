📌 Personal Details Manager (WordPress Plugin)
📖 Overview

Personal Details Manager is a custom WordPress admin plugin that allows administrators to manage personal records stored in a custom database table.

The plugin provides full CRUD functionality along with search and refresh capabilities, making it a simple data management tool inside the WordPress admin panel.

🚀 Features
📋 Record Management
Add new records
Update existing records
Delete records
View all stored records
🔍 Search Functionality
Search by:
First Name
Last Name
Mobile
Email
🔄 Refresh Option
Reset search results
Reload all records instantly
🗄️ Database Integration
Automatically creates a custom table on plugin activation
Stores structured personal data
🛠️ Installation

Download or clone the repository:

git clone <your-repo-url>

Place the plugin file in:

/wp-content/plugins/personal-details-manager/
Activate the plugin:
Go to WordPress Admin → Plugins
Find Personal Details Manager
Click Activate
📍 Usage

After activation:

Navigate to:

WordPress Admin → Personal Details
You will see:
A form to add/update records
A search bar
A table displaying all saved records
🗄️ Database Structure

The plugin creates the following table:

wp_personal_details
Fields:
Field	Type	Description
id	INT (PK)	Auto-increment ID
first_name	VARCHAR(100)	First name
last_name	VARCHAR(100)	Last name
mobile	VARCHAR(50)	Mobile number
email	VARCHAR(150)	Email address
🔧 How It Works
Add Record
Requires all fields:
First Name
Last Name
Mobile
Email
Update Record
Loads selected record into the form
Updates values in the database
Delete Record
Removes record permanently from database
Confirmation prompt before deletion
Search
Performs partial match (LIKE %value%) across all fields
🔐 Permissions

Only users with:

manage_options

capability (Admin role) can access this plugin.

⚠️ Limitations

Let’s be honest—this works, but it’s still assignment-level, not production:

❌ No pagination (loads all records)
❌ No nonce/security validation (important for real apps)
❌ No duplicate email validation
❌ No advanced filtering/sorting
❌ No REST/API support
💡 Possible Improvements

If you had more time (this is great to mention in your interview 👇):

✅ Add nonce verification for security
🔍 Advanced filtering & sorting
📄 Pagination support
📊 Export data (CSV/Excel)
📱 Improve UI/UX (cards, modals)
🔐 Role-based access control
🌐 REST API integration
🧪 Purpose of the Assignment

This project demonstrates:

WordPress plugin development
Custom database table creation (dbDelta)
CRUD operations using $wpdb
Form handling and validation
Admin panel integration
📂 File Structure
personal-details-manager/
│
├── personal-details-manager.php   # Main plugin file
└── README.md
👨‍💻 Author
<img width="1707" height="647" alt="image" src="https://github.com/user-attachments/assets/bbe8f266-c943-4bb6-8b6f-8561eed2b773" />

Viktor Zafirovski Atanasov
