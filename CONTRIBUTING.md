# Contributing

Thank you for considering contributing to Penilaian Pengabdian!

## Development Setup

### Prerequisites
- PHP 8.2+
- MySQL/MariaDB
- Composer
- Node.js & NPM

### Setup

```bash
# Clone repository
git clone <repo-url>
cd penilaian-pengabdian

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env, then:
php artisan migrate
php artisan storage:link

# Start development
composer dev
```

## Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) for code formatting.

### Check Style

```bash
vendor/bin/pint --test
```

### Auto-fix Style

```bash
vendor/bin/pint
```

**Always run Pint before committing.**

## Testing

### Run Tests

```bash
php artisan test
```

### Run Specific Test

```bash
php artisan test --filter=UserTest
```

### Writing Tests

- Place unit tests in `tests/Unit/`
- Place feature tests in `tests/Feature/`
- Use `RefreshDatabase` trait for tests that need database
- Follow existing test patterns in `UserTest.php`

## Pull Request Process

1. **Create a feature branch** from `main` or `master`
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**
   - Write clean, readable code
   - Follow existing code patterns
   - Add tests for new functionality

3. **Run quality checks** before committing
   ```bash
   php artisan test
   vendor/bin/pint --test
   ```

4. **Commit your changes**
   - Use clear, descriptive commit messages
   - Reference issues if applicable

5. **Push and create PR**
   ```bash
   git push origin feature/your-feature-name
   ```

6. **Fill PR template**
   - Describe what you changed
   - Explain why you changed it
   - Reference any related issues

## Branch Naming

- `feature/description` - New features
- `fix/description` - Bug fixes
- `docs/description` - Documentation changes
- `refactor/description` - Code refactoring

## Code Guidelines

### PHP
- Follow PSR-12 coding standards
- Use typed properties and return types
- Write self-documenting code with clear variable names
- Add PHPDoc blocks for complex methods

### Laravel
- Use Eloquent relationships properly
- Keep controllers thin, use Form Requests
- Use Services/Actions for complex business logic
- Follow Laravel conventions

### Database
- Create migrations for schema changes
- Use foreign keys for relationships
- Add indexes for frequently queried columns

## Reporting Issues

- Use GitHub Issues
- Include steps to reproduce
- Include expected vs actual behavior
- Include PHP/Laravel version if relevant

## Questions?

If you have questions, feel free to open an issue with the "question" label.
