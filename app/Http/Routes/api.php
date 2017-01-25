<?php

// Routes for the HTTP API
//
Route::group(['middleware' => ['api'], 'prefix' => 'api/v1'], function () {
    Route::post('auth/register', 'AuthController@register')->name('api.v1.auth.register');
    Route::post('auth/login', 'AuthController@login')->name('api.v1.auth.login');
    Route::post('auth/otp/verify', 'AuthController@verifyOtp')->name('api.v1.auth.otp.verify');
    Route::post('auth/logout', 'AuthController@logout')->name('api.v1.auth.logout');
});


//Auth enabled routes
Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'api/v1'], function () {

    Route::get('home', 'HomeController@index')->name('api.v1.home.index');

    Route::resource('user/team/members', 'UsersController@teamMembers');
    Route::resource('user/team', 'UsersController@team');
    Route::get('task/user/team', 'UsersController@allTeamUser');
    Route::resource('user/loans', 'UsersController@loans');
    Route::resource('user/teamloans', 'UsersController@teamLoans');
    Route::resource('user/trackingLists', 'UsersController@trackStatusLists');
    Route::post('user/enableTracking/{id}', 'UsersController@enableTracking');
    Route::resource('users', 'UsersController');
   
    Route::get('teams/referrals', 'TeamsController@referrals');
    Route::resource('teams', 'TeamsController');

    Route::resource('leads/source', 'LeadsController@source');
    Route::resource('leads', 'LeadsController');
    Route::resource('customers', 'CustomersController');
    Route::post('tasks/updateStatus/{id}', 'TasksController@updateStatus');
    Route::get('tasks/todayTasks', 'TasksController@todayTasks');
    Route::get('tasks/getUserTasks', 'TasksController@getUserTasks');
    Route::resource('tasks', 'TasksController');
    Route::resource('taskstatus', 'TaskStatusesController');
    Route::resource('taskstages', 'TaskStagesController');

    Route::resource('referrals', 'ReferralsController');

    Route::get('projects/getProjects', 'ProjectsController@getProjects');
    Route::resource('projects', 'ProjectsController');
    Route::resource('project-statuses', 'ProjectStatusesController');

    Route::resource('campaigns', 'CampaignsController');
    Route::get('builders/getBuilders', 'BuildersController@getBuilders');
    Route::resource('builders', 'BuildersController');

    Route::resource('team/members/banks', 'BanksController@teamMembersBanks');

    Route::resource('banks.projects', 'BanksProjectsController');
    Route::resource('banks.products', 'BanksProductsController');
    Route::get('banks/products/getProducts', 'BanksProductsController@bankProducts');
    Route::resource('banks/products', 'BanksProductsController@documentFilters');
    Route::resource('banks/projects/pending', 'BanksProjectsController@pending');
    Route::resource('banks/projects/approved', 'BanksProjectsController@approved');
    Route::resource('banks/projects/approved-by', 'BanksProjectsController@approvedBy');

    Route::resource('banks/filter', 'BanksController@filter');
    Route::get('banks/bankDocuments', 'BanksController@bankDocuments');
    Route::resource('banks', 'BanksController');

    Route::resource('addresses', 'AddressesController');
    Route::resource('cities', 'CitiesController');

    Route::post('startday', 'AttendancesController@startDay');
    Route::get('attendances/calendar/{uuid}', 'AttendancesController@calendar')->name('api.v1.attendances.calendar');
    Route::get('attendances/holidays', 'AttendancesController@holidays')->name('api.v1.attendances.holidays');
    Route::get('attendances', 'AttendancesController@index')->name('api.v1.attendances.index');

    Route::resource('queries', 'QueriesController');
    Route::resource('loans/type', 'LoansController@type');
    Route::resource('loans/tasks', 'LoansController@getTasks');
    Route::resource('loans/statuses', 'LoansController@getLoanStatuses');
    Route::post('loans/updateStatus/{id}', 'LoansController@updateStatus');
    Route::post('loans/upload/{id}', 'LoansController@upload');
    Route::resource('loans', 'LoansController');
    Route::get('products/productFilters', 'ProductsController@productFilters');
    Route::resource('products', 'ProductsController');
    Route::resource('search/customers', 'SearchController@customers');
    Route::resource('search/leads', 'SearchController@leads');
    Route::resource('search', 'SearchController');
    Route::get('dashboard/teams', 'DashboardController@teams');
    Route::get('dashboard/builders', 'DashboardController@builders');
    Route::get('dashboard/referrals', 'DashboardController@referrals');
});

Route::group(
    [
        'middleware' => ['api', 'auth:api'],
        'prefix'     => 'api/v1/consumers',
        'namespace'  => 'Consumers',
    ],
    function () {
        Route::resource('profile', 'ProfileController');
        Route::resource('loans', 'LoansController');
        Route::resource('referrals', 'LeadsController');
        Route::resource('loanalerts', 'LoanAlertController');
        Route::get('feedback/category', 'FeedbackCategoryController@index');
        Route::post('feedbacks', 'FeedbackController@getFeedbacks');
        Route::post('feedbacks/submit', 'FeedbackController@submitFeedbacks');
        Route::get('myreferral', 'ReferralsController@index');
        Route::post('myreferral', 'ReferralsController@newreferral');
    }
);
Route::group(
        [
            'middleware' => ['api'],
            'prefix' => 'api/v1/consumers',
            'namespace'  => 'Consumers'
        ],
        function () {
            Route::post('lead/new', 'LeadsController@newLead');
            Route::resource('cities', 'CitiesController');
            Route::resource('source', 'SourceController');
            Route::resource('loantype', 'TypeController');
            Route::post('banksbycity', 'BanksController@getBankByCity');
            Route::post('banks/products', 'BanksProductsController@documentFilters');
        }
);
