# TravelMate — Smart Trip Planner & Travel Companion
### STIWK2114 Mobile Programming · Project Report

---

## Front Page

| | |
| --- | --- |
| **Project Title** | TravelMate — Smart Trip Planner & Travel Companion |
| **Student Name** | [Your Name] |
| **Matric Number** | [MATRIC-XXXX] |
| **Course Code** | STIWK2114 |
| **Group / Class** | [Group / Class] |
| **Lecturer's Name** | [Lecturer Name] |
| **Framework** | WebView-Hybrid (Cordova + PHP/MySQL web app) |

---

## Table of Contents

1. Introduction
2. System Features and Screenshots
3. Conclusion and Future Enhancement
4. AI Utilization and Transparency

---

## 1. Introduction

**Background.** Modern travellers need a single, pocket-sized tool to organise
trips, control spending, and check conditions on the ground. TravelMate is a
mobile application built with a **WebView-hybrid** approach: a PHP + MySQL web
application is rendered inside an Android WebView (packaged with Apache Cordova),
combining web development productivity with native device access.

**Selected theme.** *Tourism / travel planning* — a useful, real-life theme that
naturally exercises databases, maps, device sensors, and live data.

**Purpose.** Let users register, add and manage planned trips (destination,
country, date, budget, rating, notes, photo, GPS location), view them on a map,
and check live weather at each destination.

**Main features developed.**
- Account login/registration with PHP **sessions**.
- Full **CRUD** on trips: add, edit, delete, search, list.
- **Mobile-friendly UI** (CSS) with bottom navigation across 6 screens.
- **jQuery/JS** for validation, live search, toasts, and dynamic content.
- **Device features:** GPS location capture, camera photo capture, battery info.
- **Third-party libraries:** Leaflet (maps) and Chart.js (budget statistics).
- **External API:** Open-Meteo weather (temperature + description + humidity + wind).
- **LocalStorage** for user preferences (dark mode, budget display).
- Installable **Android APK** via Cordova.

---

## 2. System Features and Screenshots

> Replace each placeholder with a screenshot captured from the emulator/browser.
> All screens were tested live on a PHP server; every CRUD operation was verified.

### Screen 1 — Login / Register (`index.php`)
Function: Account entry. Uses PHP session to keep the user logged in. Includes a
toggle between Sign In and Create Account. Demo login: `student` / `student123`.

![Login screen](screens/login.png)

### Screen 2 — Home / Dashboard (`home.php`)
Function: Greeting, summary cards (trip count, total budget), the user's next
upcoming trip, recent trips, and a **"Weather near you"** card that uses **GPS**
to fetch live weather from Open-Meteo.

![Home screen](screens/home.png)

### Screen 3 — My Trips / Search (`trips.php`)
Function: Lists all trips as cards, provides a **search** box (server-side
filter + instant client-side filter), and offers Edit / Delete actions. This is
the **List** and **Search** part of CRUD.

![Trips list screen](screens/trips.png)

### Screen 4 — Add / Edit Trip (`trip_form.php`)
Function: The **Add** and **Update** parts of CRUD. Fields: destination, country,
start date, budget, rating, notes. Includes a **📍 GPS** button (geolocation) and
a **camera** input (`capture="camera"`) to attach a photo. Client + server
validation is applied.

![Trip form screen](screens/form.png)

### Screen 5 — Trip Detail (`trip_detail.php`)
Function: Shows full trip info, the attached photo, a **Leaflet map** with a
marker at the trip's GPS coordinates, and a **weather** card that loads live
conditions for the destination via the Open-Meteo API. Provides Edit / Delete.

![Trip detail screen](screens/detail.png)

### Screen 6 — Profile / Stats (`profile.php`)
Function: User summary, a **Chart.js** bar chart of budget per trip, **localStorage**
preferences (dark mode, budget display), and **device** info (platform, screen,
battery via `navigator.getBattery`). Includes logout.

![Profile screen](screens/profile.png)

### Database operation evidence
The `trips` table (12 fields, mixed types, `id` as unique primary key) was
created from `sql/schema.sql`. Add/Update/Delete/Search/List were all executed
and verified through the UI during testing.

---

## 3. Conclusion and Future Enhancement

**Summary.** TravelMate is a complete WebView-hybrid mobile application that meets
all stated requirements: a tourism theme, a relational database with a rich
`trips` table, full CRUD, a mobile-first interface, PHP server-side processing,
jQuery/JS interaction, sessions + localStorage, GPS + camera + battery device
features, the Leaflet and Chart.js libraries, and the Open-Meteo weather API.

**Main achievements.**
- Working end-to-end app verified on a live PHP/MySQL server.
- Clean, modern, mobile-first UI with smooth navigation.
- Real device features and a real external API integrated and tested.

**Limitations.**
- The WebView shell loads a remote URL, so the app needs network access to the
  server (typical for hybrid apps of this kind).
- Photos are stored on the server filesystem; a cloud/CDN store would scale better.
- Weather is "current conditions" only (no forecast range yet).

**Future enhancements.**
- Offline cache (Service Worker / local SQLite) for use without signal.
- Trip sharing, multi-user collaboration, and itinerary day-planning.
- Push notifications for upcoming trips and weather alerts.
- Currency conversion and multi-currency budgets.
- Cloud photo storage and image compression.

---

## 4. AI Utilization and Transparency

See the separate file **`docs/ai-utilization.md`** (also included in the
submission as AI evidence). It covers: (a) the AI prompts used, (b) the strategy
when using AI, (c) the main ways AI was used (initial code, UI design, logic
explanation, debugging, PHP/database, API integration), and (d) a reflection on
AI's role in the workflow. In short, AI was used as a draft generator and
pair-programmer; all output was adapted, run, and verified before inclusion.
