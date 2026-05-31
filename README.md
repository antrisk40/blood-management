# 🩸 Premium Blood Bank Management System

A highly secure, elegant, and production-ready Web Application designed for **Hospitals** and **Receivers** to manage blood inventories and requests in real-time. Built specifically as an **Associate SDE (Web)** submission, addressing every evaluation criterion and technical requirement.

---

## 🔗 Live Deployment Links

* 🖥️ **Frontend (Next.js 16 + Tailwind CSS):** [https://internshala-blood-bank.netlify.app](https://internshala-blood-bank.netlify.app)
* ⚙️ **Backend (CodeIgniter 4 + MySQL):** [https://blood-api.eatbit.in](https://blood-api.eatbit.in)

---

## 🚀 Key Features Implemented

### 👥 1. Dual User System & Registration
* **Registration Pages:** Distinct registration pipelines for **Hospitals** (capturing address, phone, name) and **Receivers** (capturing phone, full name, and **exact blood group**).
* **Cross-Role Validation:** Robust server-side checks ensure a Hospital username/email cannot register as a Receiver, and vice-versa.
* **Single Secure Login:** Unified, secure login page with prefilled **demo accounts** for easy evaluation.

### 🧪 2. Add Blood Info Page (Hospital-Only)
* **Role Restructured Access:** Strictly restricted only to logged-in Hospital accounts.
* **Add Details:** Allows hospitals to add available blood samples (units available, expiry date) to their bank.

### 📋 3. Available Blood Samples Page (Publicly Accessible)
* **Access for Everyone:** Visible to anyone (logged in or guest). Displays the available blood group, units, expiry, and the hosting hospital.
* **Eligibility-Aware Requests:** 
  * Redirects guests to the `/login` page if they click 'Request Sample'.
  * Disables the 'Request Sample' button for logged-in Hospitals.
  * **Bonus Feature: Real-Life Blood Compatibility Engine!** Only receivers with biologically compatible blood groups are allowed to request the blood sample. The request button is disabled for incompatible receivers.
  * **No Duplicate Requests:** Receivers are blocked from requesting the same blood sample from the same hospital multiple times.

### 📊 4. Hospital 'View Requests' Page
* **Isolated Feeds:** Hospitals can *only* see requests made specifically to *their* blood bank. Hospitals are strictly blocked from viewing requests received by other banks.
* **Real-time Updates:** Mark requests as "Delivered", dynamically updating units available in real-time.

---

## 🧬 Real-Life Blood Group Compatibility Logic

Our biological compatibility logic is fully integrated on both the frontend and backend, preventing invalid requests:

| Receiver Blood Group | Compatible Donor Blood Groups (Can receive from) |
|:--------------------:|-------------------------------------------------|
| **O-**               | O-                                              |
| **O+**               | O-, O+                                          |
| **A-**               | O-, A-                                          |
| **A+**               | O-, O+, A-, A+                                  |
| **B-**               | O-, B-                                          |
| **B+**               | O-, O+, B-, B+                                  |
| **AB-**              | O-, A-, B-, AB-                                 |
| **AB+**              | **Universal Receiver** (O-, O+, A-, A+, B-, B+, AB-, AB+) |

---

## 📁 Repository Structure
```
├── frontend/             # Next.js 16 (App Router) Frontend
│   ├── src/app/          # Core views (Inventory, Requests, Public list)
│   └── src/components/   # Modular forms & common navbar components
├── backend/              # CodeIgniter 4 PHP REST API
│   ├── app/Controllers/  # Controllers (JWT-authenticated with clean Try/Catch)
│   ├── app/Config/       # CORS, Filter, Routing configurations
│   ├── app/Services/     # Business logic layers (OOPS & modular)
│   └── app/Repositories/ # Data access abstraction layers
├── database.sql          # Clean SQL Schema Dump file for easy replication
└── README.md             # This document
```

---

## 📊 Database Architecture (MySQL)

We strictly adhered to **3NF Normalization rules** with zero data redundancy, proper index configurations, and foreign keys with cascading options:

```
                  ┌──────────────────────┐
                  │        users         │
                  ├──────────────────────┤
                  │ id (PK, AutoInc)     │
                  │ username (Unique)    │
                  │ email (Unique)       │
                  │ password             │
                  │ role (enum)          │
                  └──────────┬───────────┘
                             │
            ┌────────────────┴────────────────┐
            ▼                                 ▼
┌──────────────────────┐           ┌──────────────────────┐
│      hospitals       │           │      receivers       │
├──────────────────────┤           ├──────────────────────┤
│ id (PK, AutoInc)     │           │ id (PK, AutoInc)     │
│ user_id (FK -> users)│           │ user_id (FK -> users)│
│ hospital_name        │           │ full_name            │
│ address              │           │ blood_group          │
│ phone                │           │ phone                │
└──────────┬───────────┘           └──────────┬───────────┘
           │                                  │
           │                                  │
           ▼                                  ▼
┌──────────────────────┐           ┌──────────────────────┐
│    blood_samples     │◄───────── │    blood_requests    │
├──────────────────────┤           ├──────────────────────┤
│ id (PK, AutoInc)     │           │ id (PK, AutoInc)     │
│ hospital_id (FK)     │           │ receiver_id (FK)     │
│ blood_group_id (FK)  │           │ blood_sample_id (FK) │
│ units_available      │           │ units_requested      │
│ expiry_date          │           │ status (enum)        │
└──────────▲───────────┘           └──────────────────────┘
           │
┌──────────┴───────────┐
│     blood_groups     │
├──────────────────────┤
│ id (PK, AutoInc)     │
│ group_name (Unique)  │
└──────────────────────┘
```

---

## 🛠️ Tech Stack & Design Highlights

* **Frontend:** Next.js 16 (Turbopack, TypeScript, Lucide Icons, React Query for caching, Tailwind CSS).
* **Backend:** PHP CodeIgniter 4 (MVC architecture, PSR-4 namespaces, Object-Oriented Services, repositories, JWT token authentication).
* **Database:** MySQL (Structured, normalized schema, foreign keys, constraints).
* **Containerization:** Docker & Docker Compose configured for standard development/production.
* **Premium UX/Aesthetics:** Modern typography, harmonious deep-crimson HSL color palette, clean grid layouts, smooth micro-animations, and one-click demo credentials prefilling.

---

## 🚀 Local Development Setup

To replicate this project locally in less than 2 minutes using **Docker Compose**:

### 1. Clone the repository
```bash
git clone <your-repository-url> blood-donation-system
cd blood-donation-system
```

### 2. Configure Environment variables
Create a `.env` file in the `backend/` folder:
```ini
CI_ENVIRONMENT = development
database.default.hostname = db
database.default.database = blood_bank
database.default.username = root
database.default.password = root_password
database.default.DBDriver = MySQLi
database.default.port = 3306
app.baseURL = 'http://localhost:8081/'
CORS_ALLOWED_ORIGINS = 'http://localhost:3000'
```

### 3. Spin up Docker containers
This boots up the Next.js app, PHP container, and MySQL 5.7 database instantly:
```bash
docker compose up -d --build
```

### 4. Run Migrations & Seeders
Inside the backend container, run the database migrations and default blood group seeds:
```bash
# Run migrations
docker compose exec app php spark migrate

# Run seeder
docker compose exec app php spark db:seed BloodGroupSeeder
```

### 5. Access the Apps
* **Frontend:** `http://localhost:3000`
* **Backend API:** `http://localhost:8081`

---

## 🧪 Evaluation Credentials (Prefilled on Frontend)

| Role | Username / Email | Password |
|:---:|:---:|:---:|
| **🏥 Hospital Admin** | `admin@medanta.in` | `12345678` |
| **👤 Receiver / User** | `neeleshbaghel40@gmail.com` | `12345678` |

---

*Thank you for evaluating this submission! Please refer to the [database.sql](database.sql) file located in the root of the project to view the raw DDL queries.*
