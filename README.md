# Classroom Availability & Booking System

A modern, responsive, and fully localized (English & Arabic) web application built with **Laravel 11**, **Tailwind CSS**, and **Alpine.js**. The system allows students and lecturers to find, track, and book university classrooms while providing administrators with a powerful dashboard to manage rooms, users, and reservations.

## ✨ Features

- **Multi-Role Authentication**: 
  - **Administrators**: Manage classrooms (add, edit, delete), approve/reject bookings, manage user roles, and view overall system analytics.
  - **Lecturers & Students**: Browse available classrooms, filter by size, capacity, or location, and request bookings for specific dates and times.
- **Dynamic Localization (LTR/RTL)**: Full support for English and Arabic. The UI seamlessly adapts its layout to RTL when Arabic is selected using logical CSS properties.
- **Smart Filtering & Availability Checking**: Instantly search for classrooms and prevent double-booking. The system detects conflicts and intelligently suggests alternative rooms with similar capacities or locations.
- **Favourites System**: Users can save their most frequently used classrooms to a "My Favourites" list for quick access.
- **Modern UI/UX**: Designed with a sleek dark/light mode toggle, dynamic interactive modals, and micro-animations for a premium user experience.

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS 3 (using Logical Properties), Alpine.js
- **Database**: SQLite / MySQL
- **Asset Compilation**: Vite

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and NPM

### Installation

1. **Clone the repository** (if applicable) and navigate to the project directory:
   ```bash
   cd classroom-ava-system
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies:**
   ```bash
   npm install
   ```

4. **Set up the environment:**
   Copy the `.env.example` file to `.env` and configure your database settings.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Database Migrations and Seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Frontend Assets:**
   To build the CSS and JavaScript files for production (vital for the RTL layout):
   ```bash
   npm run build
   ```
   *(Or run `npm run dev` for local active development).*

7. **Serve the Application:**
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://127.0.0.1:8000`.

## 🌐 Localization (Adding Translations)

The application supports English and Arabic. Translations are stored in the `lang/` directory.

To add or modify translations for Arabic:
1. Open `lang/ar.json`.
2. Add your English string as the key and the Arabic translation as the value.
   ```json
   {
       "New English Word": "الكلمة العربية الجديدة"
   }
   ```
3. In your Blade files, wrap the English text in the `__()` helper:
   ```blade
   {{ __('New English Word') }}
   ```
