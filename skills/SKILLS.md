---
name: sketchplanner-laravel-usj2
description: "Best practices for developing SketchPlanner — a geotechnical foundation validation platform — using Laravel (PHP), JavaScript, and HTML. Use when generating controllers, models, migrations, services, blade views, or API endpoints related to soil investigation (sondir/CPT), foundation calculations (Meyerhof/LRFD), SAP2000 integration, SNI compliance, or project management features. Triggers on: Laravel, PHP, Blade, geotechnical, Meyerhof, LRFD, sondir, CPT, pondasi, settlement, SNI, SketchPlanner, SAP2000, seismic."
---

# SketchPlanner — Laravel Development Skill

## Goal

Guide AI to generate high-quality, idiomatic Laravel code for the **SketchPlanner** platform — a technical validation system for foundation design on challenging Indonesian soils (peat/gambut), focusing on the integration of field data (Sondir), structural loads (SAP2000), and national standards (SNI).

---

## Tech Stack

| Layer        | Technology                              |
| ------------ | --------------------------------------- |
| Backend      | PHP 8.2+, Laravel 11.x                  |
| Frontend     | JavaScript (Vanilla / Alpine.js), HTML5 |
| Styling      | Tailwind CSS                            |
| Database     | MySQL 8.0                               |
| Storage      | AWS S3 (PDF & DXF reports)              |
| Realtime DB  | Cloud Firestore (team collaboration)    |
| External API | Google Maps API, PuSGeN API             |
| Auth         | Laravel Sanctum (API) + 2FA             |

---

## Project Structure

Follow feature-based organization to ensure scalability:
app/
├── Http/
│ ├── Controllers/
│ │ ├── Project/
│ │ ├── Soil/ # Sondir input & CSV mapping
│ │ ├── Integration/ # SAP2000 importer, Seismic lookup
│ │ └── Calculation/ # Meyerhof, LRFD, Pile Group logic
│ ├── Requests/ # Validation for Geotechnical & SAP data
│ └── Resources/ # API Resources (FoundationResultResource)
├── Models/
│ ├── Project.php
│ └── MeyerhofCalculation.php
├── Services/
│ ├── MeyerhofService.php # Core Engine
│ ├── SeismicService.php # Location-based seismic lookup
│ ├── SapImportService.php # SAP2000 CSV parser
│ └── ReportService.php # PDF Generation with SNI references
├── Enums/
│ └── FoundationStatus.php # SAFE (LAYAK), WARNING (MARGIN), DANGER (BAHAYA)
resources/
├── views/
│ ├── layouts/
│ ├── calculation/ # Meyerhof UI & Results view
│ └── pdf/ # SNI-compliant report templates

---

## Happy Path Workflow (User Journey 2)

### 1. Project Localization & Seismic Data (US01)

- **Action:** User selects project location via GPS/Interactive Map.
- **Logic:** `SeismicService` performs a lookup to get $S_s$ and $S_1$ values based on regional seismic hazard maps (SNI 1726:2019).
- **Fallback:** Use static mapping for major Indonesian cities if API is unavailable.

### 2. Structural Load Integration (US05)

- **Action:** User uploads SAP2000 "Joint Reactions" table in CSV format.
- **Parser Logic:** `SapImportService` maps columns (e.g., `Joint`, `F3` as Vertical Load) to internal variables.
- **Validation:** Ensure vertical load is positive and units are converted to kN.

### 3. Geotechnical Input (US02)

- **Action:** Input CPT (Sondir) data ($q_c$ and $f_s$) via spreadsheet-like interface.
- **Offline Sync:** Store locally in IndexedDB; sync to `meyerhof_calculations` table once online.

### 4. Meyerhof & LRFD Calculation (US06, US07, US18)

- **Engine:** `MeyerhofService` calculates $Q_u$ (Ultimate) and $Q_a$ (Allowable).
- **Group Efficiency:** Apply Converse-Labarre formula if pile count ($n \times m$) > 1.
- **LRFD Validation:** Check $\phi R_n \geq 1.4 \times P_{\text{sap2000}}$ (Simplified factored load).
- **Peat Trigger:** Automatic `DANGER` status if $q_c < 5$ and $R_f > 5\%$.

### 5. Technical Reporting (US13)

- **Action:** Export PDF report.
- **Content:** Must include specific SNI clauses (SNI 1726:2019, SNI 1727:2020) and a digital signature field.

---

## Coding Conventions

### Services (Stateless & Injected)

All business logic must reside in Services. Example for `MeyerhofService`:

````php
// Meyerhof formula: Qu = (qc_avg * Ap) + (fs_avg * As)
// 1 kgf/cm² = 98.0665 kPa
$qu_kn = ($qc_avg_kpa * $Ap) + ($fs_avg_kpa * $As);
$qa_kn = $qu_kn / $safetyFactor;

Models & Migrations
Always include soft deletes and JSON casting for complex geotechnical data.
protected $casts = [
    'status' => FoundationStatus::class,
    'sondir_data' => 'array',
    'seismic_data' => 'array',
];

Berikut adalah file skill.md yang telah disusun secara komprehensif untuk mencakup keseluruhan alur Happy Path pada User Journey 2 (Validasi Standar Keamanan Nasional) serta fitur Meyerhof yang sudah ada.

Markdown
---
name: sketchplanner-laravel
description: 'Best practices for developing SketchPlanner — a geotechnical foundation validation platform — using Laravel (PHP), JavaScript, and HTML. Use when generating controllers, models, migrations, services, blade views, or API endpoints related to soil investigation (sondir/CPT), foundation calculations (Meyerhof/LRFD), SAP2000 integration, SNI compliance, or project management features. Triggers on: Laravel, PHP, Blade, geotechnical, Meyerhof, LRFD, sondir, CPT, pondasi, settlement, SNI, SketchPlanner, SAP2000, seismic.'
---

