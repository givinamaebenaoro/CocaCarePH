<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\VerificationTokenController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify'=>true]);

Route::get('user/login','FrontendController@login')->name('login.form');
Route::post('user/login','FrontendController@loginSubmit')->name('login.submit');
Route::get('user/logout','FrontendController@logout')->name('user.logout');

Route::get('verify/{token}', [VerificationController::class, 'verify'])->name('verify');

Route::get('user/register','FrontendController@register')->name('register.form');
Route::post('user/register','FrontendController@registerSubmit')->name('register.submit');
// Reset password
Route::get('password-reset', 'FrontendController@showResetForm')->name('password.reset');
Route::post('password-reset', 'FrontendController@updatePassword')->name('password.reset');
// Socialite
Route::get('login/{provider}/', 'Auth\LoginController@redirect')->name('login.redirect');
Route::get('login/{provider}/callback/', 'Auth\LoginController@Callback')->name('login.callback');
Route::get('/email/verify/{id}/{hash}', 'FrontendController@verify')->name('verification.verify');

//Route::get('/verify-email/{token}', 'VerificationController@verifyEmail')->name('verify.email');

Route::get('/','FrontendController@home')->name('home');

// Route::get('/email/verify/{id}/{token}', [VerificationTokenController::class, 'verify']);
// //Facebook Login URL
// Route::prefix('facebook')->name('facebook.')->group(function(){
//     Route::get('auth', [FacebookController::class, 'getAccessToken'])->name('login');
//     Route::get('callback', [FacebookController::class, 'callback'])->name('callback');
//     Route::get('auth', [FacebookController::class, 'loginWithFacebook'])->name('login');
// });

// //Google Login URL
// Route::prefix('google')->name('google.')->group(function(){
//     Route::get('auth', [GoogleController::class, 'loginWithGoogle'])->name('login');
//     Route::get('callback', [GoogleController::class, 'callbackFromGoogle'])->name('callback');
// });

// Frontend Routes
Route::get('/home', 'FrontendController@index');
Route::get('/policy', [FrontendController::class, 'policy'])->name('policy');
Route::get('/about-us','FrontendController@aboutUs')->name('about-us');
Route::get('/contact','FrontendController@contact')->name('contact');
Route::post('/contact/message','MessageController@store')->name('contact.store');
Route::get('product-detail/{slug}','FrontendController@productDetail')->name('product-detail');
Route::post('/product/search','FrontendController@productSearch')->name('product.search');
Route::get('/product-cat/{slug}','FrontendController@productCat')->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}','FrontendController@productSubCat')->name('product-sub-cat');
Route::get('/product-brand/{slug}','FrontendController@productBrand')->name('product-brand');
// Cart section
Route::get('/add-to-cart/{slug}','CartController@addToCart')->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart','CartController@singleAddToCart')->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}','CartController@cartDelete')->name('cart-delete');
Route::post('cart-update','CartController@cartUpdate')->name('cart.update');
Route::post('/cart/update/ajax', [CartController::class, 'updateCart'])->name('cart.update.ajax');


//Shipping Section
Route::get('/checkout/shipping-address', [ShippingAddressController::class, 'create'])->name('frontend.pages.shipping-address');
Route::post('/shipping-address', [ShippingAddressController::class, 'store'])->name('shipping.address.store');
Route::get('/shipping-address/{shippingAddress}/edit', [ShippingAddressController::class, 'edit'])->name('frontend.pages.edit-shipping-address');
Route::get('/back-to-checkout', [ShippingAddressController::class, 'backToCheckout'])->name('backToCheckout');

Route::put('/shipping-address/{shippingAddress}', [ShippingAddressController::class, 'update'])->name('shipping.address.update');
// web.php (Routes file)
Route::delete('/shipping-address/{shippingAddress}', [ShippingAddressController::class, 'destroy'])->name('frontend.pages.shipping-address.destroy');





