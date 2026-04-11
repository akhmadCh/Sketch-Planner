---
name: sketchplanner-laravel
description: 'Best practices for developing SketchPlanner — a geotechnical foundation validation platform — using Laravel (PHP), JavaScript, and HTML. Use when generating controllers, models, migrations, services, blade views, or API endpoints related to soil investigation (sondir/CPT), foundation calculations (Meyerhof/LRFD), SNI compliance, or project management features. Triggers on: Laravel, PHP, Blade, geotechnical, Meyerhof, LRFD, sondir, CPT, pondasi, settlement, SNI, SketchPlanner.'
---

# SketchPlanner — Laravel Development Skill

## Goal
Guide AI to generate high-quality, idiomatic Laravel code for the **SketchPlanner** platform — a technical validation system for foundation design on challenging Indonesian soils (peat/gambut), aligned with national standards (SNI).

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

Follow feature-based organization (not layer-based):

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Project/
│   │   ├── Soil/           # Sondir input, soil profile
│   │   ├── Calculation/    # Meyerhof, LRFD, settlement
│   │   └── Report/         # PDF, DXF export
│   ├── Requests/           # Form Request validation
│   └── Resources/          # API Resources (JSON transformers)
├── Models/
├── Services/
│   ├── MeyerhofService.php
│   ├── LRFDService.php
│   ├── SettlementService.php
│   ├── PeatDetectionService.php
│   └── ReportExportService.php
├── Enums/
│   ├── FoundationStatus.php  # SAFE, WARNING, DANGER
│   └── SoilType.php
resources/
├── views/
│   ├── layouts/
│   ├── dashboard/
│   ├── soil/
│   ├── calculation/
│   └── report/
database/
├── migrations/
└── seeders/
```

---

## Coding Conventions

### Controllers
- Keep controllers thin — delegate all business logic to `Service` classes.
- Use **Form Requests** for all input validation.
- Use **API Resources** for all JSON responses.
- Return consistent response structure:

```php
return response()->json([
    'status'  => 'success',
    'message' => 'Perhitungan berhasil.',
    'data'    => new FoundationResultResource($result),
], 200);
```

### Services
- All geotechnical calculation logic lives in dedicated Service classes.
- Services must be stateless and injected via constructor.
- Wrap calculation logic in try-catch for tolerance checking (max 0.5% error).

```php
class MeyerhofService
{
    public function calculateBearingCapacity(SoilData $data): FoundationResult
    {
        // Meyerhof formula implementation
    }
}
```

### Models & Migrations
- Use `fillable` (not `guarded = []`) for mass assignment safety.
- Always define `casts` for JSON columns and enums.
- Soft deletes (`SoftDeletes`) on all core domain models.

```php
protected $casts = [
    'status'     => FoundationStatus::class,
    'sondir_data' => 'array',
];
```

### Enums (PHP 8.1+)
Use backed enums for foundation status and soil classification:

```php
enum FoundationStatus: string
{
    case SAFE    = 'LAYAK';
    case WARNING = 'MARGIN';
    case DANGER  = 'BAHAYA';
}
```

### Validation (Form Requests)
Always validate geotechnical inputs with realistic domain ranges:

```php
public function rules(): array
{
    return [
        'qc'    => 'required|numeric|min:0|max:500',   // kgf/cm²
        'fs'    => 'required|numeric|min:0|max:10',    // kgf/cm²
        'depth' => 'required|numeric|min:0.2|max:60',  // meters
    ];
}
```

---

## Domain Rules & Business Logic

### Meyerhof Bearing Capacity (US06)
- Input: `qc` (cone resistance), `fs` (local friction), pile diameter, pile depth.
- Formula: `Qu = (qc_avg * Ap) + (fs_avg * As)`
- Output must include: `Qu`, `Qa` (allowable = Qu / SF), `SF` (safety factor ≥ 2.5).
- Tolerance vs manual calculation: **≤ 0.5%**.

### LRFD Validation (US07)
- Apply resistance factors: `φ = 0.70` for driven piles.
- Check: `φ * Rn ≥ Σ(γi * Qi)`.
- Reference: SNI 1727:2020 & SNI 1726:2019.

### Peat Detection (US03)
- Flag as gambut/peat if: `Rf > 5%` AND `qc < 5 kgf/cm²`.
- Trigger `FoundationStatus::DANGER` warning automatically.
- Log warning to audit trail.

### Settlement Estimation (US11)
- Use Terzaghi consolidation model.
- Input: layer thickness, compression index (Cc), void ratio (e0), effective stress.
- Output: primary consolidation settlement (Sc) in cm.

---

## API Endpoints Convention

| Method | URI                                      | Description                     |
|--------|------------------------------------------|---------------------------------|
| POST   | `/api/projects`                          | Create new project              |
| POST   | `/api/projects/{id}/sondir`              | Upload sondir data (offline sync)|
| POST   | `/api/projects/{id}/calculate/meyerhof` | Run Meyerhof calculation        |
| POST   | `/api/projects/{id}/calculate/lrfd`     | Run LRFD validation             |
| GET    | `/api/projects/{id}/soil-profile`        | Get soil profile chart data     |
| GET    | `/api/projects/{id}/report/pdf`          | Export PDF report               |
| GET    | `/api/projects/{id}/report/dxf`          | Export DXF (AutoCAD) file       |

---

## Offline Sync Strategy (US02)
- Mobile clients store sondir data in **IndexedDB** when offline.
- On reconnect, client sends a batch `POST /api/projects/{id}/sondir/sync`.
- Server uses **upsert** (updateOrCreate) based on `point_id` + `depth` to avoid duplicates.
- Return `sync_status` for each record: `created | updated | skipped`.

---

## Security Requirements
- Encrypt all project data at rest using **AES-256** (Laravel `encrypt()`).
- Enforce **2FA** via `laravel/fortify` for all users.
- Audit log every change to critical calculation data (observer pattern).

```php
// In AppServiceProvider or EventServiceProvider
Project::observe(ProjectObserver::class);
```

---

## PDF Report Generation (US13)
- Use `barryvdh/laravel-dompdf` for PDF generation.
- Report must include: SNI article references, digital signature field, calculation summary.
- Store generated PDF to **AWS S3** and return a signed URL (expiry: 24h).

---

## Testing Standards
- Use **PHPUnit** with **RefreshDatabase** trait.
- Test each Service class independently (unit tests).
- Use **factories** for all model seeding in tests.
- Calculation accuracy test: assert result is within 0.5% of known manual value.

```php
public function test_meyerhof_calculation_within_tolerance(): void
{
    $result = $this->meyerhofService->calculateBearingCapacity($sampleData);
    $this->assertEqualsWithDelta(123.45, $result->allowable_capacity, 0.62); // 0.5% of 123.45
}
```

---

## Blade / Frontend Guidelines
- Use **Alpine.js** for reactive UI components (status indicators, form validation).
- Use semantic color classes for foundation status:
  - `text-green-600` / `bg-green-100` → LAYAK (Safe)
  - `text-yellow-600` / `bg-yellow-100` → MARGIN (Warning)
  - `text-red-600` / `bg-red-100` → BAHAYA (Danger)
- Soil Profile Chart: render using **Chart.js** with inverted Y-axis (depth increases downward).
- All forms must show inline validation errors using Laravel's `@error` directive.

---

## References
- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- SNI 1727:2020 — Beban Minimum untuk Perancangan Bangunan Gedung
- SNI 1726:2019 — Tata Cara Perencanaan Ketahanan Gempa
- SNI 7973:2013 — Spesifikasi Desain untuk Konstruksi Kayu
- Meyerhof, G.G. (1976). Bearing Capacity and Settlement of Pile Foundations.
