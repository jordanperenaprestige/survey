<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::get('/admin/login', 'AdminAuth\AuthController@login')->name('admin.login');
Route::post('/admin/login', 'AdminAuth\AuthController@adminLogin')->name('admin.admin-login');

Route::group(['middleware' => 'auth:admin'], function () {

    Route::get('/admin', 'Admin\DashboardController@index')->name('admin.dashboard');
    Route::get('/admin/dashboad/room/update/{id}', 'Admin\DashboardController@update')->name('admin.room.update');
    Route::get('/admin/dashboad/room/get-survey', 'Admin\DashboardController@getRoomSurvey')->name('admin.room.surveys');
    Route::post('/admin/dashboard/room/store-update', 'Admin\DashboardController@storeUpdate')->name('admin.room.store-update');
    // for report
    Route::get('/admin/dashboard/average-time-by-daily/list', 'Admin\DashboardController@getAverageTimeByDaily')->name('admin.dashboard.average-time-by-daily.list');
    Route::get('/admin/dashboard/total-sms-by-daily/list', 'Admin\DashboardController@getTotalSMSByDaily')->name('admin.dashboard.total-sms-by-daily.list');
    Route::get('/admin/dashboard/trend-report-by-daily/list', 'Admin\DashboardController@getTrendReportByDaily')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-daily.list');
    Route::get('/admin/dashboard/trend-incident-by-daily/list', 'Admin\DashboardController@getTrendIncidentByDaily')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-daily.list');
    Route::get('/admin/dashboard/donut-report-by-daily/list', 'Admin\DashboardController@getDonutReportByDaily')->name('admin.dashboard.donut-by-daily.list');
    Route::get('/admin/dashboard/donut-report-by-daily-answer/list', 'Admin\DashboardController@getDonutReportByDailyAnswer')->name('admin.dashboard.donut-by-daily-answer.list');

    Route::get('/admin/dashboard/average-time-by-day/list', 'Admin\DashboardController@getAverageTimeByDay')->name('admin.dashboard.average-time-by-day.list');
    Route::get('/admin/dashboard/total-sms-by-day/list', 'Admin\DashboardController@getTotalSMSByDay')->name('admin.dashboard.total-sms-by-day.list');
    Route::get('/admin/dashboard/trend-report-by-day/list', 'Admin\DashboardController@getTrendReportByDay')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-day.list');
    Route::get('/admin/dashboard/trend-incident-by-day/list', 'Admin\DashboardController@getTrendIncidentByDay')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-day.list');
    Route::get('/admin/dashboard/donut-report-by-day/list', 'Admin\DashboardController@getDonutReportByDay')->name('admin.dashboard.donut-by-day-answer.list');
    Route::get('/admin/dashboard/donut-report-by-day-answer/list', 'Admin\DashboardController@getDonutReportByDayAnswer')->name('admin.dashboard.donut-by-day.list');

    Route::get('/admin/dashboard/trend-report-by-daily-all/list', 'Admin\DashboardController@getTrendReportByDailyAll')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-daily-all.list');
    Route::get('/admin/dashboard/trend-incident-by-daily-all/list', 'Admin\DashboardController@getTrendIncidentByDailyAll')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-daily-all.list');

    Route::get('/admin/dashboard/average-time-by-week/list', 'Admin\DashboardController@getAverageTimeByWeek')->name('admin.dashboard.average-time-by-week.list');
    Route::get('/admin/dashboard/total-sms-by-week/list', 'Admin\DashboardController@getTotalSMSByWeek')->name('admin.dashboard.total-sms-by-week.list');
    Route::get('/admin/dashboard/trend-report-by-week/list', 'Admin\DashboardController@getTrendReportByWeek')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-week.list');
    Route::get('/admin/dashboard/trend-incident-by-week/list', 'Admin\DashboardController@getTrendIncidentByWeek')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-week.list');
    
    Route::get('/admin/dashboard/average-time-by-month/list', 'Admin\DashboardController@getAverageTimeByMonth')->name('admin.dashboard.average-time-by-month.list');
    Route::get('/admin/dashboard/total-sms-by-month/list', 'Admin\DashboardController@getTotalSMSByMonth')->name('admin.dashboard.total-sms-by-month.list');
    Route::get('/admin/dashboard/total-sms-by-month/list', 'Admin\DashboardController@getTotalSMSByMonth')->name('admin.dashboard.total-sms-by-month.list');
    Route::get('/admin/dashboard/trend-report-by-month/list', 'Admin\DashboardController@getTrendReportByMonth')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-month.list');
    Route::get('/admin/dashboard/trend-incident-by-month/list', 'Admin\DashboardController@getTrendIncidentByMonth')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-month.list');
    Route::get('/admin/dashboard/donut-report-by-month/list', 'Admin\DashboardController@getDonutReportByMonth')->name('admin.dashboard.donut-by-month-answer.list');
    Route::get('/admin/dashboard/donut-report-by-month-answer/list', 'Admin\DashboardController@getDonutReportByMonthAnswer')->name('admin.dashboard.donut-by-month.list');

    Route::get('/admin/dashboard/average-time-by-year/list', 'Admin\DashboardController@getAverageTimeByYear')->name('admin.dashboard.average-time-by-year.list');
    Route::get('/admin/dashboard/total-sms-by-year/list', 'Admin\DashboardController@getTotalSMSByYear')->name('admin.dashboard.total-sms-by-year.list');
    Route::get('/admin/dashboard/trend-report-by-year/list', 'Admin\DashboardController@getTrendReportByYear')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-year.list');
    Route::get('/admin/dashboard/trend-incident-by-year/list', 'Admin\DashboardController@getTrendIncidentByYear')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-year.list');
    // Route::get('/admin/reports/donut-report-by-year/list', 'Admin\ReportsController@getDonutReportByYear')->name('admin.reports.donut-by-year-answer.list');
    // Route::get('/admin/reports/donut-report-by-year-answer/list', 'Admin\ReportsController@getDonutReportByYearAnswer')->name('admin.reports.donut-by-year.list');
    Route::get('/admin/dashboard/filter-survey-first-last', 'Admin\DashboardController@getSurveyFirstLast')->where('id', '[0-9]+')->name('admin.dashboard.survey-first-last');
    Route::get('/admin/dashboard/total-sms-by-years/list', 'Admin\DashboardController@getTotalSMSByYears')->name('admin.dashboard.total-sms-by-years.list');
    Route::get('/admin/dashboard/average-time-by-years/list', 'Admin\DashboardController@getAverageTimeByYears')->name('admin.dashboard.average-time-by-years.list');
    Route::get('/admin/dashboard/trend-report-by-years/list', 'Admin\DashboardController@getTrendReportByYears')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-years.list');
    Route::get('/admin/dashboard/trend-incident-by-years/list', 'Admin\DashboardController@getTrendIncidentByYears')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-years.list');


    /*
    |--------------------------------------------------------------------------
    | Admin Users Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/users', 'Admin\UsersController@index')->name('admin.users');
    Route::get('/admin/users/list', 'Admin\UsersController@list')->name('admin.users.list');
    Route::post('/admin/users/store', 'Admin\UsersController@store')->name('admin.users.store');
    Route::get('/admin/users/{id}', 'Admin\UsersController@details')->where('id', '[0-9]+')->name('admin.users.details');
    Route::put('/admin/users/update', 'Admin\UsersController@update')->name('admin.users.update');
    Route::get('/admin/users/delete/{id}', 'Admin\UsersController@delete')->where('id', '[0-9]+')->name('admin.user.delete');
    Route::get('/admin/users/download-csv', 'Admin\UsersController@downloadCsv')->name('admin.user.download-csv');
    Route::get('/admin/users/list-supervisor', 'Admin\UsersController@listSupervisor')->name('admin.users.list-supervisor');

    /*
    |--------------------------------------------------------------------------
    | Roles Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/roles', 'Admin\RolesController@index')->name('admin.roles');
    Route::get('/admin/roles/list', 'Admin\RolesController@list')->name('admin.roles.list');
    Route::post('/admin/roles/store', 'Admin\RolesController@store')->name('admin.roles.store');
    Route::get('/admin/roles/{id}', 'Admin\RolesController@details')->where('id', '[0-9]+')->name('admin.roles.details');
    Route::put('/admin/roles/update', 'Admin\RolesController@update')->name('admin.roles.update');
    Route::get('/admin/roles/delete/{id}', 'Admin\RolesController@delete')->where('id', '[0-9]+')->name('admin.roles.delete');
    Route::get('/admin/roles/modules', 'Admin\RolesController@getModules')->name('admin.roles.modules');
    Route::get('/admin/roles/get-admin', 'Admin\RolesController@getAdmin')->name('admin.roles.get-admin');
    Route::get('/admin/roles/get-portal', 'Admin\RolesController@getPortal')->name('admin.roles.get-portal');
    Route::get('/admin/roles/download-csv', 'Admin\RolesController@downloadCsv')->name('admin.roles.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Modules Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/modules', 'Admin\ModulesController@index')->name('admin.modules');
    Route::get('/admin/modules/list', 'Admin\ModulesController@list')->name('admin.modules.list');
    Route::post('/admin/modules/store', 'Admin\ModulesController@store')->name('admin.modules.store');
    Route::get('/admin/modules/{id}', 'Admin\ModulesController@details')->where('id', '[0-9]+')->name('admin.modules.details');
    Route::put('/admin/modules/update', 'Admin\ModulesController@update')->name('admin.modules.update');
    Route::get('/admin/modules/delete/{id}', 'Admin\ModulesController@delete')->where('id', '[0-9]+')->name('admin.modules.delete');
    Route::get('/admin/modules/get-all-links', 'Admin\ModulesController@getAllLinks')->where('id', '[0-9]+')->name('admin.modules.get-all-links');
    Route::get('/admin/modules/download-csv', 'Admin\ModulesController@downloadCsv')->name('admin.modules.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Categories Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/categories', 'Admin\CategoriesController@index')->name('admin.category');
    Route::get('/admin/category/list', 'Admin\CategoriesController@list')->name('admin.category.list');
    Route::post('/admin/category/store', 'Admin\CategoriesController@store')->name('admin.category.store');
    Route::get('/admin/category/{id}', 'Admin\CategoriesController@details')->where('id', '[0-9]+')->name('admin.category.details');
    Route::post('/admin/category/update', 'Admin\CategoriesController@update')->name('admin.category.update');
    Route::get('/admin/category/delete/{id}', 'Admin\CategoriesController@delete')->where('id', '[0-9]+')->name('admin.category.delete');
    Route::get('/admin/category/get-parent', 'Admin\CategoriesController@getParent')->where('id', '[0-9]+')->name('admin.category.get-parent');
    Route::get('/admin/category/get-all', 'Admin\CategoriesController@getAll')->name('admin.category.get-all');
    Route::get('/admin/category/get-all/{id}', 'Admin\CategoriesController@getAll')->where('id', '[0-9]+')->name('admin.category.get-sub-category');
    Route::get('/admin/category/download-csv', 'Admin\CategoriesController@downloadCsv')->name('admin.category.download-csv');
    // Route::post('/admin/category/delete-image', 'Admin\CategoriesController@deleteImage')->name('admin.category.delete-image');
    Route::get('/admin/category/labels/{id}', 'Admin\CategoriesController@getLabels')->where('id', '[0-9]+')->name('admin.category.labels');
    Route::post('/admin/category/label/store', 'Admin\CategoriesController@saveLabels')->name('admin.category.label.store');
    Route::get('/admin/category/label/delete/{id}', 'Admin\CategoriesController@deleteLabel')->where('id', '[0-9]+')->name('admin.category.label.delete');

    /*
    |--------------------------------------------------------------------------
    | Supplemental Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/supplementals', 'Admin\SupplementalController@index')->name('admin.supplemental');
    Route::get('/admin/supplemental/list', 'Admin\SupplementalController@list')->name('admin.supplemental.list');
    Route::post('/admin/supplemental/store', 'Admin\SupplementalController@store')->name('admin.supplemental.store');
    Route::get('/admin/supplemental/{id}', 'Admin\SupplementalController@details')->where('id', '[0-9]+')->name('admin.supplemental.details');
    Route::post('/admin/supplemental/update', 'Admin\SupplementalController@update')->name('admin.supplemental.update');
    Route::get('/admin/supplemental/delete/{id}', 'Admin\SupplementalController@delete')->where('id', '[0-9]+')->name('admin.supplemental.delete');
    Route::get('/admin/supplemental/get-parent', 'Admin\SupplementalController@getParent')->where('id', '[0-9]+')->name('admin.supplemental.get-parent');
    Route::get('/admin/supplemental/get-child', 'Admin\SupplementalController@getChild')->where('id', '[0-9]+')->name('admin.supplemental.get-child');
    Route::get('/admin/supplemental/download-csv', 'Admin\SupplementalController@downloadCsv')->name('admin.supplemental.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Classifications Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/classifications', 'Admin\ClassificationController@index')->name('admin.classifications');
    Route::get('/admin/classification/list', 'Admin\ClassificationController@list')->name('admin.classification.list');
    Route::post('/admin/classification/store', 'Admin\ClassificationController@store')->name('admin.classification.store');
    Route::get('/admin/classification/{id}', 'Admin\ClassificationController@details')->where('id', '[0-9]+')->name('admin.classification.details');
    Route::put('/admin/classification/update', 'Admin\ClassificationController@update')->name('admin.classification.update');
    Route::get('/admin/classification/delete/{id}', 'Admin\ClassificationController@delete')->where('id', '[0-9]+')->name('admin.classification.delete');
    Route::get('/admin/classification/get-all', 'Admin\ClassificationController@getAll')->where('id', '[0-9]+')->name('admin.classification.get-all');
    Route::get('/admin/classification/download-csv', 'Admin\ClassificationController@downloadCsv')->name('admin.classification.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Companies Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/companies', 'Admin\CompaniesController@index')->name('admin.companies');
    Route::get('/admin/company/list', 'Admin\CompaniesController@list')->name('admin.company.list');
    Route::post('/admin/company/store', 'Admin\CompaniesController@store')->name('admin.company.store');
    Route::get('/admin/company/{id}', 'Admin\CompaniesController@details')->where('id', '[0-9]+')->name('admin.company.details');
    Route::put('/admin/company/update', 'Admin\CompaniesController@update')->name('admin.company.update');
    Route::get('/admin/company/delete/{id}', 'Admin\CompaniesController@delete')->where('id', '[0-9]+')->name('admin.company.delete');
    Route::get('/admin/company/get-all', 'Admin\CompaniesController@getAll')->where('id', '[0-9]+')->name('admin.company.get-all');
    Route::get('/admin/company/get-parent', 'Admin\CompaniesController@getParent')->where('id', '[0-9]+')->name('admin.company.get-parent');
    Route::get('/admin/company/get-brands/{company_id}', 'Admin\CompaniesController@getBrands')->where('id', '[0-9]+')->name('admin.company.get-brands');
    Route::get('/admin/company/download-csv', 'Admin\CompaniesController@downloadCsv')->name('admin.company.download-csv');
    Route::post('/admin/company/brand/store', 'Admin\CompaniesController@storeBrand')->name('admin.company.brand.store');
    Route::get('/admin/company/brand/delete/{id}/{company_id}', 'Admin\CompaniesController@deleteBrand')->where('id', '[0-9]+')->where('company_id', '[0-9]+')->name('admin.company.brand.delete');
    Route::post('/admin/company/contract/store', 'Admin\CompaniesController@storeContract')->name('admin.company.contract.store');
    Route::get('/admin/company/contract/{id}', 'Admin\CompaniesController@contractDetails')->name('admin.company.contract.details');
    Route::put('/admin/company/contract/update', 'Admin\CompaniesController@updateContract')->name('admin.company.contract.update');
    Route::get('/admin/company/contract/delete/{id}', 'Admin\CompaniesController@deleteContract')->where('id', '[0-9]+')->name('admin.company.contract.delete');
    Route::get('/admin/company/contract/duplicate/{id}', 'Admin\CompaniesController@duplicateContract')->where('id', '[0-9]+')->name('admin.company.contract.duplicate');

    /*
    |--------------------------------------------------------------------------
    | Company User Workflows Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/company/workflows/{id}', 'Admin\CompanyWorkflowsController@index')->name('admin.company.workflows');
    Route::post('/admin/company/workflow/store', 'Admin\CompanyWorkflowsController@storeWorkflow')->name('admin.company.workflow.store');
    Route::get('/admin/company/workflowzzz/{id}', 'Admin\CompanyWorkflowsController@details')->name('admin.company.workflow.details');
    Route::put('/admin/company/workflow/update', 'Admin\CompanyWorkflowsController@update')->name('admin.company.workflow.update');
    Route::get('/admin/company/workflow/delete/{id}', 'Admin\CompanyWorkflowsController@delete')->where('id', '[0-9]+')->name('admin.company.workflow.delete');
    Route::get('/admin/company/workflow/get-list/{id}', 'Admin\CompanyWorkflowsController@getList')->name('admin.company.workflow.get-list');
    Route::get('/admin/company/workflow/get-company-details', 'Admin\CompanyWorkflowsController@getCompanyDetails')->name('admin.company.workflow.get-company-details');
    Route::get('/admin/company/workflow/get-users', 'Admin\CompanyWorkflowsController@getUsers')->name('admin.company.workflow.get-users');
    Route::get('/admin/company/workflow/download-csv', 'Admin\CompanyWorkflowsController@downloadCsv')->name('admin.company.workflow.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Amenities Routesadmin/tags
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/amenities', 'Admin\AmenitiesController@index')->name('admin.amenities');
    Route::get('/admin/amenity/list', 'Admin\AmenitiesController@list')->name('admin.amenity.list');
    Route::post('/admin/amenity/store', 'Admin\AmenitiesController@store')->name('admin.amenity.store');
    Route::get('/admin/amenity/{id}', 'Admin\AmenitiesController@details')->where('id', '[0-9]+')->name('admin.amenity.details');
    Route::post('/admin/amenity/update', 'Admin\AmenitiesController@update')->name('admin.amenity.update');
    Route::get('/admin/amenity/delete/{id}', 'Admin\AmenitiesController@delete')->where('id', '[0-9]+')->name('admin.amenity.delete');
    Route::get('/admin/amenity/download-csv', 'Admin\AmenitiesController@downloadCsv')->name('admin.amenity.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Tags Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/tags', 'Admin\TagsController@index')->name('admin.tags');
    Route::get('/admin/tag/list', 'Admin\TagsController@list')->name('admin.tag.list');
    Route::post('/admin/tag/store', 'Admin\TagsController@store')->name('admin.tag.store');
    Route::get('/admin/tag/{id}', 'Admin\TagsController@details')->where('id', '[0-9]+')->name('admin.tag.details');
    Route::put('/admin/tag/update', 'Admin\TagsController@update')->name('admin.tag.update');
    Route::get('/admin/tag/delete/{id}', 'Admin\TagsController@delete')->where('id', '[0-9]+')->name('admin.tag.delete');
    Route::post('/admin/tag/batch-upload', 'Admin\TagsController@batchUpload')->name('admin.tag.batch-upload');
    Route::get('/admin/tag/download-csv', 'Admin\TagsController@downloadCsv')->name('admin.tag.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Illustration Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/Illustrations', 'Admin\IllustrationsController@index')->name('admin.Illustrations');
    Route::get('/admin/Illustration/list', 'Admin\IllustrationsController@list')->name('admin.Illustration.list');
    Route::post('/admin/Illustration/store', 'Admin\IllustrationsController@store')->name('admin.Illustration.store');
    Route::get('/admin/Illustration/{id}', 'Admin\IllustrationsController@details')->where('id', '[0-9]+')->name('admin.Illustration.details');
    Route::post('/admin/Illustration/update', 'Admin\IllustrationsController@update')->name('admin.Illustration.update');
    Route::get('/admin/Illustration/delete/{id}', 'Admin\IllustrationsController@delete')->where('id', '[0-9]+')->name('admin.Illustration.delete');
    Route::get('/admin/Illustration/download-csv', 'Admin\IllustrationsController@downloadCsv')->name('admin.illustration.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Brands Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/brands', 'Admin\BrandController@index')->name('admin.brands');
    Route::get('/admin/brand/list', 'Admin\BrandController@list')->name('admin.brand.list');
    Route::post('/admin/brand/store', 'Admin\BrandController@store')->name('admin.brand.store');
    Route::get('/admin/brand/{id}', 'Admin\BrandController@details')->where('id', '[0-9]+')->name('admin.brand.details');
    Route::post('/admin/brand/update', 'Admin\BrandController@update')->name('admin.brand.update');
    Route::get('/admin/brand/delete/{id}', 'Admin\BrandController@delete')->where('id', '[0-9]+')->name('admin.brand.delete');
    Route::post('/admin/brand/batch-upload', 'Admin\BrandController@batchUpload')->name('admin.brand.batch-upload');
    Route::get('/admin/brand/get-supplementals', 'Admin\BrandController@getSupplementals')->where('id', '[0-9]+')->name('admin.brand.get-supplementals');
    Route::get('/admin/brand/get-tags', 'Admin\BrandController@getTags')->where('id', '[0-9]+')->name('admin.brand.get-tags');
    Route::get('/admin/brand/get-all', 'Admin\BrandController@allBrands')->where('id', '[0-9]+')->name('admin.brand.get-all');
    Route::get('/admin/brand/download-csv', 'Admin\BrandController@downloadCsv')->name('admin.brand.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Brands Products Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/brand/products/{id}', 'Admin\ProductsController@index')->where('id', '[0-9]+')->name('admin.brand.products');
    Route::get('/admin/brand/product/list', 'Admin\ProductsController@list')->name('admin.brand.product.list');
    Route::get('/admin/brand/product/{id}', 'Admin\ProductsController@details')->where('id', '[0-9]+')->name('admin.brand.product.details');
    Route::post('/admin/brand/product/store', 'Admin\ProductsController@store')->name('admin.brand.product.store');
    Route::post('/admin/brand/product/update', 'Admin\ProductsController@update')->name('admin.brand.product.update');
    Route::get('/admin/brand/product/delete/{id}', 'Admin\ProductsController@delete')->where('id', '[0-9]+')->name('admin.brand.product.delete');
    Route::get('/admin/brand/product-by-id/{id}', 'Admin\ProductsController@getProductsByBrand')->where('id', '[0-9]+')->name('admin.brand.product.by-brand');

    /*
    |--------------------------------------------------------------------------
    | Sites Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/sites', 'Admin\SiteController@index')->name('admin.sites');
    Route::get('/admin/site/list', 'Admin\SiteController@list')->name('admin.site.list');
    Route::post('/admin/site/store', 'Admin\SiteController@store')->name('admin.site.store');
    Route::get('/admin/site/{id}', 'Admin\SiteController@details')->where('id', '[0-9]+')->name('admin.site.details');
    Route::post('/admin/site/update', 'Admin\SiteController@update')->name('admin.site.update');
    Route::get('/admin/site/delete/{id}', 'Admin\SiteController@delete')->where('id', '[0-9]+')->name('admin.site.delete');
    Route::get('/admin/site/get-all', 'Admin\SiteController@getAll')->where('id', '[0-9]+')->name('admin.site.get-all');
    Route::get('/admin/site/set-default/{id}', 'Admin\SiteController@setDefault')->where('id', '[0-9]+')->name('admin.site.set-default');
    Route::get('/admin/site/download-csv', 'Admin\SiteController@downloadCsv')->name('admin.site.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/buildings/{id}', 'Admin\BuildingsController@index')->name('admin.site.buildings');
    Route::get('/admin/site/building/list', 'Admin\BuildingsController@list')->name('admin.site.building.list');
    Route::post('/admin/site/building/store', 'Admin\BuildingsController@store')->name('admin.site.building.store');
    Route::get('/admin/site/building/{id}', 'Admin\BuildingsController@details')->where('id', '[0-9]+')->name('admin.site.building.details');
    Route::put('/admin/site/building/update', 'Admin\BuildingsController@update')->name('admin.site.building.update');
    Route::get('/admin/site/building/delete/{id}', 'Admin\BuildingsController@delete')->where('id', '[0-9]+')->name('admin.site.building.delete');
    Route::get('/admin/site/buildings', 'Admin\BuildingsController@getAll')->where('id', '[0-9]+')->name('admin.site.buildings.all');
    Route::get('/admin/site/get-buildings/{id}', 'Admin\BuildingsController@getBuildings')->where('id', '[0-9]+')->name('admin.site.buildings.get-buildings');
    Route::get('/admin/site/get-building-by-id', 'Admin\BuildingsController@getBuildingByIds')->where('id', '[0-9]+')->name('admin.site.buildings.get-building-by-id');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Floors Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/floor/list', 'Admin\FloorsController@list')->name('admin.site.floor.list');
    Route::post('/admin/site/floor/store', 'Admin\FloorsController@store')->name('admin.site.floor.store');
    Route::get('/admin/site/floor/{id}', 'Admin\FloorsController@details')->where('id', '[0-9]+')->name('admin.site.floor.details');
    Route::post('/admin/site/floor/update', 'Admin\FloorsController@update')->name('admin.site.floor.update');
    Route::get('/admin/site/floor/delete/{id}', 'Admin\FloorsController@delete')->where('id', '[0-9]+')->name('admin.site.floor.delete');
    Route::get('/admin/site/floors/{id}', 'Admin\FloorsController@getFloors')->where('id', '[0-9]+')->name('admin.site.floors');
    Route::get('/admin/site/get-building-level-by-id', 'Admin\FloorsController@getBuildingLevelByIds')->where('id', '[0-9]+')->name('admin.site.buildings.get-building-level-by-id');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Floors Rooms Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/room/list', 'Admin\RoomsController@list')->name('admin.site.room.list');
    Route::post('/admin/site/room/store', 'Admin\RoomsController@store')->name('admin.site.room.store');
    Route::get('/admin/site/room/{id}', 'Admin\RoomsController@details')->where('id', '[0-9]+')->name('admin.site.room.details');
    Route::post('/admin/site/room/update', 'Admin\RoomsController@update')->name('admin.site.room.update');
    Route::get('/admin/site/room/delete/{id}', 'Admin\RoomsController@delete')->where('id', '[0-9]+')->name('admin.site.room.delete');
    Route::get('/admin/site/floors/rooms/{id}', 'Admin\RoomsController@getRooms')->where('id', '[0-9]+')->name('admin.site.floors.rooms');
    Route::get('/admin/site/get-building-level-room-by-id', 'Admin\RoomsController@getBuildingLevelRoomByIds')->where('id', '[0-9]+')->name('admin.site.buildings.get-building-level-room-by-id');

    /*
    |--------------------------------------------------------------------------
    | Sites Screens Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/screens', 'Admin\ScreensController@index')->name('admin.site.screens');
    Route::get('/admin/site/screen/list', 'Admin\ScreensController@list')->name('admin.site.screen.list');
    Route::post('/admin/site/screen/store', 'Admin\ScreensController@store')->name('admin.site.screen.store');
    Route::get('/admin/site/screen/{id}', 'Admin\ScreensController@details')->where('id', '[0-9]+')->name('admin.site.screen.details');
    Route::put('/admin/site/screen/update', 'Admin\ScreensController@update')->name('admin.site.screen.update');
    Route::get('/admin/site/screen/delete/{id}', 'Admin\ScreensController@delete')->where('id', '[0-9]+')->name('admin.site.screen.delete');
    Route::get('/admin/site/screen/get-screens/{ids}/{type}', 'Admin\ScreensController@getScreens')->name('admin.site.screen.get-screens');
    Route::get('/admin/site/screen/get-screens/{ids}', 'Admin\ScreensController@getScreens')->name('admin.site.screen.get-screens-ids');
    Route::get('/admin/site/screen/get-all', 'Admin\ScreensController@getAllScreens')->name('admin.site.screen.get-all');
    Route::get('/admin/site/screen/set-default/{id}', 'Admin\ScreensController@setDefault')->where('id', '[0-9]+')->name('admin.site.screen.set-default');
    Route::get('/admin/site/screen/download-csv', 'Admin\ScreensController@downloadCsv')->name('admin.site-screen.download-csv');

    Route::get('/admin/site/maps', 'Admin\SiteMapController@index')->name('admin.site.maps');
    Route::get('/admin/site/maps/list', 'Admin\SiteMapController@list')->name('admin.site.maps.list');
    /*
    |--------------------------------------------------------------------------
    | Sites Tenants Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/tenants', 'Admin\SiteTenantsController@index')->name('admin.site.tenants');
    Route::get('/admin/site/tenant/list', 'Admin\SiteTenantsController@list')->name('admin.site.tenant.list');
    Route::post('/admin/site/tenant/store', 'Admin\SiteTenantsController@store')->name('admin.site.tenant.store');
    Route::get('/admin/site/tenant/{id}', 'Admin\SiteTenantsController@details')->where('id', '[0-9]+')->name('admin.site.tenant.details');
    Route::post('/admin/site/tenant/update', 'Admin\SiteTenantsController@update')->name('admin.site.tenant.update');
    Route::get('/admin/site/tenant/delete/{id}', 'Admin\SiteTenantsController@delete')->where('id', '[0-9]+')->name('admin.site.tenant.delete');
    Route::get('/admin/site/tenant/get-tenants/{ids}', 'Admin\SiteTenantsController@getTenants')->name('admin.site.tenant.get-tenants');
    Route::get('/admin/site/tenant/get-tenants-per-floor/{id}', 'Admin\SiteTenantsController@getTenantPerFloor')->where('id', '[0-9]+')->name('admin.site.tenant.get-tenants-floor');
    Route::post('/admin/site/tenant/batch-upload', 'Admin\SiteTenantsController@batchUpload')->name('admin.site.tenant.batch-upload');
    Route::get('/admin/site/tenant/products/{id}', 'Admin\SiteTenantsController@products')->where('id', '[0-9]+')->name('admin.site.tenant-products');
    Route::post('/admin/site/tenant/store-brand-products', 'Admin\SiteTenantsController@saveBrandProduct')->name('admin.site.tenant.brand-products');
    Route::get('/admin/site/tenant/product/list/{id}', 'Admin\SiteTenantsController@tenantProducts')->where('id', '[0-9]+')->name('admin.site.tenant.product-list');
    Route::get('/admin/site/tenant/product/delete/{tid}/{id}', 'Admin\SiteTenantsController@deleteProduct')->where('tid', '[0-9]+')->where('id', '[0-9]+')->name('admin.site.tenant.delete-product');
    Route::get('/admin/site/tenant/download-csv', 'Admin\SiteTenantsController@downloadCsv')->name('admin.site-tenant.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Sites Maps Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/manage-map/{id}', 'Admin\MapsController@index')->where('id', '[0-9]+')->name('admin.site.manage.map');
    Route::get('/admin/site/manage-map/list/{id}', 'Admin\MapsController@list')->where('id', '[0-9]+')->name('admin.site.manage.map.list');
    Route::get('/admin/site/manage-map/details/{id}', 'Admin\MapsController@details')->where('id', '[0-9]+')->name('admin.site.manage.map.details');
    Route::post('/admin/site/manage-map/store', 'Admin\MapsController@store')->name('admin.site.manage.map.store');
    Route::post('/admin/site/manage-map/update', 'Admin\MapsController@update')->name('admin.site.manage.map.update');
    Route::get('/admin/site/manage-map/delete/{id}', 'Admin\MapsController@delete')->where('id', '[0-9]+')->name('admin.site.manage.map.delete');

    Route::get('/admin/site/map/{id}', 'Admin\MapsController@getMapDetails')->where('id', '[0-9]+')->name('admin.site.map');
    Route::get('/admin/site/map/get-points/{id}', 'Admin\MapsController@getSitePoints')->where('id', '[0-9]+')->name('admin.site.map.get-points');
    Route::get('/admin/site/map/get-links/{id}', 'Admin\MapsController@getSiteLinks')->where('id', '[0-9]+')->name('admin.site.map.get-links');
    Route::post('/admin/site/map/create-point', 'Admin\MapsController@createPoint')->name('admin.site.map.create-point');
    Route::post('/admin/site/map/update-point', 'Admin\MapsController@updatePoint')->name('admin.site.map.update-point');
    Route::post('/admin/site/map/update-details', 'Admin\MapsController@updatePointDetails')->name('admin.site.map.update-details');
    Route::get('/admin/site/map/delete-point/{id}', 'Admin\MapsController@deletePoint')->where('id', '[0-9]+')->name('admin.site.map.delete-point');
    Route::get('/admin/site/map/point-info/{id}', 'Admin\MapsController@pointInfo')->where('id', '[0-9]+')->name('admin.site.map.point-info');
    Route::post('/admin/site/map/connect-point', 'Admin\MapsController@connectPoints')->name('admin.site.map.connect-point');
    Route::get('/admin/site/map/delete-line/{id}', 'Admin\MapsController@deleteLine')->where('id', '[0-9]+')->name('admin.site.map.delete-line');
    Route::get('/admin/site/map/set-default/{id}', 'Admin\MapsController@setDefault')->where('id', '[0-9]+')->name('admin.site.map.set-default');
    Route::get('/admin/site/map/generate-routes/{site_id}/{screen_id}', 'Admin\MapsController@generateRoutes')->where('id', '[0-9]+')->name('admin.site.map.generate-routes');

    /*
    |--------------------------------------------------------------------------
    | Advertisements Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/manage-ads/upload', 'Admin\AdvertisementController@index')->name('admin.manage-ads.upload');
    Route::get('/admin/manage-ads/list', 'Admin\AdvertisementController@list')->name('admin.manage-ads.list');
    Route::post('/admin/manage-ads/store', 'Admin\AdvertisementController@store')->name('admin.manage-ads.store');
    Route::get('/admin/manage-ads/{id}', 'Admin\AdvertisementController@details')->where('id', '[0-9]+')->name('admin.manage-ads.details');
    Route::post('/admin/manage-ads/update', 'Admin\AdvertisementController@update')->name('admin.manage-ads.update');
    Route::get('/admin/manage-ads/delete/{id}', 'Admin\AdvertisementController@delete')->where('id', '[0-9]+')->name('admin.manage-ads.delete');
    Route::get('/admin/manage-ads/all', 'Admin\AdvertisementController@getAllType')->name('admin.manage-ads.all');
    Route::get('/admin/manage-ads/material/{id}', 'Admin\AdvertisementController@getMaterialDetails')->where('id', '[0-9]+')->name('admin.manage-ads.material.details');
    Route::get('/admin/manage-ads/material/delete/{id}', 'Admin\AdvertisementController@deleteMaterial')->where('id', '[0-9]+')->name('admin.manage-ads.material.delete');

    /*
    |--------------------------------------------------------------------------
    | Content Management
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/content-management', 'Admin\ContentManagementController@index')->name('admin.content-management');
    Route::get('/admin/content-management/list', 'Admin\ContentManagementController@list')->name('admin.content-management.list');
    Route::post('/admin/content-management/store', 'Admin\ContentManagementController@store')->name('admin.content-management.store');
    Route::get('/admin/content-management/{id}', 'Admin\ContentManagementController@details')->where('id', '[0-9]+')->name('admin.content-management.details');
    Route::put('/admin/content-management/update', 'Admin\ContentManagementController@update')->name('admin.content-management.update');
    Route::get('/admin/content-management/delete/{id}', 'Admin\ContentManagementController@delete')->where('id', '[0-9]+')->name('admin.content-management.delete');
    Route::get('/admin/content-management/transaction-statuses', 'Admin\ContentManagementController@getTransactionStatuses')->where('id', '[0-9]+')->name('admin.content-management.transaction-statuses');
    Route::get('/admin/play-list', 'Admin\ContentManagementController@playlist')->name('admin.play-list');
    Route::get('/admin/play-list/list', 'Admin\ContentManagementController@getPLayList')->name('admin.play-list.list');
    Route::post('/admin/play-list/update-sequence', 'Admin\ContentManagementController@updateSequence')->name('admin.play-list.update-sequence');
    Route::post('/admin/play-list/batch-upload', 'Admin\ContentManagementController@batchUpload')->name('admin.play-list.batch-upload');

    /*
    |--------------------------------------------------------------------------
    | Genre Routes
    |--------------------------------------------------------------------------
    *
    Route::get('/admin/cinema/genres', 'Admin\GenresController@index')->name('admin.genres');
    Route::get('/admin/cinema/genre/list', 'Admin\GenresController@list')->name('admin.genre.list');
    Route::post('/admin/cinema/genre/store', 'Admin\GenresController@store')->name('admin.genre.store');
    Route::get('/admin/cinema/genre/{id}', 'Admin\GenresController@details')->where('id', '[0-9]+')->name('admin.genre.details');
    Route::put('/admin/cinema/genre/update', 'Admin\GenresController@update')->name('admin.genre.update');
    Route::get('/admin/cinema/genre/delete/{id}', 'Admin\GenresController@delete')->where('id', '[0-9]+')->name('admin.genre.delete');

    /*
    |--------------------------------------------------------------------------
    | Site Code Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/cinema/site-codes', 'Admin\CinemaSiteController@index')->name('admin.site-codes');
    Route::get('/admin/cinema/site-code/list', 'Admin\CinemaSiteController@list')->name('admin.site-code.list');
    Route::post('/admin/cinema/site-code/store', 'Admin\CinemaSiteController@store')->name('admin.site-code.store');
    Route::get('/admin/cinema/site-code/{id}', 'Admin\CinemaSiteController@details')->where('id', '[0-9]+')->name('admin.site-code.details');
    Route::put('/admin/cinema/site-code/update', 'Admin\CinemaSiteController@update')->name('admin.site-code.update');
    Route::get('/admin/cinema/site-code/delete/{id}', 'Admin\CinemaSiteController@delete')->where('id', '[0-9]+')->name('admin.site-code.delete');
    Route::get('/admin/cinema/site-code/download-csv', 'Admin\CinemaSiteController@downloadCsv')->name('admin.site-code.download-csv');
    /*
    |--------------------------------------------------------------------------
    | Cinema Schedules Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/cinema/schedules', 'Admin\CinemasScheduleController@index')->name('admin.schedules');
    Route::get('/admin/cinema/schedule/list', 'Admin\CinemasScheduleController@list')->name('admin.schedule.list');
    Route::post('/admin/cinema/schedule/store', 'Admin\CinemasScheduleController@store')->name('admin.schedule.store');
    Route::get('/admin/cinema/schedule/{id}', 'Admin\CinemasScheduleController@details')->where('id', '[0-9]+')->name('admin.schedule.details');
    Route::get('/admin/cinema/schedule/delete/{id}', 'Admin\CinemasScheduleController@delete')->where('id', '[0-9]+')->name('admin.schedule.delete');
    Route::get('/admin/cinema/schedule/site-codes', 'Admin\CinemasScheduleController@getSiteCodes')->name('admin.cinema.site-codes');
    Route::get('/admin/cinema/schedule/download-csv', 'Admin\CinemasScheduleController@downloadCsv')->name('admin.cinema.download-csv');
    /*
    |--------------------------------------------------------------------------
    | Reports Routes
    |--------------------------------------------------------------------------
    */


    Route::get('/admin/reports/merchant-population', 'Admin\ReportsController@index')->name('admin.reports.merchant-population');
    Route::get('/admin/reports/merchant-population/list', 'Admin\ReportsController@getPopulationReport')->name('admin.reports.merchant-population.list');
    Route::get('/admin/reports/merchant-population-two/list', 'Admin\ReportsController@getPopulationReportTwo')->name('admin.reports.merchant-population-two.list');
    Route::get('/admin/reports/merchant-population/download-csv', 'Admin\ReportsController@downloadCsvPopulation')->name('admin.reports.merchant-population.download-csv');
    Route::get('/admin/reports/top-tenant-search', 'Admin\ReportsController@topTenantSearch')->name('admin.reports.top-tenant-search');
    Route::get('/admin/reports/top-tenant-search/list', 'Admin\ReportsController@getTenantSearch')->where('id', '[0-9]+')->name('admin.reports.top-tenant-search.list');
    Route::get('/admin/reports/top-tenant-search/download-csv', 'Admin\ReportsController@downloadCsvTenantSearch')->where('id', '[0-9]+')->name('admin.reports.top-tenant-search.download-csv');
    Route::get('/admin/reports/most-search-keywords', 'Admin\ReportsController@mostSearchKeywords')->name('admin.reports.most-search-keywords');
    Route::get('/admin/reports/most-search-keywords/list', 'Admin\ReportsController@getSearchKeywords')->where('id', '[0-9]+')->name('admin.reports.most-search-keywords.list');
    Route::get('/admin/reports/most-search-keywords/download-csv', 'Admin\ReportsController@downloadCsvSearchKeywords')->where('id', '[0-9]+')->name('admin.reports.most-search-keywords.download-csv');
    Route::get('/admin/reports/merchant-usage', 'Admin\ReportsController@merchantUsage')->name('admin.reports.merchant-usage');
    Route::get('/admin/reports/merchant-usage/list', 'Admin\ReportsController@getMerchantUsage')->where('id', '[0-9]+')->name('admin.reports.merchant-usage.list');
    Route::get('/admin/reports/merchant-usage/download-csv', 'Admin\ReportsController@downloadCsvmerchantUsage')->where('id', '[0-9]+')->name('admin.reports.merchant-usage.download-csv');
    Route::get('/admin/reports/monthly-usage/list', 'Admin\ReportsController@monthlyUsage')->name('admin.reports.monthly-usage');

    Route::get('/admin/reports/trend/list', 'Admin\ReportsController@getTrend')->where('id', '[0-9]+')->name('admin.reports.trend.list'); //

    Route::get('/admin/reports/average-time-by-daily/list', 'Admin\ReportsController@getAverageTimeByDaily')->name('admin.reports.average-time-by-daily.list');
    Route::get('/admin/reports/total-sms-by-daily/list', 'Admin\ReportsController@getTotalSMSByDaily')->name('admin.reports.total-sms-by-daily.list');
    Route::get('/admin/reports/trend-report-by-daily/list', 'Admin\ReportsController@getTrendReportByDaily')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-daily.list');
    Route::get('/admin/reports/trend-incident-by-daily/list', 'Admin\ReportsController@getTrendIncidentByDaily')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-daily.list');
    Route::get('/admin/reports/donut-report-by-daily/list', 'Admin\ReportsController@getDonutReportByDaily')->name('admin.dashboard.donut-by-daily.list');
    Route::get('/admin/reports/donut-report-by-daily-answer/list', 'Admin\ReportsController@getDonutReportByDailyAnswer')->name('admin.dashboard.donut-by-daily-answer.list');

    Route::get('/admin/reports/trend-report-by-daily-all/list', 'Admin\ReportsController@getTrendReportByDailyAll')->where('id', '[0-9]+')->name('admin.dashboard.trend-report-by-daily-all.list');
    Route::get('/admin/reports/trend-incident-by-daily-all/list', 'Admin\ReportsController@getTrendIncidentByDailyAll')->where('id', '[0-9]+')->name('admin.dashboard.trend-incident-by-daily-all.list');


    Route::get('/admin/reports/average-time-by-day/list', 'Admin\ReportsController@getAverageTimeByDay')->name('admin.reports.average-time-by-day.list');
    Route::get('/admin/reports/total-sms-by-day/list', 'Admin\ReportsController@getTotalSMSByDay')->name('admin.reports.total-sms-by-day.list');
    Route::get('/admin/reports/trend-report-by-day/list', 'Admin\ReportsController@getTrendReportByDay')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-day.list');
    Route::get('/admin/reports/trend-incident-by-day/list', 'Admin\ReportsController@getTrendIncidentByDay')->where('id', '[0-9]+')->name('admin.reports.trend-incident-by-day.list');
    Route::get('/admin/reports/donut-report-by-day/list', 'Admin\ReportsController@getDonutReportByDay')->name('admin.reports.donut-by-day-answer.list');
    Route::get('/admin/reports/donut-report-by-day-answer/list', 'Admin\ReportsController@getDonutReportByDayAnswer')->name('admin.reports.donut-by-day.list');

    Route::get('/admin/reports/average-time-by-week/list', 'Admin\ReportsController@getAverageTimeByWeek')->name('admin.reports.average-time-by-week.list');
    Route::get('/admin/reports/total-sms-by-week/list', 'Admin\ReportsController@getTotalSMSByWeek')->name('admin.reports.total-sms-by-week.list');
    Route::get('/admin/reports/trend-report-by-week/list', 'Admin\ReportsController@getTrendReportByWeek')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-week.list');
    Route::get('/admin/reports/trend-incident-by-week/list', 'Admin\ReportsController@getTrendIncidentByWeek')->where('id', '[0-9]+')->name('admin.reports.trend-incident-by-week.list');
    //Route::get('/admin/reports/donut-report-by-week/list', 'Admin\ReportsController@getDonutReportByWeek')->name('admin.reports.donut-by-week-answer.list');
    //Route::get('/admin/reports/donut-report-by-week-answer/list', 'Admin\ReportsController@getDonutReportByWeekAnswer')->name('admin.reports.donut-by-week.list');

    // Route::get('/admin/reports/trend-report-by-week/list', 'Admin\ReportsController@getTrendReportByWeek')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-week.list');
    // Route::get('/admin/reports/trend-incident-by-week/list', 'Admin\ReportsController@getTrendIncidentByWeek')->where('id', '[0-9]+')->name('admin.reports.trend-incident-by-week.list');
    // Route::get('/admin/reports/donut-report-by-week/list', 'Admin\ReportsController@getDonutReportByWeek')->name('admin.reports.donut-by-week-answer.list');
    // Route::get('/admin/reports/donut-report-by-week-answer/list', 'Admin\ReportsController@getDonutReportByWeekAnswer')->name('admin.reports.donut-by-week.list');
    Route::get('/admin/reports/total-sms-by-month/list', 'Admin\ReportsController@getTotalSMSByMonth')->name('admin.reports.total-sms-by-month.list');
    Route::get('/admin/reports/average-time-by-month/list', 'Admin\ReportsController@getAverageTimeByMonth')->name('admin.reports.average-time-by-month.list');
    Route::get('/admin/reports/trend-report-by-month/list', 'Admin\ReportsController@getTrendReportByMonth')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-month.list');
    Route::get('/admin/reports/trend-incident-by-month/list', 'Admin\ReportsController@getTrendIncidentByMonth')->where('id', '[0-9]+')->name('admin.reports.trend-incident-by-month.list');
    Route::get('/admin/reports/donut-report-by-month/list', 'Admin\ReportsController@getDonutReportByMonth')->name('admin.reports.donut-by-month-answer.list');
    Route::get('/admin/reports/donut-report-by-month-answer/list', 'Admin\ReportsController@getDonutReportByMonthAnswer')->name('admin.reports.donut-by-month.list');

    Route::get('/admin/reports/total-sms-by-year/list', 'Admin\ReportsController@getTotalSMSByYear')->name('admin.reports.total-sms-by-year.list');
    Route::get('/admin/reports/average-time-by-year/list', 'Admin\ReportsController@getAverageTimeByYear')->name('admin.reports.average-time-by-year.list');
    Route::get('/admin/reports/trend-report-by-year/list', 'Admin\ReportsController@getTrendReportByYear')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-year.list');
    Route::get('/admin/reports/trend-incident-by-year/list', 'Admin\ReportsController@getTrendIncidentByYear')->where('id', '[0-9]+')->name('admin.reports.trend-incident-by-year.list');
    // Route::get('/admin/reports/donut-report-by-year/list', 'Admin\ReportsController@getDonutReportByYear')->name('admin.reports.donut-by-year-answer.list');
    // Route::get('/admin/reports/donut-report-by-year-answer/list', 'Admin\ReportsController@getDonutReportByYearAnswer')->name('admin.reports.donut-by-year.list');

    Route::get('/admin/reports/total-sms-by-years/list', 'Admin\ReportsController@getTotalSMSByYears')->name('admin.reports.total-sms-by-years.list');
    Route::get('/admin/reports/average-time-by-years/list', 'Admin\ReportsController@getAverageTimeByYears')->name('admin.reports.average-time-by-years.list');
    Route::get('/admin/reports/trend-report-by-years/list', 'Admin\ReportsController@getTrendReportByYears')->where('id', '[0-9]+')->name('admin.reports.trend-report-by-years.list');
    Route::get('/admin/reports/trend-incident-by-years/list', 'Admin\ReportsController@getTrendIncidentByYears')->where('id', '[0-9]+')->name('admin.reports.trend-incident-by-years.list');


    Route::get('/admin/reports/monthly-usage/download-csv', 'Admin\ReportsController@downloadCsvMonthlyUsage')->where('id', '[0-9]+')->name('admin.reports.monthly-usage.download-csv');
    Route::get('/admin/reports/yearly-usage', 'Admin\ReportsController@yearlyUsage')->name('admin.reports.yearly-usage');
    Route::get('/admin/reports/yearly-usage/list', 'Admin\ReportsController@getYearlyUsage')->where('id', '[0-9]+')->name('admin.reports.yearly-usage.list');
    Route::get('/admin/reports/yearly-usage/download-csv', 'Admin\ReportsController@downloadCsvYearlyUsage')->where('id', '[0-9]+')->name('admin.reports.yearly-usage.download-csv');
    Route::get('/admin/reports/is-helpful', 'Admin\ReportsController@isHelpful')->name('admin.reports.is-helpful');
    Route::get('/admin/reports/is-helpful/list', 'Admin\ReportsController@getIsHelpful')->name('admin.reports.is-helpful.list'); //
    Route::get('/admin/reports/questionnaire-survey/list', 'Admin\ReportsController@getQuestionnaireSurvey')->name('admin.reports.questionnaire-survey.list'); //
    Route::get('/admin/reports/is-helpful/response', 'Admin\ReportsController@getResponseNo')->name('admin.reports.is-helpful.response');
    Route::get('/admin/reports/is-helpful/other-response', 'Admin\ReportsController@getOtherResponse')->name('admin.reports.is-helpful.other-response');
    Route::get('/admin/reports/is-helpful/download-csv', 'Admin\ReportsController@downloadCsvIsHelpful')->name('admin.reports.is-helpful.download-csv');
    Route::get('/admin/reports/screen-uptime', 'Admin\ReportsController@screenUptime')->name('admin.reports.screen-uptime');
    Route::get('/admin/reports/uptime-history', 'Admin\ReportsController@uptimeHistory')->name('admin.reports.uptime-history');
    Route::get('/admin/reports/uptime-history/list', 'Admin\ReportsController@getUptimeHistory')->name('admin.reports.uptime-history-list');
    Route::get('/admin/reports/uptime-history/download-csv', 'Admin\ReportsController@downloadCsvUptimeHistory')->name('admin.reports.uptime-history.download-csv');

    Route::get('/admin/reports/kiosk-usage', 'Admin\ReportsController@kioskUsage')->name('admin.reports.kiosk-usage');
    Route::get('/admin/reports/kiosk-usage/list', 'Admin\ReportsController@getKioskUsage')->name('admin.reports.kiosk-usage-list');
    Route::get('/admin/reports/kiosk-usage/download-csv', 'Admin\ReportsController@downloadCsvKioskUsage')->name('admin.reports.kiosk-usage.download-csv');
    Route::get('/admin/reports/getFirstLastsurvey', 'Admin\ReportsController@getFirstLastSurvey')->name('admin.reports.first-last-survey');
    Route::get('/admin/dashboard/getFirstLastsurvey', 'Admin\DashboardController@getFirstLastSurvey')->name('admin.dashboard.first-last-survey');
    /*
    |--------------------------------------------------------------------------
    | Client User Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/client/users', 'Admin\ClientUserController@index')->name('admin.client.user');
    Route::get('/admin/client/users/list', 'Admin\ClientUserController@list')->name('admin.client.user.list');
    Route::post('/admin/client/users/store', 'Admin\ClientUserController@store')->name('admin.client.user.store');
    Route::get('/admin/client/users/{id}', 'Admin\ClientUserController@details')->where('id', '[0-9]+')->name('admin.client.user.details');
    Route::put('/admin/client/users/update', 'Admin\ClientUserController@update')->name('admin.client.user.update');
    Route::get('/admin/client/users/delete/{id}', 'Admin\ClientUserController@delete')->where('id', '[0-9]+')->name('admin.client.user.delete');
    Route::get('/admin/client/users/download-csv', 'Admin\ClientUserController@downloadCsv')->name('admin.client.user.download-csv');

    /*
    |--------------------------------------------------------------------------
    | FAQ's Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/faqs', 'Admin\FAQController@index')->name('admin.faqs');
    Route::get('/admin/faq/list', 'Admin\FAQController@list')->name('admin.faq.list');
    Route::post('/admin/faq/store', 'Admin\FAQController@store')->name('admin.faq.store');
    Route::get('/admin/faq/{id}', 'Admin\FAQController@details')->where('id', '[0-9]+')->name('admin.faq.details');
    Route::post('/admin/faq/update', 'Admin\FAQController@update')->name('admin.faq.update');
    Route::get('/admin/faq/delete/{id}', 'Admin\FAQController@delete')->where('id', '[0-9]+')->name('admin.faq.delete');
    Route::get('/admin/faq/download-csv', 'Admin\FAQController@downloadCsv')->name('admin.faq.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Gallery Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/gallery', 'Admin\GalleryController@index')->name('admin.gallery');
    Route::get('/admin/gallery/list', 'Admin\GalleryController@list')->name('admin.galleryfaqs.list');
    Route::get('/admin/gallery/{id}', 'Admin\GalleryController@details')->where('id', '[0-9]+')->name('admin.gallery.details');
    Route::post('/admin/gallery/update', 'Admin\GalleryController@update')->name('admin.gallery.update');
    Route::post('/admin/gallery/upload', 'Admin\GalleryController@upload')->name('admin.gallery.upload');

    /*
    |--------------------------------------------------------------------------
    | Customer Care Inquiry Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/customer-cares', 'Admin\CustomerCareController@index')->name('admin.customer-care');
    Route::get('/admin/customer-care/list', 'Admin\CustomerCareController@list')->name('admin.customer-care.list');
    Route::post('/admin/customer-care/store', 'Admin\CustomerCareController@store')->name('admin.customer-care.store');
    Route::get('/admin/customer-care/{id}', 'Admin\CustomerCareController@details')->where('id', '[0-9]+')->name('admin.customer-care.details');
    Route::post('/admin/customer-care/update', 'Admin\CustomerCareController@update')->name('admin.customer-care.update');
    Route::get('/admin/customer-care/delete/{id}', 'Admin\CustomerCareController@delete')->where('id', '[0-9]+')->name('admin.customer-care.delete');
    Route::get('/admin/customer-care/users', 'Admin\CustomerCareController@getUsers')->where('id', '[0-9]+')->name('admin.customer-care.users');
    Route::get('/admin/customer-care/download-csv', 'Admin\CustomerCareController@downloadCsv')->name('admin.customer-care.download-csv');
    /*
    |--------------------------------------------------------------------------
    | Customer Care Concern Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/customer-care/concerns', 'Admin\ConcernsController@index')->name('admin.concerns');
    Route::get('/admin/customer-care/concern/list', 'Admin\ConcernsController@list')->name('admin.concern.list');
    Route::post('/admin/customer-care/concern/store', 'Admin\ConcernsController@store')->name('admin.concern.store');
    Route::get('/admin/customer-care/concern/{id}', 'Admin\ConcernsController@details')->where('id', '[0-9]+')->name('admin.concern.details');
    Route::post('/admin/customer-care/concern/update', 'Admin\ConcernsController@update')->name('admin.concern.update');
    Route::get('/admin/customer-care/concern/delete/{id}', 'Admin\ConcernsController@delete')->where('id', '[0-9]+')->name('admin.concern.delete');
    Route::get('/admin/customer-care/concern/download-csv', 'Admin\ConcernsController@downloadCsv')->name('admin.concern.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Assistant Messages Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/assistant-messages', 'Admin\AssistantMessagesController@index')->name('admin.assistant-message');
    Route::get('/admin/assistant-message/list', 'Admin\AssistantMessagesController@list')->name('admin.assistant-message.list');
    Route::post('/admin/assistant-message/store', 'Admin\AssistantMessagesController@store')->name('admin.assistant-message.store');
    Route::get('/admin/assistant-message/{id}', 'Admin\AssistantMessagesController@details')->where('id', '[0-9]+')->name('admin.assistant-message.details');
    Route::post('/admin/assistant-message/update', 'Admin\AssistantMessagesController@update')->name('admin.assistant-message.update');
    Route::get('/admin/assistant-message/delete/{id}', 'Admin\AssistantMessagesController@delete')->where('id', '[0-9]+')->name('admin.assistant-message.delete');
    Route::get('/admin/assistant-message/download-csv', 'Admin\AssistantMessagesController@downloadCsv')->name('admin.assistant-message.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Translations Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/translations', 'Admin\TranslationsController@index')->name('admin.translation');
    Route::get('/admin/translation/list', 'Admin\TranslationsController@list')->name('admin.translation.list');
    Route::post('/admin/translation/store', 'Admin\TranslationsController@store')->name('admin.translation.store');
    Route::get('/admin/translation/{id}', 'Admin\TranslationsController@details')->where('id', '[0-9]+')->name('admin.translation.details');
    Route::post('/admin/translation/update', 'Admin\TranslationsController@update')->name('admin.translation.update');
    Route::get('/admin/translation/delete/{id}', 'Admin\TranslationsController@delete')->where('id', '[0-9]+')->name('admin.translation.delete');
    Route::get('/admin/translation/download-csv', 'Admin\TranslationsController@downloadCsv')->name('admin.translation.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Admin Users Information Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/users-information', 'Admin\UsersInformationController@index')->name('admin.users.information');
    Route::get('/admin/users-information/details', 'Admin\UsersInformationController@details')->name('admin.users.information.details');
    Route::post('/admin/users-information/update-profile', 'Admin\UsersInformationController@updateProfile')->name('portal.user.information.update-profile');

    /*
    |--------------------------------------------------------------------------
    | Admin Users Activity Logs Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/activity-logs/list', 'Admin\UserActivityLogsController@list')->name('admin.user.activity.logs.list');

    /*
    |--------------------------------------------------------------------------
    | Transaction Status Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/transaction/statuses/get-all', 'Admin\TransactionStatusController@getAll')->name('admin.transaction.statuses.get-all');


    /*
    |--------------------------------------------------------------------------
    | Pi Products Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/pi-products', 'Admin\PiProductController@index')->name('admin.site.pi-product-product');
    Route::get('/admin/site/pi-product/list', 'Admin\PiProductController@list')->name('admin.site.pi-product-product.list');
    Route::post('/admin/site/pi-product/store', 'Admin\PiProductController@store')->name('admin.site.pi-product-product.store');
    Route::get('/admin/site/pi-product/{id}', 'Admin\PiProductController@details')->where('id', '[0-9]+')->name('admin.site.pi-product-product.details');
    Route::put('/admin/site/pi-product/update', 'Admin\PiProductController@update')->name('admin.site.pi-product-product.update');
    Route::get('/admin/site/pi-product/delete/{id}', 'Admin\PiProductController@delete')->where('id', '[0-9]+')->name('admin.site.pi-product-product.delete');
    Route::get('/admin/site/pi-product/get-products', 'Admin\PiProductController@getProducts')->where('id', '[0-9]+')->name('admin.site.pi-product-product.get-products');

    /*
    |--------------------------------------------------------------------------
    | Sites Screen Products Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/site-screen-products', 'Admin\SiteScreenProductController@index')->name('admin.site.site-screen-product');
    Route::get('/admin/site/site-screen-product/list', 'Admin\SiteScreenProductController@list')->name('admin.site.site-screen-product.list');
    Route::post('/admin/site/site-screen-product/store', 'Admin\SiteScreenProductController@store')->name('admin.site.site-screen-product.store');
    Route::get('/admin/site/site-screen-product/{id}', 'Admin\SiteScreenProductController@details')->where('id', '[0-9]+')->name('admin.site.site-screen-product.details');
    Route::put('/admin/site/site-screen-product/update', 'Admin\SiteScreenProductController@update')->name('admin.site.site-screen-product.update');
    Route::get('/admin/site/site-screen-product/delete/{id}', 'Admin\SiteScreenProductController@delete')->where('id', '[0-9]+')->name('admin.site.site-screen-product.delete');
    Route::post('/admin/site/site-screen-product/get-screens', 'Admin\SiteScreenProductController@getScreen')->name('admin.site.site-screen-product.get-screens');

    /*
    |--------------------------------------------------------------------------
    | Questionnaires Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/questionnaires', 'Admin\QuestionnairesController@index')->name('admin.questionnaires');
    Route::get('/admin/questionnaire/list', 'Admin\QuestionnairesController@list')->name('admin.questionnaire.list');
    Route::post('/admin/questionnaire/store', 'Admin\QuestionnairesController@store')->name('admin.questionnaire.store');
    Route::get('/admin/questionnaire/{id}', 'Admin\QuestionnairesController@details')->where('id', '[0-9]+')->name('admin.questionnaire.details');
    Route::post('/admin/questionnaire/update', 'Admin\QuestionnairesController@update')->name('admin.questionnaire.update');
    Route::get('/admin/questionnaire/delete/{id}', 'Admin\QuestionnairesController@delete')->where('id', '[0-9]+')->name('admin.questionnaire.delete');
    Route::get('/admin/questionnaire/get-all', 'Admin\QuestionnairesController@getAll')->where('id', '[0-9]+')->name('admin.questionnaire.get-all');
    Route::get('/admin/questionnaire/set-default/{id}', 'Admin\QuestionnairesController@setDefault')->where('id', '[0-9]+')->name('admin.questionnaire.set-default');
    Route::get('/admin/questionnaire/download-csv', 'Admin\QuestionnairesController@downloadCsv')->name('admin.questionnaire.download-csv');


    /*
    |--------------------------------------------------------------------------
    | Questionnaire Answers Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/questionnaire/answers/{id}', 'Admin\AnswersController@index')->name('admin.questionaire.answers');
    Route::get('/admin/questionnaire/answers/list', 'Admin\AnswersController@list')->name('admin.questionaire.answers.list');
    // Route::post('/admin/site/building/store', 'Admin\BuildingsController@store')->name('admin.site.building.store');
    // Route::get('/admin/site/building/{id}', 'Admin\BuildingsController@details')->where('id', '[0-9]+')->name('admin.site.building.details');
    // Route::put('/admin/site/building/update', 'Admin\BuildingsController@update')->name('admin.site.building.update');
    // Route::get('/admin/site/building/delete/{id}', 'Admin\BuildingsController@delete')->where('id', '[0-9]+')->name('admin.site.building.delete');
    // Route::get('/admin/site/buildings', 'Admin\BuildingsController@getAll')->where('id', '[0-9]+')->name('admin.site.buildings.all');
    // Route::get('/admin/site/get-buildings/{id}', 'Admin\BuildingsController@getBuildings')->where('id', '[0-9]+')->name('admin.site.buildings.get-buildings');



    /*
    |--------------------------------------------------------------------------
    | Questionnaire Surveys Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/questionnaire/surveys', 'Admin\QuestionnaireSurveysController@index')->name('admin.questionnaire.surveys');
    Route::get('/admin/questionnaire/survey/list', 'Admin\QuestionnaireSurveysController@list')->name('admin.questionnaire.surveys.list');
    Route::post('/admin/questionnaire/survey/store', 'Admin\QuestionnaireSurveysController@store')->name('admin.questionnaire.surveys.store');
    Route::get('/admin/questionnaire/survey/{id}', 'Admin\QuestionnaireSurveysController@details')->where('id', '[0-9]+')->name('admin.questionnaire.surveys.details');
    Route::post('/admin/questionnaire/survey/update', 'Admin\QuestionnaireSurveysController@update')->name('admin.questionnaire.surveys.update');
    Route::get('/admin/questionnaire/survey/delete/{id}', 'Admin\QuestionnaireSurveysController@delete')->where('id', '[0-9]+')->name('admin.questionnaire.surveys.delete');
    // Route::get('/admin/questionnaire/survey/get-all', 'Admin\QuestionnaireSurveysController@getAll')->where('id', '[0-9]+')->name('admin.questionnaire.get-all');
    // Route::get('/admin/questionnaire/survey/set-default/{id}', 'Admin\QuestionnaireSurveysController@setDefault')->where('id', '[0-9]+')->name('admin.questionnaire.set-default');
    // Route::get('/admin/questionnaire/survey/download-csv', 'Admin\QuestionnaireSurveysController@downloadCsv')->name('admin.questionnaire.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Reservations Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/reservations', 'Admin\ReservationsController@index')->name('admin.reservations');
    Route::get('/admin/reservation/list', 'Admin\ReservationsController@list')->name('admin.reservation.list');
    Route::post('/admin/reservation/store', 'Admin\ReservationsController@store')->name('admin.reservation.store');
    Route::get('/admin/reservation/{id}', 'Admin\ReservationsController@details')->where('id', '[0-9]+')->name('admin.reservation.details');
    Route::post('/admin/reservation/update', 'Admin\ReservationsController@update')->name('admin.reservation.update');
    Route::get('/admin/reservation/delete/{id}', 'Admin\ReservationsController@delete')->where('id', '[0-9]+')->name('admin.reservation.delete');
    Route::get('/admin/reservation/get-all', 'Admin\ReservationsController@getAll')->where('id', '[0-9]+')->name('admin.reservation.get-all');
    Route::get('/admin/reservation/set-default/{id}', 'Admin\ReservationsController@setDefault')->where('id', '[0-9]+')->name('admin.reservation.set-default');
    Route::get('/admin/reservation/download-csv', 'Admin\ReservationsController@downloadCsv')->name('admin.reservation.download-csv');

    Route::post('/admin/logout', 'AdminAuth\AuthController@adminLogout')->name('admin.logout');
});
