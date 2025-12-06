    <?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\UserController;
    use Illuminate\Support\Facades\Route;

    // Admin Controllers
    use App\Http\Controllers\Admin\AdminController as AdminDashboardController;
    use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
    use App\Http\Controllers\Admin\UkmController as AdminUkmController;
    use App\Http\Controllers\Admin\UserController as AdminUserController;
    use App\Http\Controllers\Admin\StaffController as AdminStaffController;
    use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
    use App\Http\Controllers\Admin\EventController as AdminEventController;
    use App\Http\Controllers\Admin\FeedController as AdminFeedController;

    // Staff Controllers  
    use App\Http\Controllers\Staff\StaffController as StaffDashboardController;
    use App\Http\Controllers\Staff\UkmController as StaffUkmController;
    use App\Http\Controllers\Staff\EventController as StaffEventController;
    use App\Http\Controllers\Staff\FeedController as StaffFeedController;
    use App\Http\Controllers\Staff\RegistrationController as StaffRegistrationController;

    // Public Routes
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

// Halaman Home/Dashboard (bisa diakses guest & auth)
Route::get('/home', [HomeController::class, 'home'])->name('home');

    // Auth Routes

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Authenticated Routes
    Route::middleware(['auth'])->group(function () {
        // Dashboard redirect based on role
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

        // ==================== USER ROUTES ====================
        Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
            Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

            // UKM Routes
            Route::get('/ukm', [UserController::class, 'ukmList'])->name('ukm.list');
            Route::get('/ukm/{id}', [UserController::class, 'ukmDetail'])->name('ukm.detail');
            Route::post('/ukm/{id}/register', [UserController::class, 'registerUkm'])->name('ukm.register');
            // Events Routes
            Route::get('/events', [UserController::class, 'events'])->name('events.index');
            Route::get('/events/{id}', [UserController::class, 'eventDetail'])->name('events.show');

            // Feeds Routes  
            Route::get('/feeds', [UserController::class, 'feeds'])->name('feeds.index');
            Route::get('/feeds/{id}', [UserController::class, 'feedDetail'])->name('feeds.show');

            // Profile Routes
            Route::get('/profile', [UserController::class, 'profile'])->name('profile');
            Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

        });

        // ==================== ADMIN ROUTES ====================
        Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
            // Dashboard
            Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

            // UKM Routes
            
                Route::get('/ukms', [AdminUkmController::class, 'index'])->name('ukms.index');
    Route::post('/ukms', [AdminUkmController::class, 'store'])->name('ukms.store');
    Route::put('ukms/{id}', [AdminUkmController::class, 'update'])->name('ukms.update');
    Route::delete('ukms/{id}', [AdminUkmController::class, 'destroy'])->name('ukms.destroy');
    Route::patch('ukms/{id}/toggle-status', [AdminUkmController::class, 'toggleStatus'])->name('ukms.toggle-status');

            // Users Management
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::put('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
            Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
            // Tambahkan di routes/web.php
Route::post('/staff/bulk-assign', [AdminStaffController::class, 'bulkAssign'])->name('staff.bulk-assign');
Route::get('/staff/available/{ukmId}', [AdminStaffController::class, 'getAvailableStaff'])->name('staff.available');

            // Staff Management
            Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
            Route::post('/staff/assign', [AdminStaffController::class, 'assign'])->name('staff.assign');
            Route::delete('/staff/{id}', [AdminStaffController::class, 'remove'])->name('staff.remove');

// Registration routes
Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
Route::get('/registrations/{id}/details', [AdminRegistrationController::class, 'showDetails'])->name('registrations.details');
Route::put('/registrations/{id}/status', [AdminRegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
Route::post('/registrations/bulk-action', [AdminRegistrationController::class, 'bulkAction'])->name('registrations.bulkAction');
            // Events Management
            Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
            Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
            Route::put('/events/{id}', [AdminEventController::class, 'update'])->name('events.update');
            Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])->name('events.destroy');

            // Feeds Management
            Route::get('/feeds', [AdminFeedController::class, 'index'])->name('feeds.index');
            Route::post('/feeds', [AdminFeedController::class, 'store'])->name('feeds.store');
            Route::put('/feeds/{id}', [AdminFeedController::class, 'update'])->name('feeds.update');
            Route::delete('/feeds/{id}', [AdminFeedController::class, 'destroy'])->name('feeds.destroy');
        });
   

        // ==================== STAFF ROUTES ====================
        Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
            // Dashboard
            Route::get('/dashboard', [StaffDashboardController::class, 'dashboard'])->name('dashboard');

            // UKM Management
   Route::get('/ukms', [StaffUkmController::class, 'index'])->name('ukms.index');
    Route::get('/ukms/{id}', [StaffUkmController::class, 'show'])->name('ukms.show');
    Route::get('/ukms/{id}/edit', [StaffUkmController::class, 'edit'])->name('ukms.edit');
    Route::put('/ukms/{id}', [StaffUkmController::class, 'update'])->name('ukms.update');
            // Events Management
    Route::get('/events', [StaffEventController::class, 'index'])->name('events.index');
    Route::post('/events', [StaffEventController::class, 'store'])->name('events.store');
    Route::put('/events/{id}', [StaffEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [StaffEventController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{id}/toggle-visibility', [StaffEventController::class, 'toggleVisibility'])->name('events.toggle-visibility');

            // Feeds Management
    Route::get('/feeds', [StaffFeedController::class, 'index'])->name('feeds.index');
    Route::post('/feeds', [StaffFeedController::class, 'store'])->name('feeds.store');
    Route::put('/feeds/{id}', [StaffFeedController::class, 'update'])->name('feeds.update');
    Route::delete('/feeds/{id}', [StaffFeedController::class, 'destroy'])->name('feeds.destroy');

// Registration routes
Route::get('/registrations', [StaffRegistrationController::class, 'index'])->name('registrations.index');
Route::get('/registrations/{id}/details', [StaffRegistrationController::class, 'showDetails'])->name('registrations.details');
Route::put('/registrations/{id}/status', [StaffRegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
Route::post('/registrations/bulk-action', [StaffRegistrationController::class, 'bulkAction'])->name('registrations.bulkAction');
             });
    });
