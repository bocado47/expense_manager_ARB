<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/','HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/adduser','UserController@store');
Route::get('/addrole','RoleController@store');
Route::get('/addcategory','CategoriesController@store');
Route::get('/addexpense','ExpenseController@store');
Route::get('/display','UserController@display');
Route::get('/displayR','RoleController@display');
Route::get('/displayC','CategoriesController@display');
Route::get('displayE','ExpenseController@display');
Route::get('/getpiedata','HomeController@getpiedata');


Route::resource('/user','UserController');
Route::resource('/roles','RoleController');
Route::resource('/categories','CategoriesController');
Route::resource('/expense','ExpenseController');


Route::post('/UserController/getData','UserController@getData')->name('dataProcessing');
Route::post('/RoleController/getData','RoleController@getData')->name('dataRole');
Route::post('/CategoriesController/getData','CategoriesController@getData')->name('dataCategory');
Route::post('/ExpenseController/getData','ExpenseController@getData')->name('dataExpense');
Route::post('/updateUser','UserController@updateUser');
Route::post('/deleteUser','UserController@deleteUser');
Route::post('/updateRole','RoleController@updateRole');
Route::post('/deleteRole','RoleController@deleteRole');
Route::post('/updateCategory','CategoriesController@updateCategory');
Route::post('/deleteCategory','CategoriesController@deleteCategory');
Route::post('/updateExpense','ExpenseController@updateExpense');
Route::post('/deleteExpense','ExpenseController@deleteExpense');