Route::get('/shipping-address/regions', [ShippingAddressController::class, 'getRegions']);
Route::get('/shipping-address/regions/{regionCode}/provinces', [ShippingAddressController::class, 'getProvinces']);
Route::get('/shipping-address/provinces/{provinceCode}/cities-municipalities', [ShippingAddressController::class, 'getCitiesMunicipalities']);
// Route::get('/checkout/shipping/address', [CheckoutController::class, 'shippingAddress'])->name('frontend.pages.shipping.address');

// Eco-Track
//TODAYYYYYYY
Route::post('/ecotracker/tracker','EcoController@store')->name('ecotracker.store')->middleware('user');
Route::post('/ecotracker/tracker', [EcoController::class, 'store'])->name('ecotracker.store')->middleware('user');
Route::get('/ecotracker/tracker', [EcoController::class, 'showEcotracker'])->name('ecotracker')->middleware('user');

//Sales Report
Route::get('/sales','SalesController@index')->name('sales');
Route::get('/sales/pdf', 'SalesController@generatePdf')->name('sales.pdf');
Route::get('/sales/test', 'SalesController@test')->name('test');
Route::get('sales/csv', 'SalesController@csv')->name('sales.csv');
Route::get('/sales/csv', 'SalesController@generateCSV')->name('sales.csv');

// Route::post('/storeCheckout', 'CheckoutController@storeCheckout')->name('storeCheckout');
// Route::get('/checkout', 'FrontendController@showCheckout')->name('checkout');


