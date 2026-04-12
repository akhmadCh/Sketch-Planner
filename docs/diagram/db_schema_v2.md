erDiagram

    USERS {
        uuid id PK
        varchar name
        varchar email
        varchar role
        varchar password_hash
        timestamp created_at
        timestamp updated_at
    }

    PROJECTS {
        uuid id PK
        uuid owner_id FK
        varchar name
        varchar status
        timestamp created_at
        timestamp updated_at
    }

    PROJECT_MEMBERS {
        uuid id PK
        uuid project_id FK
        uuid user_id FK
        varchar access_level
        timestamp joined_at
    }

    LOCATION {
        uuid id PK
       project_id FK
        float latitude
        float longitude
        varchar zone_code
        float peak_ground_acc
        varchar province
        varchar city
        timestamp fetched_at
    }

    FOUNDATION_POINTS {
        uuid id PK
        uuid project_id FK
        varchar point_code
        float coordinate_x
        float coordinate_y
        varchar safety_status
        float min_depth
        boolean peatland_alert
        timestamp validated_at
    }

    REPORTS {
        uuid id PK
        uuid project_id FK
        uuid generated_by FK
        varchar type
        varchar file_url
        varchar signature
        timestamp created_at
    }

    SOIL_DATA {
        uuid id PK
        uuid foundation_point_id FK
        float depth
        float cone_resistance
        float local_friction
        float groundwater_level
        boolean is_peatland
        varchar input_method
        timestamp recorded_at
    }

    LOAD_DATA {
        uuid id PK
        foundations_point_id FK
        float dead_load
        float live_load
        float wind_load
        float earthquake_load
        varchar source_file
        timestamp imported_at
    }

    CALCULATION_RESULTS {
        uuid id PK
        uuid foundation_point_id FK
        uuid sni_standard_id FK
        float meyerhof_capacity
        float allowable_capacity
        float settlement_estimate
        float pile_group_efficiency
        boolean lrfd_valid
        timestamp calculated_at
    }

    SNI_STANDARDS {
        uuid id PK
        varchar code
        int version
        varchar description
        json parameters
        date effective_date
        timestamp updated_at
    }

    soil_profiles {
         uuid id PK
         uuid project_id FK
         varchar profile_name
         float groundwater_depth
         varchar soil_type
         timestamp created_at
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

    calculation_results_detail {
        uuid id PK
        uuid calculation_id FK
        int layer_sequence
        float layer_fs_contribution
        float alpha_factor
        float beta_factor
        float unit_skin_friction
    }

    %% Relationships
    USERS ||--o{ PROJECTS : "memiliki"
    USERS ||--o{ PROJECT_MEMBERS : "tergabung"
    USERS ||--o{ REPORTS : "dibuat oleh"

    PROJECTS ||--o{ PROJECT_MEMBERS : "memiliki anggota"
    PROJECTS ||--|| LOCATIONS : "berlokasi"
    PROJECTS ||--o{ FOUNDATION_POINTS : "memiliki"
    PROJECTS ||--o{ REPORTS : "menghasilkan"

    FOUNDATION_POINTS ||--o{ SOIL_DATA : "lapisan tanah"
    FOUNDATION_POINTS ||--|| LOAD_DATA : "beban"
    FOUNDATION_POINTS ||--o{ CALCULATION_RESULTS : "kalkulasi"

    SNI_STANDARDS ||--o{ CALCULATION_RESULTS : "referensi"

    soil_profiles ||--o{ soil_layers : "terdiri dari"
    soil_profiles ||--o{ meyerhof_calculations : "dianalisis dalam"

    pile_configurations ||--o{ meyerhof_calculations : "digunakan pada"

    meyerhof_calculations ||--o{ calculation_results_detail : "merinci"
    meyerhof_calculations ||--o{ reports : "menghasilkan"
