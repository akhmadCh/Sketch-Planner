erDiagram
    users {
        uuid id PK
        varchar name
        varchar email
        varchar role
        timestamp created_at
        timestamp updated_at
    }

    projects {
        uuid id PK
        uuid user_id FK
        varchar name
        text description
        varchar location
        varchar status
        timestamp created_at
    }

    soil_profiles {
        uuid id PK
        uuid project_id FK
        varchar profile_name
        float groundwater_depth
        varchar soil_type
        timestamp created_at
    }

    pile_configurations {
        uuid id PK
        uuid project_id FK
        varchar pile_type
        varchar cross_section
        float diameter
        float width
        float length
        float area_tip
        float perimeter
        varchar material
    }

    soil_layers {
        uuid id PK
        uuid soil_profile_id FK
        int layer_order
        float depth_top
        float depth_bottom
        varchar soil_classification
        float cohesion_c
        float friction_angle_phi
        float unit_weight
        float spt_n_value
    }

    meyerhof_calculations {
        uuid id PK
        uuid project_id FK
        uuid pile_config_id FK
        uuid soil_profile_id FK
        uuid created_by FK
        varchar calc_name
        float embedment_ratio
        float nq_factor
        float nc_factor
        float tip_resistance_qp
        float skin_friction_qs
        float total_capacity_qu
        float safety_factor
        float allowable_load_qa
        varchar pile_condition
        text notes
        timestamp calculated_at
    }

    calc_results_detail {
        uuid id PK
        uuid calculation_id FK
        int layer_sequence
        float layer_fs_contribution
        float alpha_factor
        float beta_factor
        float unit_skin_friction
    }

    reports {
        uuid id PK
        uuid calculation_id FK
        uuid generated_by FK
        varchar format
        text file_path
        timestamp generated_at
    }

    %% Relasi antar tabel
    users ||--o{ projects : "memiliki"
    users ||--o{ meyerhof_calculations : "membuat"
    users ||--o{ reports : "mengunduh"

    projects ||--o{ soil_profiles : "merencana"
    projects ||--o{ pile_configurations : "menentukan"
    projects ||--o{ meyerhof_calculations : "memuat"

    soil_profiles ||--o{ soil_layers : "terdiri dari"
    soil_profiles ||--o{ meyerhof_calculations : "dianalisis dalam"

    pile_configurations ||--o{ meyerhof_calculations : "digunakan pada"

    meyerhof_calculations ||--o{ calc_results_detail : "merinci"
    meyerhof_calculations ||--o{ reports : "menghasilkan"