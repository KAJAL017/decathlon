\# GEMINI.md — Advanced Laravel E-commerce Frontend Development System



\# PROJECT VISION



This project is not just a normal e-commerce website.



The goal is to build:



\* production-grade architecture

\* scalable frontend system

\* clean backend integration

\* premium UI/UX

\* maintainable long-term codebase



Admin panel already exists.

Frontend Blade design already exists.



Now the focus is:



\* converting frontend into fully dynamic architecture

\* building reusable systems

\* optimizing performance

\* ensuring scalability for future growth



Tech Stack:



\* Laravel

\* Blade

\* Tailwind CSS

\* Vanilla JavaScript

\* MySQL



STRICTLY NO:



\* jQuery

\* inline CSS

\* inline JS

\* random helper logic everywhere

\* business logic inside Blade

\* massive controllers

\* unoptimized queries



\---



\# MASTER DEVELOPMENT PHILOSOPHY



\---



Every feature must follow these principles:



1\. Scalable

2\. Reusable

3\. Dynamic

4\. Performant

5\. Responsive

6\. SEO-friendly

7\. Maintainable

8\. Modular

9\. Secure

10\. Production-ready



Never create temporary solutions.



Always think:



\* future updates

\* future traffic

\* future team members

\* future features



Code should feel:



\* enterprise-level

\* organized

\* readable

\* predictable



\---



\# GLOBAL DEVELOPMENT RULES



\---



\## NEVER RUSH



Do not generate quick fixes.



Before coding:



\* understand DB structure

\* understand frontend flow

\* understand admin structure

\* understand scalability impact

\* understand reusability



Then implement carefully.



\---



\## THINK BEFORE WRITING CODE



Before generating any feature:



1\. analyze architecture

2\. identify reusable parts

3\. identify future scaling problems

4\. optimize structure first

5\. then code



\---



\## NO DUPLICATE CODE



If logic repeats:



\* create helper

\* create service

\* create component

\* create utility



Never duplicate same logic repeatedly.



\---



\# ARCHITECTURE RULES



\---



Use layered architecture.



Structure:



app/

├── Actions/

├── DTOs/

├── Enums/

├── Helpers/

├── Http/

│   ├── Controllers/

│   ├── Middleware/

│   ├── Requests/

│   ├── Resources/

│   └── ViewComposers/

│

├── Models/

├── Repositories/

├── Services/

├── Traits/

└── ViewModels/



\---



\# CONTROLLER RULES



\---



Controllers must stay extremely thin.



Controllers should:



\* receive request

\* validate request

\* call service

\* return response



Controllers should NOT:



\* contain business logic

\* contain large DB queries

\* contain HTML manipulation

\* contain repeated logic



\---



\# SERVICE LAYER RULES



\---



Business logic belongs inside services.



Examples:



\* ProductService

\* CartService

\* OrderService

\* CheckoutService

\* WishlistService

\* CouponService

\* ReviewService



Service responsibilities:



\* calculations

\* business rules

\* workflow handling

\* reusable operations



\---



\# REPOSITORY RULES



\---



Repositories handle database queries.



Advantages:



\* reusable queries

\* clean controllers

\* easier optimization

\* centralized DB logic



Example:

ProductRepository:



\* featured products

\* latest products

\* category products

\* search products

\* filter products



\---



\# DTO RULES



\---



Use DTOs for:



\* checkout payload

\* order creation

\* pricing calculations

\* API formatting



Never pass huge raw arrays everywhere.



\---



\# ENUM RULES



\---



Use Enums for:



\* order status

\* payment status

\* product type

\* stock status



Avoid magic strings.



BAD:

if($order->status == 'pending')



GOOD:

OrderStatus::PENDING



\---



\# DATABASE RULES



\---



\## DATABASE DESIGN PHILOSOPHY



Database should support:



\* future scaling

\* filtering

\* analytics

\* SEO

\* inventory

\* marketing features



\---



\## TABLE NAMING



Use plural snake\_case.



GOOD:



\* products

\* product\_images

\* order\_items



BAD:



\* ProductTable

\* tbl\_products



\---



\## FOREIGN KEYS



Always use foreign keys.



\---



\## INDEXES



Add indexes on:



\* slug

\* category\_id

\* product\_id

\* status

\* created\_at



\---



\## SLUG SYSTEM



Every SEO entity must use slug.



Examples:



\* products

\* categories

\* brands



\---



\## SOFT DELETE



Use soft deletes where needed:



\* products

