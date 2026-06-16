---
name: laravel-storage-media-debug
description: Debug and fix Laravel local-storage media uploads that save but do not render in frontend or admin lists.
source: auto-skill
extracted_at: '2026-06-13T11:36:03.213Z'
---

# Laravel Storage Media Debug

Use this approach when an image upload succeeds in a Laravel app, but the image or banner does not appear on the frontend or in an admin listing page.

## Procedure

1. Trace the whole data path before changing code:
   - Upload controller/API endpoint.
   - Media/storage service.
   - Model accessors and mutators.
   - Database row values, especially raw image path fields.
   - Frontend controller/service query.
   - Blade/API rendering.
   - Public storage link and actual filesystem file.

2. Check for invalid upload validation usage:
   - `Illuminate\Http\UploadedFile` does not have `validate()`.
   - Validate uploaded files with `Validator::make(['file' => $file], [...])->validate()` or request validation.
   - Catch `ValidationException` in upload endpoints so file validation problems return `422`, not a generic `500 Upload failed`.

3. Verify database rows using raw model values:
   - Do not trust accessors alone.
   - Inspect `getRawOriginal('image_url')` and compare it with `$model->image_url`.
   - Expected DB value for public disk media should usually be a relative path such as `uploads/banners/file.png`, not `/storage/...` and not a full local URL/path.

4. Verify file storage:
   - Check `Storage::disk('public')->exists($rawPath)`.
   - Check the physical file under `storage/app/public/...`.
   - Check the web-accessible path under `public/storage/...`.
   - On Windows/XAMPP, `public/storage` may exist as a directory or junction rather than a standard symlink; inspect before deleting or recreating it.

5. Normalize image paths at the model/service boundary:
   - Store relative public-disk paths in the database.
   - Accept and normalize values like:
     - `uploads/banners/file.png`
     - `/storage/uploads/banners/file.png`
     - `/some/subdir/public/storage/uploads/banners/file.png`
     - `http://host/storage/uploads/banners/file.png`
   - Generate frontend URLs with `Storage::disk('public')->url($relativePath)`.

6. Watch for APP_URL mismatch during local development:
   - If the app is browsed at `http://127.0.0.1:8000` but `APP_URL` is `http://localhost/some/subdir/public`, generated absolute storage URLs can point to the wrong host/path.
   - Prefer a root-relative public storage URL like `/storage` for local disk media, optionally configurable with an env var such as `PUBLIC_STORAGE_URL` for production/CDN overrides.

7. Verify frontend data rules:
   - Confirm only active banners/media are queried (e.g. `scopeActive()`).
   - Confirm sort order is applied (`orderBy('sort_order')`).
   - Confirm homepage/section configuration actually includes an active banner section; uploaded banners may only render if the page builder has the section enabled.
   - If section settings contain selected IDs, confirm the new uploaded banner is included or that empty selection means “show all”.

8. Verify admin list serialization separately from frontend rendering:
   - Inspect the admin Blade/JS that renders table thumbnails, not only the homepage Blade.
   - If JSON is generated from Eloquent models with accessors/appends, explicitly serialize an array for admin list endpoints so each URL field uses the same clean accessor output.
   - Avoid mixing `thumbnail_url` and `image_url` when one can be raw and the other accessor-generated; either make `thumbnail_url` use `getRawOriginal()` internally or return the same clean URL as `image_url` for list thumbnails.
   - Add a small client-side guard only as defense-in-depth, such as collapsing repeated `/storage/` segments before assigning `<img src>`, but do not rely on this as the root fix.
   - Add cache-busting query params to AJAX list fetches while debugging stale admin pages, e.g. `&_=${Date.now()}`.

9. Add a safe fallback only after the root cause is fixed:
   - For an empty hero/banner section, render a neutral fallback block.
   - Keep Blade conditional structure valid: one enclosing section, `@if` content, `@else` fallback, `@endif`, then closing section.
   - Do not create duplicate banners or change schema/fields to force display.

10. Clear relevant Laravel caches after config/view changes:
   - Run `php artisan optimize:clear`.
   - If the browser still requests an old URL after server output is clean, hard-refresh or reopen the tab; stale compiled Blade/browser JS can keep old thumbnail URLs alive.

11. Targeted verification checklist:
   - `php -l` for changed PHP files.
   - Query active sorted banners and print raw path, generated URL, file existence, active flag, and sort order.
   - Verify homepage service returns the expected banner count.
   - Verify Blade rendering does not fail.
   - Verify upload route exists with `php artisan route:list --path=api/upload` or equivalent.
   - Invoke the admin list controller/endpoint and assert the JSON contains `storage/uploads/...` but not `storage/storage`.
   - Use `curl -I` on the final image URL and confirm `200 OK`; authenticated admin endpoints may redirect to login under plain curl, so controller invocation via Tinker can be more reliable for JSON checks.

## Example Tinker Checks

```bash
php artisan tinker --execute='echo json_encode(App\\Models\\Banner::active()->orderBy("sort_order")->get(["id","image_url","image_id","banner_link","sort_order","is_active"])->map(fn($b)=>["id"=>$b->id,"raw"=>$b->getRawOriginal("image_url"),"url"=>$b->image_url,"exists"=>Illuminate\\Support\\Facades\\Storage::disk("public")->exists($b->getRawOriginal("image_url")),"sort_order"=>$b->sort_order,"active"=>$b->is_active])->all(), JSON_PRETTY_PRINT);'

php artisan tinker --execute='echo json_encode(["public_url"=>config("filesystems.disks.public.url"),"home_hero_count"=>(new App\\Services\\HomeService())->getHomepageData()->firstWhere("type", "hero_banners")?->data?->count()], JSON_PRETTY_PRINT);'
```
