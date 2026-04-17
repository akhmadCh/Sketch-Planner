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

    class ProfilTanah {
        +UUID id
        +String namaProfil
        +Float kedalamanAirTanah
        +String jenisTanah
        +List~LapisanTanah~ lapisan
        +tambahLapisan() void
        +validasiProfil() bool
    }

    class KonfigurasiTiang {
        +UUID id
        +String tipeTiang
        +String bentukPenampang
        +Float diameter
        +Float panjang
        +Float luasTipTiang
        +Float keliling
        +String material
        +hitungRasioEmbedment() Float
    }

    class LapisanTanah {
        +UUID id
        +Int urutanLapisan
        +Float kedalamanAtas
        +Float kedalamanBawah
        +Float kohesi
        +Float sudutGeser
        +Float beratUnit
        +Float nilaiSPT
        +hitungTebal() Float
    }

    class KalkulasiMeyerhof {
        +UUID id
        +String namaKalkulasi
        +Float rasioEmbedment
        +Float faktorNq
        +Float faktorNc
        +Float hambatanUjungQp
        +Float gesekanSelimutQs
        +Float kapasitasTotalQu
        +Float faktorKeamanan
        +Float bebanIjinQa
        +String kondisiTiang
        +Date waktuKalkulasi
        +hitung() void
        +hitungHambatanUjung() Float
        +hitungGesekanSelimut() Float
        +hitungKapasitasTotal() Float
        +hitungBebanIjin() Float
        +validasiInput() bool
        +simpanHasil() void
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

    Project "1" *-- "1..*" ProfilTanah : mencakup
    Project "1" -- "0..*" KalkulasiMeyerhof : memuat
    ProfilTanah "1" *-- "1..*" LapisanTanah : terdiri dari
    ProfilTanah "1" -- "0..*" KalkulasiMeyerhof : dianalisis dalam
    KonfigurasiTiang "1" -- "0..*" KalkulasiMeyerhof : digunakan pada
    KalkulasiMeyerhof "1" *-- "1..*" DetailHasilKalkulasi : merinci
    KalkulasiMeyerhof "1" --> "0..*" Report : menghasilkan
