IMAGE STORAGE ISSUE

Analyze the entire image handling system.

Problem:
I have removed all online/external image URLs from the database. All product, category, banner, and other images should be loaded from local storage only.

Requirements:
- Find why the application is still trying to use online image URLs.
- Check models, controllers, seeders, factories, helpers, accessors, API responses, and frontend components.
- Remove any hardcoded external image URLs.
- Ensure images are loaded only from local storage.
- Use Laravel's Storage system and local public storage paths.
- Generate image URLs using the proper Laravel helper functions (asset(), Storage::url(), etc., depending on the project setup).
- Verify that the `storage:link` configuration is correct if required.
- Check for fallback image logic that may be forcing external URLs.
- Update all image-related code to use local images consistently.
- Keep the existing database schema intact.
- Do not reintroduce external image URLs.
- Ensure product images, category images, banners, avatars, and gallery images all work correctly from local storage.
- Fix broken image paths and maintain compatibility across the entire application.

The final result should use only local images and no external image URLs anywhere in the project.