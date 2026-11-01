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

---

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

---

### 3. Create the Storage Symlink (Required for Uploaded Files)

> ⚠️ **Do not skip this step.** Without it, uploaded files — profile pictures, signatures, and any other user uploads — will **not display** when the app runs under XAMPP, even though they uploaded successfully.

Run from the project root:

```bash
php artisan storage:link
```

Expected output:
```
The [public\storage] link has been connected to [storage\app\public].
```

Then verify the link exists and is a **symlink**, not a regular folder:

```bash
dir C:\xampp\htdocs\your-laravel-app\public
```

✅ You must see one of these:
```
<SYMLINKD>  storage [..\storage\app\public]
<JUNCTION>  storage [C:\xampp\htdocs\your-laravel-app\storage\app\public]
```

❌ If you see `<DIR>  storage`, the link is broken — see **Troubleshooting → Images / signatures not displaying** below.

Finally, confirm your uploads are physically present in the real storage directory:

```bash
dir C:\xampp\htdocs\your-laravel-app\storage\app\public
```

You should see subfolders like `profile-pictures\`, `signatures\`, etc.

---

### 4. Configure Apache to Listen on Port 8000
Edit `C:\xampp\apache\conf\httpd.conf` and add below the existing `Listen 80` line:
```apache
Listen 8000
```

In the same file, make sure these lines are **uncommented** (remove `#`):
```apache
LoadModule rewrite_module modules/mod_rewrite.so
Include conf/extra/httpd-vhosts.conf
```

---

### 5. Add Virtual Host
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

> ℹ️ **Note on `FollowSymLinks`:** This option is required for Apache to serve the `public/storage` symlink created in step 3. Do not remove it.

---

### 6. Allow Port 8000 in Windows Firewall
On the server machine:
1. Open **Windows Defender Firewall** → **Advanced settings** → **Inbound Rules** → **New Rule**
2. Choose **Port** → enter `8000` → **Allow the connection**
3. Name it e.g. `Laravel App 8000`

---

### 7. Restart Apache
Open XAMPP Control Panel → **Stop** then **Start** Apache.

---

### 8. Access the App
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
| **Profile pictures / signatures not displaying** | See dedicated section below. |

---

### 🖼 Profile Pictures / Signatures Not Displaying (Storage Symlink)

**Symptom:** Images and signatures upload successfully and display fine when using `php artisan serve`, but show as broken or missing once the project is accessed via XAMPP at `http://192.188.158.18:8000`.

**Cause:** Laravel serves user uploads through a **symlink** at `public/storage` pointing to `storage/app/public`. Apache serves files strictly from the filesystem, so:

- If `public/storage` is a **plain folder** (not a symlink), Apache cannot reach the real files → 404.
- If `APP_URL` is stale (e.g. still `http://127.0.0.1:8000`), `asset()` generates the wrong URL.
- If files were uploaded while running `php artisan serve` in a **different copy** of the project, they are not in the htdocs copy.

#### Fix — step by step

**1. Check whether `public\storage` is a real symlink**

```bash
dir C:\xampp\htdocs\your-laravel-app\public
```

| Output | Meaning |
|---|---|
| `<SYMLINKD> storage [..\storage\app\public]` | ✅ Correct — go to step 3 |
| `<JUNCTION> storage [C:\...\storage\app\public]` | ✅ Correct — go to step 3 |
| `<DIR> storage` | ❌ Plain folder — do step 2 |

**2. Delete the fake folder and re-create the link**

This does **not** delete your real uploads in `storage\app\public`:

```bash
rmdir /S /Q C:\xampp\htdocs\your-laravel-app\public\storage
cd C:\xampp\htdocs\your-laravel-app
php artisan storage:link
```

If Windows blocks symlink creation, open an **Administrator** command prompt and run:

```bash
cd C:\xampp\htdocs\your-laravel-app\public
mklink /D storage ..\storage\app\public
```

Re-run `dir` — it must now show `<SYMLINKD>` or `<JUNCTION>`, **not** `<DIR>`.

**3. Verify your files are in the real upload directory**

```bash
dir C:\xampp\htdocs\your-laravel-app\storage\app\public
```

You should see folders such as `profile-pictures\` and `signatures\`. If empty, copy the files over from the other copy of the project.

**4. Fix `APP_URL` and clear caches**

```env
APP_URL=http://192.188.158.18:8000
```

```bash
php artisan config:clear
php artisan cache:clear
```

**5. Test the file URL directly**

Open in browser:

```
http://192.188.158.18:8000/storage/profile-pictures/<filename>.jpg
```

| Result | Meaning |
|---|---|
| Image displays | ✅ Fixed — page will now show it |
| 404 | File is not inside `storage\app\public\profile-pictures\` (step 3) |
| Redirects to XAMPP dashboard / 500 | `APP_URL` still wrong (step 4) |
| 403 | Apache can't read the folder — check `FollowSymLinks` in the vhost (step 5) |

**6. Why it worked with `php artisan serve` but not with XAMPP**

`php artisan serve` uses PHP's built-in development server, which resolves uploaded files inside `storage/app/public` automatically. Apache serves files strictly from the filesystem under `public/`, so it requires a real `public/storage` symlink and a correct `APP_URL`.

#### `ERROR: The [public\storage] link already exists.`

This means something named `storage` already exists in `public/`. It is usually a plain folder created by copying, not a working symlink. Delete it and re-create the link:

```bash
rmdir /S /Q C:\xampp\htdocs\your-laravel-app\public\storage
cd C:\xampp\htdocs\your-laravel-app
php artisan storage:link
```

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

> ⚠️ **Note:** After running `config:cache`, any change to `.env` (including `APP_URL`) requires re-running `php artisan config:cache` — otherwise the old value is used. If you're still troubleshooting image URLs, run `php artisan config:clear` first.