\* categories

\* orders



\---



\# MODEL RULES



\---



Always define:



\* relationships

\* scopes

\* accessors

\* mutators

\* casts



\---



\## MODEL SCOPES



Create reusable scopes.



Examples:



\* active()

\* featured()

\* inStock()



\---



\## EAGER LOADING



Always prevent N+1 queries.



BAD:

foreach product category



GOOD:

with('category')



\---



\# BLADE ARCHITECTURE RULES



\---



resources/views/frontend/

├── layouts/

├── pages/

├── partials/

├── sections/

├── components/

└── emails/



\---



\# LAYOUT RULES



Main layout should contain:



\* SEO meta

\* global CSS

\* global JS

\* navbar

\* footer

\* flash messages



\---



\# PARTIAL RULES



Use partials for:



\* navbar

\* footer

\* breadcrumbs

\* sidebar

\* filters

\* mobile menu

\* pagination



\---



\# COMPONENT RULES



Use Blade components for:



\* buttons

\* cards

\* product cards

\* badges

\* rating stars

\* alerts

\* modals



\---



\# VIEW COMPOSER RULES



\---



Use View Composers for:



\* global settings

\* categories

\* menus

\* cart count

\* wishlist count



Avoid querying same data repeatedly.



\---



\# TAILWIND CSS RULES



\---



\## DESIGN SYSTEM RULES



Use consistent:



\* spacing

\* radius

\* typography

\* shadows

\* colors



\---



\## RESPONSIVE RULES



Must support:



\* mobile

\* tablet

\* desktop

\* large screens



Always test:



\* overflow

\* spacing

\* grids

\* sticky elements



\---



\## CLASS RULES



Avoid:



\* unreadable huge class chains



Use:



\* reusable utilities

\* extracted component classes when necessary



\---



\# UI/UX RULES



\---



Frontend should feel:



\* premium

\* smooth

\* minimal

\* modern

\* fast



\---



\## ANIMATION RULES



Animations should:



\* stay subtle

\* improve UX

\* never feel distracting



Use:



\* opacity

\* transform

\* transition



Avoid:



\* excessive animations

\* laggy effects



\---



\# MOBILE-FIRST RULES



\---



Mobile UX is priority.



Ensure:



\* thumb-friendly spacing

\* sticky add-to-cart

\* readable typography

\* optimized menus



\---



\# JAVASCRIPT RULES



\---



STRICTLY:



\* Vanilla JS only



NO:



\* jQuery

\* inline scripts

\* random global functions



\---



resources/js/

├── modules/

├── components/

├── utilities/

└── app.js



\---



\# JS ARCHITECTURE RULES



Use modular structure.



Examples:



\* cart.js

\* wishlist.js

\* filters.js

\* search.js

\* modal.js



\---



\# EVENT RULES



Use:



\* event delegation

\* reusable handlers



Avoid:



\* duplicated listeners

\* memory leaks



\---



\# AJAX RULES



Use Fetch API.



Every request must handle:



\* loading state

\* success state

\* validation errors

\* failure state



\---



\# LOADING UX RULES



Always show:



\* skeleton loaders

\* loading spinners

\* disabled states



\---



\# SEO RULES



\---



Every page must support:



\* meta title

\* meta description

\* OG tags

\* Twitter tags

\* canonical URL



\---



\# PRODUCT SEO RULES



Product page should support:



\* schema markup

\* SEO slug

\* optimized title

\* optimized images



\---



\# URL STRUCTURE RULES



GOOD:



\* /product/iphone-15-pro

\* /category/mens-shoes



BAD:



\* /product/15

\* /category?id=5



\---



\# PERFORMANCE RULES



\---



Performance is critical.



\---



\## QUERY OPTIMIZATION



Avoid:



\* repeated queries

\* nested loops querying DB



\---



\## CACHE RULES



Cache:



\* settings

\* homepage sections

\* menus

\* categories



\---



\## IMAGE OPTIMIZATION



Use:



\* lazy loading

\* responsive images

\* optimized dimensions



\---



\## ASSET OPTIMIZATION



Use:



\* Vite optimization

\* minification

\* deferred scripts



\---



\# SECURITY RULES



\---



Always:



\* validate input

\* sanitize output

\* use CSRF protection

\* escape Blade output



Never trust frontend data.



\---



\# AUTHENTICATION RULES



\---



Customer system should support:



\* login

\* register

\* forgot password

\* email verification



\---



\# ROLE RULES



\---



Roles:



