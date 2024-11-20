# Create README.md with basic information
echo "# Invoice System

A Laravel-based invoice management system.

## Requirements
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL

## Installation
1. Clone the repository
2. Run \`composer install\`
3. Copy \`.env.example\` to \`.env\` and configure your environment
4. Run \`php artisan key:generate\`
5. Run \`npm install\`
6. Run \`npm run dev\`

## Features
- Invoice generation
- Payment tracking
- User management
" > README.md

# Add and commit README
git add README.md
git commit -m "Add README"
git push
