classDiagram

    class User {
        +String userId
        +String name
        +String role
        +String email
    }

    class Project {
        +String projectId
        +String name
        +String status
        +String ownerId
        +shareAccess(userId) void
    }

    class Report {
        +String reportId
        +Date createdAt
        +String signature
        +generatePDF() void
        +exportDXF() void
    }

    class Location {
        +Float latitude
        +Float longitude
        +String zoneCode
        +Float peakGroundAcc
        +fetchFromGPS() void
    }

    class FoundationPoint {
        +String pointId
        +String safetyStatus
        +Float minDepth
        +Boolean peatlandAlert
        +validate() void
    }

    class CalculationEngine {
        +calcMeyerhof() Float
        +validateLRFD() Boolean
        +estimateSettlement() Float
        +calcPileGroup() Float
    }

    class SNIStandard {
        +String code
        +Int version
        +String description
        +getParameter() Float
    }

    class LoadData {
        +Float deadLoad
        +Float liveLoad
        +Float windLoad
        +Float earthquakeLoad
    }

    class SoilData {
        +Float depth
        +Float coneResistance
        +Float localFriction
        +Float groundwaterLevel
        +Boolean isPeatland
        +detectPeatland() Boolean
    }

    %% Relationships
    User "many" o-- "many" Project : akses
    Project "1" --> "many" Report : menghasilkan
    Project "1" *-- "1" Location : berlokasi
    Project "1" *-- "many" FoundationPoint : memiliki
    FoundationPoint "1" o-- "1" LoadData : beban
    FoundationPoint "1" o-- "many" SoilData : lapisan tanah

    CalculationEngine ..> FoundationPoint : hasil
    CalculationEngine ..> SNIStandard : referensi
    CalculationEngine ..> LoadData : validasi
    CalculationEngine ..> SoilData : analisis
