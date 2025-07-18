# Laravel Web to Web + API Conversion
<img width="1280" height="720" alt="1000157813" src="https://github.com/user-attachments/assets/2940d8d1-b0e1-4876-863b-29ae51786d55" />


Welcome to the **Laravel Web to API Conversion** repository!  
This repository is built to **teach developers how to convert an existing Laravel Web project into a clean, scalable Web + API structure** using **Laravel 11+**, following modern **best practices**.

This project is the practical companion for my **YouTube playlist**, where I explain every step in detail — from setting up API routes to documenting your API for production.

---

## 📺 **YouTube Playlist**
Watch the full tutorial series on YouTube:  
**[Laravel Web to API Conversion Playlist](https://youtube.com/playlist?list=PLBy71Vfd0SzUkJB6PD4kogNfI1AMVW0nA&si=t8ksr33slMwwiw-X)**

The series covers everything you need to know about API conversion:
- Why and when to create an API for your Laravel project.
- Converting CRUD operations to API endpoints.
- Using **Form Requests**, **Resources**, and the **Service Pattern**.
- Adding **authentication**, **authorization**, and **API tests**.
- Documenting your API with tools like **Swagger**, **Postman**, and **Scribe**.

---

## 📁 **Repository Structure**

This repository is divided into **multiple branches**, where **each branch represents a checkpoint in the timeline**.  
You can switch to any branch to see the project **exactly as it was at that stage** of the series.

### **Branches Overview**
| Branch Name          | Episode / Timeline                              |
|----------------------|-----------------------------------------------|
| `main`               | Final version with Web + API fully integrated |
| `install-api`        | Episode 01: Installing and setting up API routes |
| `first-crud`         | Episode 02: Converting the first CRUD (Categories & Products) |
| `standard-response`  | Episode 03: Creating a standardized API response format |
| `api-resources`      | Episode 04: Using API Resource classes to transform data |
| `form-request`       | Episode 05: Moving validation to Form Request classes |
| `service-classes`    | Episode 06: Refactoring controllers with Service classes |
| `authentication`     | Episode 07: Adding token-based authentication with Sanctum |
| `postman-script`     | Episode 08: Automating login tokens with Postman scripts |
| `authorization`      | Episode 09: Implementing policies, gates, and middleware |
| `api-tests`          | Episode 10: Writing automated API tests |

---

## 🚀 **How to Use This Repository**

### 1. **Clone the repository**
```bash
git clone https://github.com/Abdogoda/WEB2API.git
cd WEB2API
```

2. Checkout a specific branch

To view the project at a specific stage:

git checkout branch-name
# Example:
```bash
git checkout install-api
```

3. Install dependencies

```bash
composer install
```

4. Set up environment

```bash
cp .env.example to .env
```

5. Generate App Key
   ```bash
   php artisan key:generate
   ```

6. Set up the database (SQLITE):  
   ```bash
   php artisan migrate
   ```

7. Start the development server:  
   ```bash
   php artisan serve
   ```

8. Access the app in your browser at `http://localhost:8000`.


---

📚 What You’ll Learn in This Repository

This project demonstrates step-by-step conversion of a Laravel Web project into a Web + API version:

1. API Setup – Install and configure API routes, versioning, and test endpoints.
2. CRUD to API – Convert Web CRUD operations into JSON-based API endpoints.
3. Standardized Responses – Create a BaseApiController to unify API responses.
4. 4. API Resources – Transform models into clean JSON output using Laravel Resource classes.
5. Form Requests – Move validation logic out of controllers.
6. Service Pattern – Refactor and share logic between Web and API layers.
7. Authentication – Implement token-based authentication with Laravel Sanctum.
8. Authorization – Use Policies, Gates, and middleware for access control.
9. API Tests – Build automated tests for all endpoints.
10. API Documentation – Generate and share API docs using Postman.




---

📑 API Documentation

By the final branch, you’ll have:

A Postman Collection with all endpoints.
Examples of requests and responses.



---

🎥 Episode Index

Each episode in the YouTube series corresponds to a branch in this repo:
1. Introduction
2. Install API
3. Convert First CRUD
4. Standardized Responses
5. API Resources
6. Form Requests
7. Service Pattern
8. Authentication
9. Postman Pre-request Script
10. Authorization
11. API Tests
12. Documentation




---

🛠 Tech Stack

Laravel 12+
PHP 8.2+
MySQL
Postman for Documentation
PHPUnit for API Testing


---

## 🔗 Connect & Follow  
- **GitHub:** [@Abdogoda](https://github.com/Abdogoda)  
- **YouTube:** [@Abdulrhman-Goda](https://www.youtube.com/@Abdulrhman-Goda)

This repository is **continuously updated** as new videos are released. **Star this repo** ⭐ to stay updated! 🚀  

