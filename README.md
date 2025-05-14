# GROUP-12-22RP03920-22RP02034

**📱 USSD-Based Online Ordering System**
**Developed by:** Francois & Frederic
Tech Stack: PHP, MySQL, Africa’s Talking API

👥 **Team Contribution**
**Name	Tasks Completed**

**Francois**	index.php, sms.php, config.php (handles USSD logic, SMS delivery, and configurations)
**Frederic**	menu.php, database.sql (manages menu flow and database structure)

📌 Overview
This system allows users to place and track product orders via USSD (no internet needed). It uses Africa’s Talking API to send confirmation SMS and interact with users.

🔧 **How to Run This Project (from GitHub)**
Clone the Repository
git clone https://github.com/MGFrancois123/GROUP-12-22RP03920-22RP02034.git
**Move into the Project Directory**
cd GROUP-12-22RP03920-22RP02034

**📌 System Summary**
**👤 Unregistered Users**
Main Menu:
1.**Register** (requires Email, Names, PIN, Address)
2.**Help** (explains registration & navigation)
🔹 Default Wallet Balance: 10,000,000

**👨‍💼 Registered Users**
Main Menu:

1.Place Order
2.Check Order Status

**🛒 Order Flow**
Choose Category → Product → Quantity

Add More or Confirm

Enter PIN

Receive Confirmation (USSD + SMS)

📲 Navigation Keys
Key	Action
0	Main Menu
00	Back
98	Next Page (if many items)

**✅ PIN Security**
4-digit PIN required to confirm

Invalid PIN → Retry

Only confirmed orders appear in status

**🧮 Configuration**
Setting	Value
Balance	10,000,000
Currency	Defined by system

**THANK YOU !!!**