Route::post('/cart/order', 'CartController@completeTask')->name('cart.order');
Route::get('/admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
Route::put('/admin/update-completion/{user}', 'AdminController@updateCompletion')->name('admin.update.completion');

Route::get('/cart',function(){
    return view('frontend.pages.cart');
})->name('cart');
Route::get('/checkout','CartController@checkout')->name('checkout')->middleware('user');
Route::post('/processCheckout','CartController@processCheckout')->name('processCheckout')->middleware('user');
// Wishlist
Route::get('/wishlist',function(){
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}','WishlistController@wishlist')->name('add-to-wishlist')->middleware('user');
Route::get('wishlist-delete/{id}','WishlistController@wishlistDelete')->name('wishlist-delete');
Route::post('cart/order','OrderController@store')->name('cart.order');
Route::get('order/pdf/{id}', 'OrderController@pdf')->name('order.pdf');

Route::get('/income','OrderController@incomeChart')->name('product.order.income');
// Route::get('/user/chart','AdminController@userPieChart')->name('user.piechart');
Route::get('/product-grids','FrontendController@productGrids')->name('product-grids');
Route::get('/product-lists','FrontendController@productLists')->name('product-lists');
Route::match(['get','post'],'/filter','FrontendController@productFilter')->name('shop.filter');
// Order Track
Route::get('/product/track','OrderController@orderTrack')->name('order.track');
Route::post('product/track/order','OrderController@productTrackOrder')->name('product.track.order');
// Blog
Route::get('/blog','FrontendController@blog')->name('blog');
Route::get('/blog-detail/{slug}','FrontendController@blogDetail')->name('blog.detail');
Route::get('/blog/search','FrontendController@blogSearch')->name('blog.search');
Route::post('/blog/filter','FrontendController@blogFilter')->name('blog.filter');
Route::get('blog-cat/{slug}','FrontendController@blogByCategory')->name('blog.category');
Route::get('blog-tag/{slug}','FrontendController@blogByTag')->name('blog.tag');

// EcoTracker
Route::get('/ecotracker','FrontendController@ecotracker')->name('ecotracker');
Route::get('/ecotracker','EcoController@showEcotracker')->name('ecotracker');

// NewsLetter
Route::post('/subscribe','FrontendController@subscribe')->name('subscribe');

// Product Review
Route::resource('/review','ProductReviewController');
Route::post('product/{slug}/review','ProductReviewController@store')->name('review.store');

// Post Comment
Route::post('post/{slug}/comment','PostCommentController@store')->name('post-comment.store');
Route::resource('/comment','PostCommentController');
// Coupon
Route::post('/coupon-store','CouponController@couponStore')->name('coupon-store');
// Payment
Route::get('payment', 'PayPalController@payment')->name('payment');
Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
Route::get('payment/success', 'PayPalController@success')->name('payment.success');



// Backend section start

Route::group(['prefix'=>'/admin','middleware'=>['auth','admin']],function(){
    Route::get('/','AdminController@index')->name('admin');
    Route::get('/file-manager',function(){
        return view('backend.layouts.file-manager');
    })->name('file-manager');
    // user route
    Route::resource('users','UsersController');
    // Banner
    Route::resource('banner','BannerController');
    //Track
    Route::resource('/tracker','EcoController');
    Route::get('/ecotracker/tracker/{id}','EcoController@show')->name('ecotracker.show');
    // Brand
    Route::resource('brand','BrandController');
    // Profile
    Route::get('/profile','AdminController@profile')->name('admin-profile');
    Route::post('/profile/{id}','AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::resource('/category','CategoryController');
    // Voucher
    Route::resource('/voucher','VoucherController');
    // Ajax for sub category
    Route::post('/voucher/{id}/child','VoucherController@getChildByParent');
    // Product
    Route::resource('/product','ProductController');
    // Ajax for sub category
    Route::post('/category/{id}/child','CategoryController@getChildByParent');
    // POST category
    Route::resource('/post-category','PostCategoryController');
    // Post tag
    Route::resource('/post-tag','PostTagController');
    // Post
    Route::resource('/post','PostController');
    // Message
    Route::resource('/message','MessageController');
    Route::get('/message/five','MessageController@messageFive')->name('messages.five');

    // Order
    Route::resource('/order','OrderController');
    // Shipping
    Route::resource('/shipping','ShippingController');
    // Coupon
    Route::resource('/coupon','CouponController');
    // Settings
    Route::get('settings','AdminController@settings')->name('settings');
    Route::post('setting/update','AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}','NotificationController@show')->name('admin.notification');
    Route::get('/notifications','NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}','NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');
});










// User section start
Route::group(['prefix'=>'/user','middleware'=>['user']],function(){
    Route::get('/','HomeController@index')->name('user');

     // Profile
     Route::get('/profile','HomeController@profile')->name('user-profile');
     Route::post('/profile/{id}','HomeController@profileUpdate')->name('user-profile-update');

    //Ecotrack
     Route::get('/ecotrack/ecoindex', 'HomeController@ecoindex')->name('user.ecotrack.ecoindex');
     Route::get('/ecotrack/ecoshow/{id}', 'HomeController@ecoshow')->name('user.ecotrack.ecoshow');


    //  Order
    Route::get('/order',"HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}',"HomeController@orderShow")->name('user.order.show');
    Route::get('/order/edit/{id}',"HomeController@orderEdit")->name('user.order.edit');
    Route::match(['get', 'patch'], '/order/update/{id}', "HomeController@orderUpdate")->name('user.order.update');
    Route::delete('/order/delete/{id}','HomeController@userOrderDelete')->name('user.order.delete');

    // Product Review
    Route::get('/user-review','HomeController@productReviewIndex')->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}','HomeController@productReviewDelete')->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}','HomeController@productReviewEdit')->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}','HomeController@productReviewUpdate')->name('user.productreview.update');

    // Post comment
    Route::get('user-post/comment','HomeController@userComment')->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}','HomeController@userCommentDelete')->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}','HomeController@userCommentEdit')->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}','HomeController@userCommentUpdate')->name('user.post-comment.update');

    // Password Change
    Route::get('change-password', 'HomeController@changePassword')->name('user.change.password.form');
    Route::post('change-password', 'HomeController@changPasswordStore')->name('change.password');

    // FAQS
    Route::get('/faqs', function () {
        // Your logic for FAQs page goes here
        return view('frontend.pages.faqs');
    })->name('faqs');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
