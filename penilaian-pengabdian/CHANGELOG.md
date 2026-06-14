# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- CI/CD pipeline with GitHub Actions (`.github/workflows/ci.yml`)
- Unit tests for User model (23 test cases)
- CONTRIBUTING.md guide
- CHANGELOG.md

### Fixed
- Nilai akhir display in `/admin/transaksi` now matches laporan calculation
  - Changed from `LaporanScoreCalculator::calculate()` to `calculateNilaiAkhirForKaryawan()`
  - Proper per-pangkalan averaging for multi-pangkalan karyawan
- Laporan default tahun ajaran now uses active year instead of stale setting value
- Migration `2026_06_07_100001` now compatible with SQLite (skips MySQL-specific syntax)

### Changed
- Code style: 85 style issues fixed via Laravel Pint

## [1.0.0] - 2026-04-09

### Added
- Initial release
- Dashboard with statistics
- Karyawan management with multi-pangkalan support
- Penilaian karyawan with lock/unlock system
- Laporan (keseluruhan & perorangan) with PDF/Excel/CSV export
- Reward & punishment configuration
- Database backup & restore
- Mutasi (transfer) between tahun ajaran and pangkalan
- Setting lembaga configuration
- Role-based access control (Admin, Kepala, Tata Usaha, User)
