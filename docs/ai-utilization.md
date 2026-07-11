# AI Utilization and Transparency — TravelMate

This section explains how AI tools (used during development) supported the
building of the TravelMate mobile application. It is provided as required by the
assignment brief (Section 7.vi).

## a) AI prompts used during development

The following are representative prompts that were issued to an AI assistant.
They were adapted and verified, not copied blindly:

1. **Project scaffolding**
   > "Design a WebView-hybrid mobile app for STIWK2114 using PHP + MySQL + jQuery +
   > CSS, with CRUD, a device feature, a third-party library, and an external API.
   > Suggest a tourism theme and the database schema (≥7 fields, mixed types, unique ID)."

2. **Database schema**
   > "Write a MySQL schema for a trips table with id, user_id, destination, country,
   > start_date, budget, rating, notes, lat, lon, photo, created_at. Include a users
   > table and sample data."

3. **Leaflet map integration**
   > "Show how to add a Leaflet map with a marker using coordinates passed from PHP
   > to JavaScript via window variables."

4. **Open-Meteo API**
   > "How do I fetch current temperature and weather description from the Open-Meteo
   > API in PHP using cURL, and map weather_code to a description?"

5. **Camera + GPS in a WebView**
   > "How can a hybrid WebView app capture a photo with the camera and read GPS
   > location with the HTML5 geolocation API?"

6. **Chart.js stats**
   > "Render a bar chart of trip budgets with Chart.js from a labels/values array."

7. **Cordova APK packaging**
   > "Create a Cordova config.xml and www wrapper that loads a remote PHP URL, with
   > geolocation and camera plugins, for building an Android APK."

8. **Debugging**
   > "Why does PHP PDO fail to import a schema split on ';' when the file starts with
   > SQL comments?" — led to stripping `--` comments before splitting statements.

## b) Strategy when using AI

AI was used as a **starting point and a pair-programmer**, not as a finished
solution. The workflow was:

- Use AI to draft structure (schema, page list, library choices).
- Write / adapt the actual code into the project files.
- Run the app on a local PHP server and **test every screen and CRUD flow**.
- Ask AI to explain errors and suggest fixes, then verify fixes manually.
- Keep the final code consistent with the project's own conventions (PHP PDO,
  jQuery, mobile-first CSS).

## c) How AI was mainly used

- Generating initial code (schema, page templates, config).
- Improving interface design (mobile-first CSS, bottom navigation, cards).
- Explaining logic (Leaflet/Chart.js setup, geolocation flow).
- Solving errors / debugging (schema import, PDO auth, port conflicts).
- Improving PHP/database operations (prepared statements, parameterized queries).
- API integration (Open-Meteo proxy in PHP).
- Build/packaging guidance (Cordova APK).

## d) Reflection

AI noticeably accelerated repetitive work (boilerplate, schema, library wiring)
and helped untangle bugs faster. However, the assignment requirements (specific
field types, CRUD coverage, device + API + library integration, and a working
installable app) demanded hands-on testing and judgment that AI cannot replace.
The code was adapted, run, and verified locally — AI output was treated as a
draft to be understood and validated, not pasted as-is.