\* admin

\* customer



\---



\# E-COMMERCE CORE SYSTEM RULES



\---



\# PRODUCT SYSTEM



Product should support:



\* simple products

\* sale price

\* SKU

\* stock tracking

\* image gallery

\* categories

\* tags

\* related products

\* featured products



\---



\# CATEGORY SYSTEM



Category should support:



\* hierarchy

\* parent-child relation

\* ordering

\* SEO fields



\---



\# CART SYSTEM



Cart should support:



\* guest cart

\* user cart

\* quantity update

\* remove item

\* mini cart

\* coupon support



Prefer AJAX updates.



\---



\# CHECKOUT SYSTEM



Checkout should support:



\* shipping address

\* billing address

\* order summary

\* payment methods

\* validation



\---



\# ORDER SYSTEM



Order states:



\* pending

\* processing

\* shipped

\* delivered

\* cancelled



\---



\# PAYMENT SYSTEM



Payment gateways must stay modular.



Each payment:



\* separate service

\* separate integration layer



\---



\# REVIEW SYSTEM



Reviews should support:



\* ratings

\* customer review

\* approval system



\---



\# SEARCH SYSTEM



Search should support:



\* product title

\* SKU

\* category

\* tags



\---



\# FILTER SYSTEM



Filters:



\* category

\* price

\* brand

\* rating

\* sorting



Use query strings.



\---



\# WISHLIST SYSTEM



Wishlist should support:



\* guests

\* authenticated users



\---



\# COUPON SYSTEM



Coupons should support:



\* percentage

\* fixed amount

\* expiration

\* usage limit



\---



\# SETTINGS SYSTEM RULES



\---



Create dynamic settings system.



Settings:



\* logo

\* favicon

\* social links

\* contact info

\* SEO defaults

\* maintenance mode



\---



\# MENU SYSTEM RULES



\---



Menus must be database-driven.



Support:



\* multi-level menus

\* mega menus



\---



\# ERROR HANDLING RULES



\---



Always provide:



\* empty states

\* fallback images

\* graceful failures



Examples:



\* no products

\* empty cart

\* broken image



\---



\# VALIDATION RULES



\---



Always use:



\* Form Requests



Validation messages must stay user-friendly.



\---



\# API RULES



\---



If APIs are added:



\* use versioning

\* consistent JSON structure

\* API Resources



\---



\# NOTIFICATION RULES



\---



Use:



\* toast notifications

\* success alerts

\* validation feedback



\---



\# TESTING RULES



\---



Important features should have:



\* feature tests

\* unit tests



\---



Test:



\* cart

\* checkout

\* auth

\* order creation



\---



\# DEPLOYMENT RULES



\---



Before production:



Run:



\* config cache

\* route cache

\* optimize

\* queue workers



\---



\# LOGGING RULES



Use proper logging for:



\* payment errors

\* checkout issues

\* API failures



\---



\# GIT RULES



\---



Commit messages must stay meaningful.



GOOD:



\* add dynamic wishlist system

\* optimize homepage queries



BAD:



\* fix

\* update

\* done



\---



\# CLEAN CODE RULES



\---



Use:



\* meaningful naming

\* consistent formatting

\* readable methods



Avoid:



\* giant methods

\* unclear variables



\---



\# NAMING RULES



Classes:

PascalCase



Variables:

camelCase



Blade files:

kebab-case



\---



\# COMMENT RULES



Write comments only where logic is complex.



Avoid unnecessary comments.



\---



\# FUTURE SCALABILITY RULES



\---



Code should allow future support for:



\* multi-vendor

\* multi-language

\* multi-currency

\* mobile app API

\* advanced analytics



Architecture must not block future expansion.



\---



\# FINAL FRONTEND EXPERIENCE RULES



\---



Website should feel:



\* premium

\* cinematic

\* modern

\* smooth

\* fast

\* trustworthy



Users should instantly feel:

“This is a professional brand.”



\---



\# AI ASSISTANT EXECUTION RULES



\---



Whenever generating code:



1\. First understand requirement deeply

2\. Analyze existing architecture

3\. Explain implementation approach

4\. Generate scalable solution

5\. Keep code modular

6\. Follow Laravel best practices

7\. Ensure responsiveness

8\. Avoid hardcoding

9\. Optimize performance

10\. Ensure maintainability



Before finalizing:



\* test logic mentally

\* check responsiveness

\* check scalability

\* check security

\* check readability



Never generate lazy solutions.



END OF FILE



