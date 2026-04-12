# US06_meyerhof_user_story_output.md

Prompt: "Sebagai Pengguna, saya ingin menghitung daya dukung tiang menggunakan metode Meyerhof otomatis"

Context File: PRD_joined.txt SKILL.md class_diagram.md database_schema.md

Skills: "sketchplanner-laravel"

Task: Generate code for the following user story: "As a user, I want to automatically calculate pile bearing capacity using the Meyerhof method based on sondir (CPT) data, so the system can determine whether the foundation point is SAFE, WARNING, or DANGEROUS without manual calculation."

Input: @parameter array $soilData

- project_id : int // ID proyek aktif
- point_label : string // Label titik sondir, e.g. "S-1"
- qc_values : float[] // Cone Resistance (kgf/cm²) per kedalaman
- fs_values : float[] // Local Friction (kgf/cm²) per kedalaman
- depth_interval : float // Interval kedalaman (m), e.g. 0.2
- pile_diameter : float // Diameter tiang (m)
- pile_depth : float // Kedalaman tiang rencana (m)
- safety_factor : float // Faktor keamanan (default: 2.5)

Output: @return FoundationResultResource
//@return object {
// point_label : string,
// qu_kn : float, // Ultimate bearing capacity (kN)
// qa_kn : float, // Allowable bearing capacity (kN)
// safety_factor : float,
// status : string, // "LAYAK" | "MARGIN" | "BAHAYA"
// peat_warning : boolean,
// calculation_detail: object
// }

Rules:
// Meyerhof formula: Qu = (qc*avg * Ap) + (fs*avg * As)
// Ap = (π/4) _ d² → Tip area (m²)
// As = π _ d \* L → Skin friction area (m²)
// qc_avg = average of last 8 qc readings at pile tip (kgf/cm²)
// fs_avg = average of all fs readings along pile shaft (kgf/cm²)
// Convert: 1 kgf/cm² = 98.0665 kPa
// Status: LAYAK if Qa >= required_load
// MARGIN if Qa is 0–10% above minimum
// BAHAYA if Qa < required_load OR peat detected (qc<5 & Rf>5%)
// Tolerance vs manual: error must not exceed 0.5%
// Validation: qc range 0–500, fs range 0–10, depth 0.2–60 m

What Changed: "New feature — MeyerhofService created. New API endpoint POST /api/projects/{id}/calculate/meyerhof added. FoundationResultResource added. FoundationStatus enum added. Calculation audit log observer added."

Commit Message: "feat(calculation): add Meyerhof bearing capacity engine (US06)"
