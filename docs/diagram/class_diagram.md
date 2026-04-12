classDiagram
    class Pengguna {
        +UUID id
        +String nama
        +String email
        +String peran
        +login() bool
        +buatProyek() Proyek
        +lihatRiwayatKalkulasi() List
    }

    class Proyek {
        +UUID id
        +String nama
        +String lokasi
        +String status
        +UUID userId
        +tambahProfilTanah() ProfilTanah
        +tambahKonfigurasiTiang() KonfigurasiTiang
        +jalankanKalkulasi() KalkulasiMeyerhof
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

    class DetailHasilKalkulasi {
        +UUID id
        +Int urutanLapisan
        +Float kontribusiLapisan
        +Float faktorAlpha
        +Float faktorBeta
        +Float gesekanUnitSelimut
    }

    class Laporan {
        +UUID id
        +String format
        +String pathFile
        +Date waktuGenerate
        +generate() void
        +unduh() File
    }

    %% Relasi Antar Kelas beserta Kardinalitas
    Pengguna "1" --> "0..*" Proyek : memiliki
    Proyek "1" *-- "1..*" ProfilTanah : mencakup
    Proyek "1" *-- "1..*" KonfigurasiTiang : mendefinisikan
    Proyek "1" -- "0..*" KalkulasiMeyerhof : memuat
    ProfilTanah "1" *-- "1..*" LapisanTanah : terdiri dari
    ProfilTanah "1" -- "0..*" KalkulasiMeyerhof : dianalisis dalam
    KonfigurasiTiang "1" -- "0..*" KalkulasiMeyerhof : digunakan pada
    KalkulasiMeyerhof "1" *-- "1..*" DetailHasilKalkulasi : merinci
    KalkulasiMeyerhof "1" --> "0..*" Laporan : menghasilkan