# SketchPlanner — Laravel Development Skill

## Goal
Guide AI to generate high-quality, idiomatic Laravel code for the **SketchPlanner** platform — a technical validation system for foundation design on challenging Indonesian soils (peat/gambut), focusing on the integration of field data (Sondir), structural loads (SAP2000), and national standards (SNI).

---

## Tech Stack

| Layer        | Technology                              |
|------------- |-----------------------------------------|
| Backend      | PHP 8.2+, Laravel 11.x                  |
| Frontend     | JavaScript (Vanilla / Alpine.js), HTML5 |
| Styling      | Tailwind CSS                            |
| Database     | MySQL 8.0                               |
| Storage      | AWS S3 (PDF & DXF reports)              |
| Realtime DB  | Cloud Firestore (team collaboration)    |
| External API | Google Maps API, PuSGeN API             |
| Auth         | Laravel Sanctum (API) + 2FA             |

---

## Project Structure

Follow feature-based organization to ensure scalability:

app/
├── Http/
│   ├── Controllers/
│   │   ├── Project/
│   │   ├── Soil/           # Sondir input & CSV mapping
│   │   ├── Integration/    # SAP2000 importer, Seismic lookup
│   │   └── Calculation/    # Meyerhof, LRFD, Pile Group logic
│   ├── Requests/           # Validation for Geotechnical & SAP data
│   └── Resources/          # API Resources (FoundationResultResource)
├── Models/
│   ├── Project.php
│   └── MeyerhofCalculation.php
├── Services/
│   ├── MeyerhofService.php # Core Engine
│   ├── SeismicService.php  # Location-based seismic lookup
│   ├── SapImportService.php # SAP2000 CSV parser
│   └── ReportService.php    # PDF Generation with SNI references
├── Enums/
│   └── FoundationStatus.php # SAFE (LAYAK), WARNING (MARGIN), DANGER (BAHAYA)
resources/
├── views/
│   ├── layouts/
│   ├── calculation/        # Meyerhof UI & Results view
│   └── pdf/                # SNI-compliant report templates


---

## Happy Path Workflow (User Journey 2)

### 1. Project Localization & Seismic Data (US01)
- **Action:** User selects project location via GPS/Interactive Map.
- **Logic:** `SeismicService` performs a lookup to get $S_s$ and $S_1$ values based on regional seismic hazard maps (SNI 1726:2019).
- **Fallback:** Use static mapping for major Indonesian cities if API is unavailable.

### 2. Structural Load Integration (US05)
- **Action:** User uploads SAP2000 "Joint Reactions" table in CSV format.
- **Parser Logic:** `SapImportService` maps columns (e.g., `Joint`, `F3` as Vertical Load) to internal variables.
- **Validation:** Ensure vertical load is positive and units are converted to kN.

### 3. Geotechnical Input (US02)
- **Action:** Input CPT (Sondir) data ($q_c$ and $f_s$) via spreadsheet-like interface.
- **Offline Sync:** Store locally in IndexedDB; sync to `meyerhof_calculations` table once online.

### 4. Meyerhof & LRFD Calculation (US06, US07, US18)
- **Engine:** `MeyerhofService` calculates $Q_u$ (Ultimate) and $Q_a$ (Allowable).
- **Group Efficiency:** Apply Converse-Labarre formula if pile count ($n \times m$) > 1.
- **LRFD Validation:** Check $\phi R_n \geq 1.4 \times P_{\text{sap2000}}$ (Simplified factored load).
- **Peat Trigger:** Automatic `DANGER` status if $q_c < 5$ and $R_f > 5\%$.

### 5. Technical Reporting (US13)
- **Action:** Export PDF report.
- **Content:** Must include specific SNI clauses (SNI 1726:2019, SNI 1727:2020) and a digital signature field.

---

## Coding Conventions

### Services (Stateless & Injected)
All business logic must reside in Services. Example for `MeyerhofService`:
```php
// Meyerhof formula: Qu = (qc_avg * Ap) + (fs_avg * As)
// 1 kgf/cm² = 98.0665 kPa
$qu_kn = ($qc_avg_kpa * $Ap) + ($fs_avg_kpa * $As);
$qa_kn = $qu_kn / $safetyFactor;
Models & Migrations
Always include soft deletes and JSON casting for complex geotechnical data.

PHP
protected $casts = [
    'status' => FoundationStatus::class,
    'sondir_data' => 'array',
    'seismic_data' => 'array',
];


UI/UX Color Semantics
SAFE (LAYAK): bg-green-500 (Qa ≥ Required Load).
MARGIN (WARNING): bg-yellow-500 (Qa within 0-10% margin).
DANGER (BAHAYA): bg-red-500 (Qa < Required Load OR Peat Detected).

````

Domain Rules & ConstraintsCalculation Tolerance: Maximum 0.5% error compared to manual spreadsheet calculations.Safety Factor: Default $SF = 2.5$ for ASD (Meyerhof).Resistance Factor ($\phi$): Default $0.70$ for LRFD driven pile validation.Peat Characteristics: High water content (100-1300%) and low bearing capacity.References for AI ContextSNI 1727:2020: Minimum loads for building design.SNI 1726:2019: Seismic design procedures for Indonesia.Meyerhof Method: Conventional CPT-based pile bearing capacity calculation.
