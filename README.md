# 🌍 TerraSystcm

> **A Web-Based Digital Land Administration Support System for Cameroon**

![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow)
![License](https://img.shields.io/badge/License-Apache%202.0-green)

---

# 📖 Overview

TerraSystcm is a web-based Land Administration Support System designed to modernize and digitize land management services in Cameroon.

The project was developed following an internship carried out at the **Cadastre and Land Affairs Service (MINDCAF), Douala Bonanjo**, where existing land administration processes were studied, analyzed and transformed into a complete digital workflow.

The platform aims to reduce paperwork, improve transparency, accelerate land transactions and provide efficient collaboration between all stakeholders involved in land administration.

---

# 🎯 Objectives

The project aims to:

- Digitize land administration procedures
- Reduce manual paperwork
- Improve transparency
- Minimize document fraud
- Accelerate land application processing
- Centralize land information
- Improve collaboration between departments
- Provide application tracking for citizens
- Improve security of land records

---

# 🏛 Institution

**Ministry of State Property, Surveys and Land Tenure (MINDCAF)**

Internship Location:

Cadastre and Land Affairs Service

Douala Bonanjo

Cameroon

Internship Period:

**27 February 2026 – 27 March 2026**

---

# 🚀 Main Features

## Citizen Services

✔ Direct Land Immatriculation

✔ Concession Application

✔ Land Verification

✔ Land Division

- Morcellement
- Lotissement

✔ Land Transfer

✔ Application Tracking

✔ Notifications

---

## Administrative Services

### DO Officer

- Review applications
- Approve / Reject applications
- Forward files to Committee

---

### Committee

- Verify Mise en Valeur
- Field Investigation
- Generate Committee Report
- Forward applications to Land Affairs

---

### Land Affairs

- Review Committee Reports
- Validate Legal Documents
- Approve Applications
- Forward to Surveyor

---

### Surveyor (Géomètre)

- Parcel Survey
- Boundary Verification
- GPS Coordinates
- Produce Survey Report

---

### Conservation

- Generate Land Title
- Register Ownership
- Archive Documents

---

### Administrator

- Manage Users
- Manage Roles
- View Reports
- System Statistics
- Workflow Monitoring

---

# 👥 User Roles

- Citizen
- DO Officer
- Committee
- Land Affairs Officer
- Surveyor
- Conservation Officer
- Notary
- Delegate
- Administrator

---

# 🔄 Workflow

Citizen

↓

Submit Application

↓

DO Officer Review

↓

Committee Verification

↓

Land Affairs Validation

↓

Surveyor Inspection

↓

Conservation Registration

↓

Land Title Issued

---

# 🛠 Technologies Used

## Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript

---

## Backend

- PHP 8

---

## Database

- MySQL

---

## Server

- Apache
- XAMPP

---

## Version Control

- Git
- GitHub

---

# 🗄 Database

Main database:

```
terra_systcm
```

Main Tables

- users
- land_applications
- land_parcels
- land_ownerships
- direct_immatriculation
- concession
- land_divisions
- land_transfers
- land_verifications
- workflow_stages
- notifications
- documents
- payments
- land_immatriculations

---

# 📂 Project Structure

```
TerraSystcm/

│

├── admin/

├── API/

├── assets/

│ ├── css/

│ └── js/

├── citizen/

├── committee/

├── config/

├── conservation/

├── database/

├── DO_officer/

├── geometre/

├── land_officer/

├── misa_a_jour/

├── notary/

├── uploads/

│

├── index.php

├── login.php

├── register.php

├── logout.php

└── README.md
```

---

# 🔐 Authentication

Role-based authentication has been implemented.

Each authenticated user is redirected to their own dashboard according to their assigned role.

Supported Roles

- Citizen
- DO Officer
- Committee
- Land Officer
- Surveyor
- Conservation
- Administrator
- Notary
- Delegate

---

# 📋 Current Modules

| Module | Status |
|---------|--------|
| Login System | ✅ |
| Registration | ✅ |
| Citizen Dashboard | ✅ |
| DO Dashboard | ✅ |
| Committee Dashboard | ✅ |
| Land Affairs Dashboard | 🚧 |
| Surveyor Dashboard | 🚧 |
| Conservation Dashboard | 🚧 |
| Notifications | ✅ |
| Workflow | ✅ |
| Reports | 🚧 |

---

# 🌐 Future Improvements

- GIS Integration
- Interactive Map
- Mobile Money Payments
- Orange Money Payments
- Email Notifications
- SMS Notifications
- QR Code Verification
- Digital Signature
- PDF Certificate Generation
- Blockchain Land Records
- Cloud Deployment
- REST API

---

# 💻 Installation

Clone the repository

```bash
git clone https://github.com/FalconYp/TerraSystcm.git
```

Move the project into XAMPP

```
C:\xampp\htdocs\
```

Import the database

```
terra_systcm.sql
```

Configure database connection

```
config/database.php
```

Start

- Apache

- MySQL

Open

```
http://localhost/TerraSystcm
```

---

# 📸 Screenshots

Coming Soon

- Landing Page

- Citizen Dashboard

- DO Officer Dashboard

- Committee Dashboard

- Land Affairs Dashboard

- Surveyor Dashboard

- Conservation Dashboard

---

# 📊 Internship Project

This software was developed as part of a Bachelor's Degree Internship conducted at:

**Cadastre and Land Affairs Service**

Douala Bonanjo

Cameroon

The project was proposed to support the digital transformation of land administration processes and improve efficiency, transparency and collaboration between stakeholders.

---

# 👨‍💻 Author

**Esdras Tamo**

Software Engineering Student

IT Technician

Front-End Developer

GitHub:

https://github.com/FalconYp

---

# 📜 License

This project is licensed under the Apache 2.0 License.

---

# ⭐ Support

If you like this project, don't forget to ⭐ the repository.

Contributions, suggestions and feedback are always welcome.

---

## TerraSystcm

*"Digitalizing Land Administration for a Transparent Future."*
