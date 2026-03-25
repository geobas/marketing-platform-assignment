## 🚀 Assignment Setup

1. **Clone the repository**
```bash
git clone git@github.com:geobas/marketing-platform-assignment.git && \
cd marketing-platform-assignment
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Start Laravel Sail**
```bash
vendor/bin/sail up -d
```

4. **Initialize Laravel**
```bash
vendor/bin/sail composer run-script post-root-package-install && \
vendor/bin/sail artisan key:generate
```

5. **Install Node.js dependencies**
```bash
vendor/bin/sail npm install --legacy-peer-deps
```

6. **Build frontend assets**
```bash
vendor/bin/sail npm run build
```

7. **Modify the generated .env accordingly**

## 📘 How to Use the Application
1. Open your browser and go to: http://localhost
2. Create a Lead using the 'Subscribe' button. Selecting the 'I agree to receive marketing emails' checkbox will also send the Lead to Mailchimp
3. View all Leads by clicking the 'List Leads' button

## 🧰 Developer Utilities
**Run automated tests**
```bash
sail test
```
**Run static analysis**
```bash
sail bin phpstan
```
**Fix code style**
```bash
sail pint
```
