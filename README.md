## 🚀 Deployment on XAMPP Server (Port 8000)

This project is configured to run on an Apache server (XAMPP) using **port 8000**, without needing `php artisan serve`.

### Server Info
| Item | Value |
|------|-------|
| Server IP | `192.188.158.18` |
| Port | `8000` |
| Access URL | `http://192.188.158.18:8000` |
| Stack | XAMPP (Apache + MySQL + PHP) |

---

### 1. Clone / Copy Project to htdocs
Place the project inside the XAMPP `htdocs` folder:
```
C:\xampp\htdocs\your-laravel-app
```

### 2. Install Dependencies & Setup Laravel
Run these commands in the project root (only needed once on server):

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure your database and app URL:
```env
APP_URL=http://192.188.158.18:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations:
```bash
php artisan migrate --seed
```

### 3. Configure Apache to Listen on Port 8000
Edit `C:\xampp\apache\conf\httpd.conf` and add below the existing `Listen 80` line:
```apache
Listen 8000
```

In the same file, make sure these lines are **uncommented** (remove `#`):
```apache
LoadModule rewrite_module modules/mod_rewrite.so
Include conf/extra/httpd-vhosts.conf
```

### 4. Add Virtual Host
Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf` and append:

```apache
<VirtualHost *:8000>
    DocumentRoot "C:/xampp/htdocs/your-laravel-app/public"
    ServerName 192.188.158.18

    <Directory "C:/xampp/htdocs/your-laravel-app/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

> ⚠️ **Important:** `DocumentRoot` must point to the **`/public`** folder, not the project root.

### 5. Allow Port 8000 in Windows Firewall
On the server machine:
1. Open **Windows Defender Firewall** → **Advanced settings** → **Inbound Rules** → **New Rule**
2. Choose **Port** → enter `8000` → **Allow the connection**
3. Name it e.g. `Laravel App 8000`

### 6. Restart Apache
Open XAMPP Control Panel → **Stop** then **Start** Apache.

### 7. Access the App
From any machine on the network:
```
http://192.188.158.18:8000
```

---

### 🛠 Troubleshooting

| Issue | Fix |
|-------|-----|
| **403 Forbidden** | Ensure `AllowOverride All` and `Require all granted` are set in the `<Directory>` block. Use forward slashes `/` in paths. |
| **404 on all routes except home** | Enable `mod_rewrite` in `httpd.conf` (remove the `#`). |
| **Port already in use** | Change `Listen 8000` to another port (e.g., `8080`) and update vhost + `APP_URL`. |
| **Cannot access from other PC** | Check Windows Firewall rule for port 8000 is enabled. Verify both machines are on the same network. |
| **Assets/CSS not loading** | Make sure `APP_URL` in `.env` is `http://192.188.158.18:8000` and run `php artisan config:clear`. |

---

### ⚙️ Post-Deployment Optimization (Optional, for production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

To revert during development:
```bash
php artisan optimize:clear
```
