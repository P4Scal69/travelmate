# TravelMate — Smart Trip Planner & Travel Companion

**Course:** STIWK2114 Mobile Programming
**Framework:** WebView-Hybrid (Cordova shell + remote PHP web app)
**Theme:** Tourism / Travel planning

A mobile application that lets users plan trips, track budgets, capture photos,
pin trip locations on a map, and check live weather — all backed by a PHP + MySQL
backend and packaged as an installable Android APK.

---

## 1. Features implemented (mapping to the assignment)

| Requirement | Where it lives |
| --- | --- |
| Theme (tourism) | Whole app |
| Database ≥1 table, ≥7 fields, mixed types, unique ID | `sql/schema.sql` → `trips` (12 fields) |
| CRUD: Add / Update / Delete / Search / List | `trip_form.php`, `api/delete.php`, `trips.php`, `home.php` |
| CSS mobile-friendly UI | `assets/css/style.css` |
| JavaScript / jQuery interaction | `assets/js/app.js` |
| PHP server-side + DB | `includes/*.php`, `api/*.php` |
| Sessions / LocalStorage | `includes/auth.php` (PHP session) + `app.js` (localStorage) |
| Device capability (GPS, camera, battery) | `app.js` (geolocation, `<input capture>`, `getBattery`) |
| Third-party library (≠ jQuery) | Leaflet (maps) + Chart.js (stats) |
| External API, ≥2 data elements | Open-Meteo weather (`api/weather.php`) → temperature + description (+ humidity, wind) |
| ≥5 connected screens, mobile nav | Login, Home, Trips, Add/Edit, Detail, Profile (6 screens) |
| Installable APK | `build/` Cordova project (`config.xml` + `www/index.html`) |

---

## 2. Database

Import the schema (creates database `travelmate`, tables `users` + `trips`, seed data):

```bash
mysql -u root -p < sql/schema.sql
```

`trips` table fields (12):
`id` (INT, PK, **unique**), `user_id` (INT), `destination` (VARCHAR),
`country` (VARCHAR), `start_date` (DATE), `budget` (INT), `rating` (TINYINT),
`notes` (TEXT), `latitude` (DECIMAL), `longitude` (DECIMAL), `photo` (VARCHAR),
`created_at` (DATETIME).

Data types demonstrated: **Integer** (id, user_id, budget, rating), **String/Text**
(destination, country, notes, photo), **Date** (start_date, created_at),
**Decimal** (latitude, longitude).

---

## 3. Run locally (XAMPP)

1. Copy the `travelmate/` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Import the database: `mysql -u root < sql/schema.sql` (or use phpMyAdmin).
4. If your MySQL `root` has a password, set it in `includes/config.php`
   (`define('DB_PASS', 'yourpassword');`).
   Note: `includes/config.php` is gitignored (contains secrets). Copy
   `includes/config.sample.php` → `includes/config.php` and fill in your
   credentials for local/deployment use.
5. Open `http://localhost/travelmate/` in a browser (or the WebView app).
6. Log in with **student / student123** (or register a new account).

> Demo data: 4 sample trips are inserted on import.

---

## 4. Build the Android APK (Cordova)

The WebView app loads the remote PHP site. For emulator testing the URL
`http://10.0.2.2/travelmate/` points at the host PC. Change `APP_URL` in
`build/www/index.html` to your hosted URL for real devices.

```bash
npm install -g cordova
cordova create travelmate-app com.travelmate.app TravelMate
# copy build/config.xml and build/www/* into the new project (overwrite)
cordova platform add android
cordova build android --release
# signed APK: platforms/android/app/build/outputs/apk/release/app-release.apk
```

Required plugins are listed in `build/config.xml`
(`cordova-plugin-geolocation`, `cordova-plugin-camera`, `cordova-plugin-device`,
`cordova-plugin-whitelist`). A JDK + Android SDK (or Android Studio) must be
installed to produce the signed APK.

---

## 5. Project structure

```
travelmate/
├── index.php            # Login / Register (Session)
├── home.php             # Dashboard + live weather (GPS)
├── trips.php            # List + Search (CRUD)
├── trip_form.php        # Add / Edit (camera + GPS capture)
├── trip_detail.php      # Detail + Leaflet map + weather + photo
├── profile.php          # Stats (Chart.js) + preferences (localStorage)
├── logout.php
├── api/
│   ├── weather.php      # Open-Meteo proxy (external API)
│   └── delete.php       # Delete trip (AJAX)
├── includes/            # config, db (PDO), auth, header, footer
├── assets/
│   ├── css/style.css    # Mobile-first CSS
│   ├── js/app.js        # jQuery logic, device APIs, chart, map
│   └── uploads/         # Captured photos
├── sql/schema.sql       # Database schema + seed
└── build/               # Cordova project for the APK
    ├── config.xml
    └── www/index.html
```

See `docs/report.md` (and printable `docs/report.html`) for the full report,
and `docs/ai-utilization.md` for the AI transparency section.
